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
        $filters = $request->input('filter', ['all']);

        if (!is_array($filters)) {
            $filters = [$filters];
        }

        $query = Teacher::with('department');

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        if ($searchQuery) {
            $keywords = array_filter(explode(' ', trim($searchQuery)));
            $keywordCount = count($keywords);

            // If 'all' is selected, convert it to all available filter types
            if (in_array('all', $filters)) {
                $filters = ['id', 'name', 'position', 'phone', 'email', 'department'];
            }

            $query->where(function ($q) use ($searchQuery, $keywords, $filters, $keywordCount) {
                if ($keywordCount === 1) {
                    $keyword = strtolower($keywords[0]);

                    // Build search conditions based on selected filters
                    $q->where(function ($subQuery) use ($keyword, $filters) {
                        $conditions = [];
                        
                        // Collect all conditions first
                        if (in_array('id', $filters)) {
                            $conditions[] = ['type' => 'where', 'field' => 'id', 'operator' => '=', 'value' => $keyword];
                        }
                        
                        if (in_array('name', $filters)) {
                            $conditions[] = ['type' => 'where', 'field' => 'name', 'operator' => 'LIKE', 'value' => '%' . $keyword . '%'];
                        }
                        
                        if (in_array('position', $filters)) {
                            $conditions[] = ['type' => 'where', 'field' => 'position', 'operator' => 'LIKE', 'value' => '%' . $keyword . '%'];
                        }
                        
                        if (in_array('phone', $filters)) {
                            $conditions[] = ['type' => 'where', 'field' => 'phone_number', 'operator' => 'LIKE', 'value' => '%' . $keyword . '%'];
                        }
                        
                        if (in_array('email', $filters)) {
                            $conditions[] = ['type' => 'where', 'field' => 'email', 'operator' => 'LIKE', 'value' => '%' . $keyword . '%'];
                        }
                        
                        if (in_array('department', $filters)) {
                            $conditions[] = ['type' => 'whereHas', 'relation' => 'department'];
                        }
                        
                        // Apply conditions with proper OR logic
                        foreach ($conditions as $index => $condition) {
                            if ($condition['type'] === 'where') {
                                $method = $index === 0 ? 'whereRaw' : 'orWhereRaw';
                                $field = $condition['field'];
                                $operator = $condition['operator'];
                                $value = $condition['value'];
                                
                                if ($operator === '=') {
                                    $subQuery->$method("LOWER($field) = ?", [$value]);
                                } else {
                                    $subQuery->$method("LOWER($field) LIKE ?", [$value]);
                                }
                            } elseif ($condition['type'] === 'whereHas') {
                                $method = $index === 0 ? 'whereHas' : 'orWhereHas';
                                $subQuery->$method('department', function ($deptQuery) use ($keyword) {
                                    $deptQuery->whereRaw('LOWER(fullname) LIKE ?', ['%' . $keyword . '%'])
                                        ->orWhereRaw('LOWER(shortname) LIKE ?', ['%' . $keyword . '%'])
                                        ->orWhereRaw('LOWER(deptCode) LIKE ?', ['%' . $keyword . '%']);
                                });
                            }
                        }
                    });
                } else {
                    // Multi-keyword: use exact name match only if name is the only filter
                    if (in_array('name', $filters) && count($filters) === 1) {
                        $q->whereRaw('LOWER(name) = ?', [strtolower($searchQuery)]);
                    } else {
                        // For multi-keyword searches with multiple filters, use OR logic between keywords
                        foreach ($keywords as $index => $keyword) {
                            if (empty($keyword)) continue;

                            $method = $index === 0 ? 'where' : 'orWhere';

                            $q->$method(function ($subQuery) use ($keyword, $filters) {
                                $keyword = strtolower($keyword);
                                $conditions = [];
                                
                                if (in_array('id', $filters)) {
                                    $conditions[] = ['type' => 'where', 'field' => 'id', 'operator' => '=', 'value' => $keyword];
                                }
                                
                                if (in_array('name', $filters)) {
                                    $conditions[] = ['type' => 'where', 'field' => 'name', 'operator' => 'LIKE', 'value' => '%' . $keyword . '%'];
                                }
                                
                                if (in_array('position', $filters)) {
                                    $conditions[] = ['type' => 'where', 'field' => 'position', 'operator' => 'LIKE', 'value' => '%' . $keyword . '%'];
                                }
                                
                                if (in_array('phone', $filters)) {
                                    $conditions[] = ['type' => 'where', 'field' => 'phone_number', 'operator' => 'LIKE', 'value' => '%' . $keyword . '%'];
                                }
                                
                                if (in_array('email', $filters)) {
                                    $conditions[] = ['type' => 'where', 'field' => 'email', 'operator' => 'LIKE', 'value' => '%' . $keyword . '%'];
                                }
                                
                                if (in_array('department', $filters)) {
                                    $conditions[] = ['type' => 'whereHas', 'relation' => 'department'];
                                }
                                
                                foreach ($conditions as $condIndex => $condition) {
                                    if ($condition['type'] === 'where') {
                                        $method = $condIndex === 0 ? 'whereRaw' : 'orWhereRaw';
                                        $field = $condition['field'];
                                        $operator = $condition['operator'];
                                        $value = $condition['value'];
                                        
                                        if ($operator === '=') {
                                            $subQuery->$method("LOWER($field) = ?", [$value]);
                                        } else {
                                            $subQuery->$method("LOWER($field) LIKE ?", [$value]);
                                        }
                                    } elseif ($condition['type'] === 'whereHas') {
                                        $method = $condIndex === 0 ? 'whereHas' : 'orWhereHas';
                                        $subQuery->$method('department', function ($deptQuery) use ($keyword) {
                                            $deptQuery->whereRaw('LOWER(fullname) LIKE ?', ['%' . $keyword . '%'])
                                                ->orWhereRaw('LOWER(shortname) LIKE ?', ['%' . $keyword . '%'])
                                                ->orWhereRaw('LOWER(deptCode) LIKE ?', ['%' . $keyword . '%']);
                                        });
                                    }
                                }
                            });
                        }
                    }
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
