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

        return view('admin.teacher.index', [
            'teachers' => $teachers
        ]);
    }

    public function userShow(){
        $teachers = Teacher::with('department')->paginate(20);

        return view('userteachershow', [
            'teachers' => $teachers
        ]);
    }

    public function search(Request $request)
    {
        $searchQuery = $request->input('search');

        $departmentId = $request->input('department_id');

        $query = Teacher::with('department');
        
        // Filter by department_id if provided
        if($departmentId) {
            $query->where('department_id', $departmentId);
        }
        
        // Apply search filters
        if ($searchQuery) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('name', 'like', '%' . $searchQuery . '%')
                  ->orWhere('position', 'like', '%' . $searchQuery . '%')
                  ->orWhere('phone_number', 'like', '%' . $searchQuery . '%')
                  ->orWhere('email', 'like', '%' . $searchQuery . '%')
                  ->orWhere('id', 'like', '%' . $searchQuery . '%')
                  ->orWhereHas('department', function ($deptQuery) use ($searchQuery) {
                      $deptQuery->where('fullname', 'like', '%' . $searchQuery . '%')
                               ->orWhere('shortname', 'like', '%' . $searchQuery . '%')
                               ->orWhere('deptCode', 'like', '%' . $searchQuery . '%');
                  });
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

    public function showTeachers(Teacher $teacher)
    {
        return view('admin.teacher.userteachershow', [
            'teacher' => $teacher
        ]);
    }

    



    public function create()
    {
        $departments = Department::all();

        return view('admin.teacher.create', [
            'departments' => $departments
        ]);
    }

    public function store(Request $request)
    {
        $formData = $request->validate([
            'name' => 'required|string',
            'position' => 'required|string',
            'phone_number' => 'required|string|unique:teachers,phone_number',
            'email' => 'nullable|email|unique:teachers,email',
            'department_id' => 'required|exists:departments,id',
            'image' => 'nullable|file|mimes:jpeg,png,jpg|max:2048'
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
            $formData['image'] = "assets/teachers/{$formData['phone_number']}/{$fileName}";
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

    public function update(Request $request, Teacher $teacher)
    {
        $formData = $request->validate([
            'name' => 'required|string',
            'position' => 'required|string',
            'phone_number' => [
                'required',
                Rule::unique('teachers')->ignore($teacher->id),
            ],
            'email' => [
                'nullable',
                'email',
                Rule::unique('teachers')->ignore($teacher->id),
            ],
            'department_id' => 'required|exists:departments,id',
            'image' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
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
            $formData['image'] = "assets/teachers/{$formData['phone_number']}/{$fileName}";
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
