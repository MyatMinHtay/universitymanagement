<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('department')->paginate(10);
        $studentcounts = Student::count();

        return view('admin.student.index', [
            'students' => $students,
            'studentcounts' => $studentcounts
        ]);
    }

    /**
     * Display students on the public user page with pagination
     */
    public function userShow(){
        $students = Student::with('department')->paginate(20);
        $studentcounts = Student::count();

        return view('userstudentshow', [
            'students' => $students,
            'studentcounts' => $studentcounts
        ]);
    }

    /**
     * Advanced search for students with multiple filters and keyword support
     * Handles: ID (exact match), name, year, seat_number, phone, email, department searches
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

        $query = Student::with('department');

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        if ($searchQuery) {
            $keywords = array_filter(explode(' ', trim($searchQuery)));
            $keywordCount = count($keywords);

            // If 'all' is selected, convert it to all available filter types
            if (in_array('all', $filters)) {
                $filters = ['id', 'name', 'year', 'seat_number', 'phone', 'email', 'department'];
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
                        
                        if (in_array('year', $filters)) {
                            $conditions[] = ['type' => 'where', 'field' => 'year', 'operator' => 'LIKE', 'value' => '%' . $keyword . '%'];
                        }
                        
                        if (in_array('seat_number', $filters)) {
                            $conditions[] = ['type' => 'where', 'field' => 'seat_number', 'operator' => 'LIKE', 'value' => '%' . $keyword . '%'];
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
                                
                                if (in_array('year', $filters)) {
                                    $conditions[] = ['type' => 'where', 'field' => 'year', 'operator' => 'LIKE', 'value' => '%' . $keyword . '%'];
                                }
                                
                                if (in_array('seat_number', $filters)) {
                                    $conditions[] = ['type' => 'where', 'field' => 'seat_number', 'operator' => 'LIKE', 'value' => '%' . $keyword . '%'];
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

        $students = $query->get();
        return response()->json($students);
    }



    public function create()
    {
        $departments = Department::all();

        return view('admin.student.create', [
            'departments' => $departments,
        ]);
    }

    public function show(Student $student)
    {
        return view('admin.student.show', [
            'student' => $student,
        ]);
    }

    /**
     * Display individual student details on public user page
     */
    public function showStudents(Student $student)
    {
        return view('admin.student.userstushow', [
            'student' => $student,
        ]);
    }

    /**
     * Create new student with image upload and organized file storage
     * Images are stored in assets/students/{seat_number}/ directory
     */
    public function store(Request $request)
    {
        $formData = $request->validate([
            'name' => 'required|string',
            'year' => 'required|string',
            'seat_number' => 'required|string',
            'phone_number' => 'nullable|string|unique:students,phone_number',
            'email' => 'nullable|email|unique:students,email',
            'image' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
            'department_id' => 'required|exists:departments,id',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $originalName = $file->getClientOriginalName();
            $cleanName = preg_replace('/[^A-Za-z0-9_\-\.]/', '', preg_replace('/\s+/', '_', $originalName));
            $fileName = time() . '_' . $cleanName;

            $uploadPath = public_path("assets/students/{$formData['seat_number']}");
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $file->move($uploadPath, $fileName);
            $formData['image'] = "assets/students/{$formData['seat_number']}/$fileName";
        }else{
            $formData['image'] = "assets/students/profile.png";
        }

        try {
            Student::create($formData);
        } catch (QueryException $e) {
            return back()->withErrors(['error' => 'Failed to create student: ' . $e->getMessage()])->withInput();
        }

        return redirect()->route('students')->with('success', 'Student created successfully.');
    }

    public function edit(Student $student)
    {
        $departments = Department::all();

        return view('admin.student.edit', [
            'student' => $student,
            'departments' => $departments,
        ]);
    }

    /**
     * Update student with image handling and file cleanup
     * Removes old image and creates new organized directory structure
     */
    public function update(Request $request, Student $student)
    {
        $formData = $request->validate([
            'name' => 'required|string',
            'year' => 'required|string',
            'seat_number' => 'required|string',
            'phone_number' => [
                'nullable',
                'string',
                Rule::unique('students')->ignore($student->id),
            ],
            'email' => [
                'nullable',
                'email',
                Rule::unique('students')->ignore($student->id),
            ],
            'image' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
            'department_id' => 'required|exists:departments,id',
        ]);

        if ($request->hasFile('image')) {
            if (!empty($student->image)) {
                $oldImagePath = public_path($student->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $file = $request->file('image');
            $originalName = $file->getClientOriginalName();
            $cleanName = preg_replace('/[^A-Za-z0-9_\-\.]/', '', preg_replace('/\s+/', '_', $originalName));
            $fileName = time() . '_' . $cleanName;

            $uploadPath = public_path("assets/students/{$formData['seat_number']}");
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $file->move($uploadPath, $fileName);
            $formData['image'] = "assets/students/{$formData['seat_number']}/$fileName";
        } else {
            $formData['image'] = $student->image;
        }

        try {
            $student->update($formData);
        } catch (QueryException $e) {
            return back()->withErrors(['error' => 'Failed to update student: ' . $e->getMessage()]);
        }

        return redirect()->route('students')->with('success', 'Student updated successfully.');
    }

    /**
     * Delete student and cleanup associated files/directories
     * Removes entire student directory from assets/students/{seat_number}
     */
    public function destroy(Student $student)
    {
        try {
            $folderPath = public_path("assets/students/{$student->seat_number}");

            if (File::exists($folderPath)) {
                File::deleteDirectory($folderPath);
            }

            $student->delete();
        } catch (QueryException $e) {
            return back()->withErrors(['error' => 'Failed to delete student: ' . $e->getMessage()]);
        }

        return redirect()->route('students')->with('success', 'Student deleted successfully.');
    }
}
