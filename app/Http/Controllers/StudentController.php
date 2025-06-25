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
        $students = Student::with('department')->paginate(2);

        return view('admin.student.index', [
            'students' => $students,
        ]);
    }

    public function search(Request $request)
    {
        $searchQuery = $request->input('search');
        $departmentId = $request->input('department_id');
        
        $query = Student::with('department');
        
        // Filter by department_id if provided
        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }
        
        // Apply search filters
        if ($searchQuery) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('name', 'like', '%' . $searchQuery . '%')
                  ->orWhere('seat_number', 'like', '%' . $searchQuery . '%')
                  ->orWhere('year', 'like', '%' . $searchQuery . '%');
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

    public function store(Request $request)
    {
        $formData = $request->validate([
            'name' => 'required|string',
            'year' => 'required|string',
            'seat_number' => 'required|string|unique:students,seat_number',
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
