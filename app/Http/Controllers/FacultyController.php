<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\File;

class FacultyController extends Controller
{
    public function index()
    {
        $faculty = Faculty::with('department')->paginate(20);

        return view('admin.faculty.index', [
            'faculty' => $faculty
        ]);
    }

    public function userShow()
    {
        $faculty = Faculty::with('department')->paginate(20);

        return view('userfacultyshow', [
            'faculty' => $faculty
        ]);
    }

    public function search(Request $request)
    {
        $searchQuery = $request->input('search');
        $departmentId = $request->input('department_id');
        $filters = $request->input('filter', ['all']); // Accept array of filters
        
        // Ensure filters is always an array
        if (!is_array($filters)) {
            $filters = [$filters];
        }

        $query = Faculty::with('department');
        
        // Filter by department_id if provided
        if($departmentId) {
            $query->where('department_id', $departmentId);
        }
        
        // Apply search filters - focusing on specific fields: id, name, position, phone, email (no department for faculty)
        if ($searchQuery) {
            // Split search query into multiple keywords
            $keywords = explode(' ', trim($searchQuery));
            
            $query->where(function ($q) use ($keywords, $filters) {
                foreach ($keywords as $index => $keyword) {
                    if (empty(trim($keyword))) continue;
                    
                    $method = $index === 0 ? 'where' : 'orWhere';
                    
                    $q->$method(function ($subQuery) use ($keyword, $filters) {
                        // Check if 'all' filter is selected or if specific filters are selected
                        if (in_array('all', $filters)) {
                            $subQuery->where('id', $keyword)
                                   ->orWhere('name', 'like', '%' . $keyword . '%')
                                   ->orWhere('position', 'like', '%' . $keyword . '%')
                                   ->orWhere('phone_number', 'like', '%' . $keyword . '%')
                                   ->orWhere('email', 'like', '%' . $keyword . '%');
                        } else {
                            // Apply specific filters
                            $hasCondition = false;
                            
                            if (in_array('id', $filters)) {
                                $subQuery->where('id', $keyword);
                                $hasCondition = true;
                            }
                            
                            if (in_array('name', $filters)) {
                                $method = $hasCondition ? 'orWhere' : 'where';
                                $subQuery->$method('name', 'like', '%' . $keyword . '%');
                                $hasCondition = true;
                            }
                            
                            if (in_array('position', $filters)) {
                                $method = $hasCondition ? 'orWhere' : 'where';
                                $subQuery->$method('position', 'like', '%' . $keyword . '%');
                                $hasCondition = true;
                            }
                            
                            if (in_array('phone', $filters)) {
                                $method = $hasCondition ? 'orWhere' : 'where';
                                $subQuery->$method('phone_number', 'like', '%' . $keyword . '%');
                                $hasCondition = true;
                            }
                            
                            if (in_array('email', $filters)) {
                                $method = $hasCondition ? 'orWhere' : 'where';
                                $subQuery->$method('email', 'like', '%' . $keyword . '%');
                                $hasCondition = true;
                            }
                        }
                    });
                }
            });
        }
        
        $faculty = $query->get();

        return response()->json($faculty);
    }

    public function show(Faculty $faculty)
    {
        return view('admin.faculty.show', [
            'faculty' => $faculty
        ]);
    }

    public function showFaculty(Faculty $faculty)
    {
        return view('userfacultydetail', [
            'faculty' => $faculty
        ]);
    }

    public function create()
    {
        $departments = Department::all();

        return view('admin.faculty.create', [
            'departments' => $departments
        ]);
    }

    public function store(Request $request)
    {
        $formData = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'phone_number' => 'required|string|unique:faculty,phone_number|max:20',
            'email' => 'required|email|unique:faculty,email|max:255',
            'department_id' => 'required|exists:departments,id',
            'image' => 'nullable|file|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $originalName = $file->getClientOriginalName();
            $cleanName = preg_replace('/[^A-Za-z0-9_\-\.]/', '', preg_replace('/\s+/', '_', $originalName));
            $fileName = time() . '_' . $cleanName;

            $uploadPath = public_path("assets/faculty/{$formData['phone_number']}");
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $file->move($uploadPath, $fileName);
            $formData['image'] = "assets/faculty/{$formData['phone_number']}/{$fileName}";
        } else {
            $formData['image'] = "assets/faculty/profile.png";
        }

        try {
            Faculty::create($formData);
        } catch (QueryException $e) {
            return back()->withErrors(['error' => 'Failed to create faculty: ' . $e->getMessage()])->withInput();
        }

        return redirect()->route('faculty')->with('success', 'Faculty member created successfully.');
    }

    public function edit(Faculty $faculty)
    {
        $departments = Department::all();

        return view('admin.faculty.edit', [
            'faculty' => $faculty,
            'departments' => $departments
        ]);
    }

    public function update(Request $request, Faculty $faculty)
    {
        $formData = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'phone_number' => [
                'required',
                'string',
                'max:20',
                Rule::unique('faculty')->ignore($faculty->id),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('faculty')->ignore($faculty->id),
            ],
            'department_id' => 'required|exists:departments,id'
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if (!empty($faculty->image) && $faculty->image !== 'assets/faculty/profile.png') {
                $oldImagePath = public_path($faculty->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $file = $request->file('image');
            $originalName = $file->getClientOriginalName();
            $cleanName = preg_replace('/[^A-Za-z0-9_\-\.]/', '', preg_replace('/\s+/', '_', $originalName));
            $fileName = time() . '_' . $cleanName;

            $uploadPath = public_path("assets/faculty/{$formData['phone_number']}");
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $file->move($uploadPath, $fileName);
            $formData['image'] = "assets/faculty/{$formData['phone_number']}/{$fileName}";
        } else {
            $formData['image'] = $faculty->image;
        }

        try {
            $faculty->update($formData);
        } catch (QueryException $e) {
            return back()->withErrors(['error' => 'Failed to update faculty: ' . $e->getMessage()]);
        }

        return redirect()->route('faculty')->with('success', 'Faculty member updated successfully.');
    }

    public function destroy(Faculty $faculty)
    {
        try {
            // Delete faculty folder with images
            $folderPath = public_path("assets/faculty/{$faculty->phone_number}");
            if (File::exists($folderPath)) {
                File::deleteDirectory($folderPath);
            }

            $faculty->delete();
        } catch (QueryException $e) {
            return back()->withErrors(['error' => 'Failed to delete faculty: ' . $e->getMessage()]);
        }

        return redirect()->route('faculty')->with('success', 'Faculty member deleted successfully.');
    }
} 