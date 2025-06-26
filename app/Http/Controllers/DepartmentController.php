<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Department;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;
use Illuminate\Database\QueryException;

class DepartmentController extends Controller
{
    public function index(){

        $departments = Department::all();


        return view('admin.department.index',[
            'departments' => $departments
        ]);

    }

    public function showDepartmentsProfile(Department $department){

     

        return view('userdepartmentshow',[
            'department' => $department
        ]);
    }

    public function showDepartments(){

        $departments = Department::all();

      

        return view('departments',[
            'departments' => $departments
        ]);
    }

    public function search(Request $request){
        $searchQuery = $request->input('search');
        $departments = Department::where(function ($query) use ($searchQuery) {
            $query->where('fullname', 'like', '%' . $searchQuery . '%')
                ->orWhere('shortname', 'like', '%' . $searchQuery . '%')
                ->orWhere('deptCode', 'like', '%' . $searchQuery . '%')
                ->orWhere('id', 'like', '%' . $searchQuery . '%');
        })->get();
        return response()->json($departments);
    }

    public function create(){
        return view('admin.department.create');
    }

    public function show(Department $department){
        return view('admin.department.show',[
            'department' => $department
        ]);
    }

    public function store(Request $request){

        

        $formData = $request->validate([
            'fullname' => 'required',
            'shortname' => 'required',
            'deptCode' => 'required',
            'logo' => 'required|file|mimes:jpeg,png,jpg|max:2048',
            'banner' => 'required|file|mimes:jpeg,png,jpg|max:2048',
        ]);

       
        $deptFileFields = ['logo', 'banner'];
        

        foreach ($deptFileFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
        
                // Get original name and sanitize it
                $originalName = $file->getClientOriginalName();
                $cleanName = preg_replace('/\s+/', '_', $originalName); // Replace spaces with underscores
                $cleanName = preg_replace('/[^A-Za-z0-9_\-\.]/', '', $cleanName); // Remove unwanted characters
        
                // Prepend timestamp for uniqueness
                $fileName = time() . '_' . $cleanName;
        
                $deptFoldername = $formData['shortname'];

                $uploadPath = public_path("assets/departments/{$deptFoldername}");
        
                // Create directory if it doesn't exist
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
        
                // Move file to desired location
                $file->move($uploadPath, $fileName);
        
                // Save relative path
                $formData[$field] = "assets/departments/{$deptFoldername}/$fileName";
            }
        }

        try {
            $department = Department::create($formData);
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                // Duplicate entry
                return back()->withErrors(['error' => 'The department already exists.'])->withInput();
            }

            return back()->withErrors(['error' => 'Failed to create department: ' . $e->getMessage()]);
        }
    
        

        return redirect()->route('departments')->with('success', 'Department Created Successfully');
    } 

    public function edit(Department $department){

        
        return view('admin.department.edit',[
            'department' => $department
        ]);
    }

    public function update(Request $request, Department $department){

        $formData = $request->validate([
            'fullname' => 'required',
            'shortname'          => [
                'required',
                Rule::unique('departments')->ignore($department->id),
            ],
            'deptCode' => 'required',
            'logo' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
            'banner' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
        ]);

     

        $oldShortname = $department->shortname;
        $newShortname = $formData['shortname'];


        $deptFileFields = ['logo', 'banner'];

        foreach ($deptFileFields as $field) {
            if ($request->hasFile($field)) {
                // Delete old file
                if (!empty($department->$field)) {
                    $oldFilePath = public_path($department->$field);
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }

                $file = $request->file($field);
                $originalName = $file->getClientOriginalName();
                $cleanName = preg_replace('/\s+/', '_', $originalName);
                $cleanName = preg_replace('/[^A-Za-z0-9_\-\.]/', '', $cleanName);
                $fileName = time() . '_' . $cleanName;

                $uploadPath = public_path("assets/departments/{$newShortname}");

                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                $file->move($uploadPath, $fileName);

                // Save new relative path
                $formData[$field] = "assets/departments/{$newShortname}/{$fileName}";
            } else {
                // Keep the old file path if no new file uploaded
                $formData[$field] = $department->$field;
            }
        }

        try {
            $department->update($formData);
        } catch (QueryException $e) {
            return back()->withErrors(['error' => 'Failed to update student: ' . $e->getMessage()]);
        }

        return redirect()->route('departments')->with('success', 'Department updated successfully.');
    }

     private function deleteFolder($folderPath){
        if (file_exists($folderPath)) {
            $files = array_diff(scandir($folderPath), ['.', '..']);
            foreach ($files as $file) {
                $filePath = "$folderPath/$file";
                is_dir($filePath) ? $this->deleteFolder($filePath) : unlink($filePath);
            }
            rmdir($folderPath);
        }
    }

    public function destroy(Department $department)
    {
        try {
            // Define the folder name from the department shortname
            $deptFoldername = $department->shortname;
            $folderPath = public_path("assets/departments/{$deptFoldername}");

            // Remove entire folder if it exists
            if (File::exists($folderPath)) {
                File::deleteDirectory($folderPath);
            }

            // Delete the department record from the database
            $department->delete();

        } catch (QueryException $e) {
            return back()->withErrors(['error' => 'Failed to delete department: ' . $e->getMessage()]);
        }

        return redirect()->route('departments')->with('success', 'Department deleted successfully.');
    }

}
