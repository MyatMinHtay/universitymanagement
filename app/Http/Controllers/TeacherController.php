<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\File;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with('department')->paginate(20);
        $teachercounts = Teacher::count();

        return view('admin.teacher.index', [
            'teachers' => $teachers,
            'teachercounts' => $teachercounts
        ]);
    }

    /**
     * Display teachers on the public user page with pagination
     */
    public function userShow(){
        $teachers = Teacher::with('department')->paginate(20);
        $teachercounts = Teacher::count();

        return view('userteachershow', [
            'teachers' => $teachers,
            'teachercounts' => $teachercounts
        ]);
    }

    /**
     * Advanced search for teachers with multiple filters and keyword support
     * Handles: ID (exact match), name, position, phone, email, department searches
     * Supports multiple keywords separated by spaces using OR logic
     */
    public function search(Request $request)
    {
        $searchQuery = $request->input('search');
        $departmentId = $request->input('department_id');
        $filters = $request->input('filter', ['all']); // Accept array of filters
        
        // Ensure filters is always an array
        if (!is_array($filters)) {
            $filters = [$filters];
        }
        
        $query = Teacher::with('department');
        
        // Filter by department_id if provided
        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }
        
        // Apply search filters - focusing on specific fields: id, name, position, phone, email
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
                                   ->orWhere('email', 'like', '%' . $keyword . '%')
                                   ->orWhereHas('department', function ($deptQuery) use ($keyword) {
                                       $deptQuery->where('fullname', 'like', '%' . $keyword . '%')
                                               ->orWhere('shortname', 'like', '%' . $keyword . '%');
                                   });
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
                            
                            if (in_array('department', $filters)) {
                                $method = $hasCondition ? 'orWhereHas' : 'whereHas';
                                $subQuery->$method('department', function ($deptQuery) use ($keyword) {
                                    $deptQuery->where('fullname', 'like', '%' . $keyword . '%')
                                            ->orWhere('shortname', 'like', '%' . $keyword . '%')
                                            ->orWhere('deptCode', 'like', '%' . $keyword . '%');
                                });
                                $hasCondition = true;
                            }
                        }
                    });
                }
            });
        }
        
        $teachers = $query->get();

        return response()->json($teachers);
    }

    public function show(Teacher $teacher)
    {
        return view('admin.teacher.show', [
            'teacher' => $teacher
        ]);
    }

    /**
     * Display individual teacher details on public user page
     */
    public function showTeachers(Teacher $teacher)
    {
        return view('admin.teacher.userteachershow', [
            'teacher' => $teacher,
        ]);
    }

    public function create()
    {
        $departments = Department::all();

        return view('admin.teacher.create', [
            'departments' => $departments
        ]);
    }

    /**
     * Create new teacher with image upload and organized file storage
     * Images are stored in assets/teachers/{phone_number}/ directory
     */
    public function store(Request $request)
    {
        $formData = $request->validate([
            'name' => 'required|string',
            'position' => 'required|string',
            'phone_number' => 'nullable|string|unique:teachers,phone_number',
            'email' => 'nullable|email|unique:teachers,email',
            'image' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
            'department_id' => 'required|exists:departments,id',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $originalName = $file->getClientOriginalName();
            $cleanName = preg_replace('/[^A-Za-z0-9_\-\.]/', '', preg_replace('/\s+/', '_', $originalName));
            $fileName = time() . '_' . $cleanName;

            $uploadPath = public_path("assets/teachers/{$formData['phone_number']}");
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $file->move($uploadPath, $fileName);
            $formData['image'] = "assets/teachers/{$formData['phone_number']}/$fileName";
        }else{
            $formData['image'] = "assets/teachers/profile.png";
        }

        try {
            Teacher::create($formData);
        } catch (QueryException $e) {
            return back()->withErrors(['error' => 'Failed to create teacher: ' . $e->getMessage()])->withInput();
        }

        return redirect()->route('teachers')->with('success', 'Teacher created successfully.');
    }

    public function edit(Teacher $teacher)
    {
        $departments = Department::all();

        return view('admin.teacher.edit', [
            'teacher' => $teacher,
            'departments' => $departments
        ]);
    }

    /**
     * Update teacher with image handling and file cleanup
     * Removes old image and creates new organized directory structure
     */
    public function update(Request $request, Teacher $teacher)
    {
        $formData = $request->validate([
            'name' => 'required|string',
            'position' => 'required|string',
            'phone_number' => [
                'nullable',
                'string',
                Rule::unique('teachers')->ignore($teacher->id),
            ],
            'email' => [
                'nullable',
                'email',
                Rule::unique('teachers')->ignore($teacher->id),
            ],
            'image' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
            'department_id' => 'required|exists:departments,id',
        ]);

        if ($request->hasFile('image')) {
            if (!empty($teacher->image)) {
                $oldImagePath = public_path($teacher->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $file = $request->file('image');
            $originalName = $file->getClientOriginalName();
            $cleanName = preg_replace('/[^A-Za-z0-9_\-\.]/', '', preg_replace('/\s+/', '_', $originalName));
            $fileName = time() . '_' . $cleanName;

            $uploadPath = public_path("assets/teachers/{$formData['phone_number']}");
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $file->move($uploadPath, $fileName);
            $formData['image'] = "assets/teachers/{$formData['phone_number']}/$fileName";
        } else {
            $formData['image'] = $teacher->image;
        }

        try {
            $teacher->update($formData);
        } catch (QueryException $e) {
            return back()->withErrors(['error' => 'Failed to update teacher: ' . $e->getMessage()]);
        }

        return redirect()->route('teachers')->with('success', 'Teacher updated successfully.');
    }

    /**
     * Delete teacher and cleanup associated files/directories
     * Removes entire teacher directory from assets/teachers/{phone_number}
     */
    public function destroy(Teacher $teacher)
    {
        try {
            $folderPath = public_path("assets/teachers/{$teacher->phone_number}");

            if (File::exists($folderPath)) {
                File::deleteDirectory($folderPath);
            }

            $teacher->delete();
        } catch (QueryException $e) {
            return back()->withErrors(['error' => 'Failed to delete teacher: ' . $e->getMessage()]);
        }

        return redirect()->route('teachers')->with('success', 'Teacher deleted successfully.');
    }
}
