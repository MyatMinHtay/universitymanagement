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

        return view('admin.student.index', [
            'students' => $students,
        ]);
    }

    public function userShow(){
        $students = Student::with('department')->paginate(20);
        $studentcounts = Student::count();

        return view('userstudentshow', [
            'students' => $students,
            'studentcounts' => $studentcounts
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
        
        $query = Student::with('department');
        
        // Filter by department_id if provided
        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }
        
        // Apply search filters - focusing on specific fields: id, name, year, seat_number, phone, email
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
                                   ->orWhere('year', 'like', '%' . $keyword . '%')
                                   ->orWhere('seat_number', 'like', '%' . $keyword . '%')
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
                            
                            if (in_array('year', $filters)) {
                                $method = $hasCondition ? 'orWhere' : 'where';
                                $subQuery->$method('year', 'like', '%' . $keyword . '%');
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

    public function showStudents(Student $student)
    {
        return view('admin.student.userstushow', [
            'student' => $student,
        ]);
    }

    public function store(Request $request)
    {
        $formData = $request->validate([
            'name' => 'required|string',
            'year' => 'required|string',
            'seat_number' => 'required|string|unique:students,seat_number',
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

    public function update(Request $request, Student $student)
    {
        $formData = $request->validate([
            'name' => 'required|string',
            'year' => 'required|string',
            'seat_number' => [
                'required',
                Rule::unique('students')->ignore($student->id),
            ],
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
