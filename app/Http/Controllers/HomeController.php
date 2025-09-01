<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Models\Department;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class HomeController extends Controller
{

    /**
     * Display user profile page with associated MTC data
     */
    public function showprofile(User $user){

          
        $mtcs = Mtc::all();
          
            
            return view('auth.profile',[
                
                'user' => $user,
                'mtcs' => $mtcs
            ]);
    }

    public function showeditprofile(){
        return view('auth.editprofile');
    }

    /**
     * Global search across all university entities (departments, teachers, students, faculty)
     * Supports multiple category filters and adjusts result limits dynamically
     * Returns formatted data for AJAX responses with proper URLs and badges
     */
    public function homeSearch(Request $request)
    {
        $query = $request->get('query');
        $filterParam = $request->get('filter', 'all'); // default to 'all'
        
        // Convert comma-separated filters to array
        $filters = array_map('trim', explode(',', $filterParam));
        
        // If filters contain 'all', search all categories
        if (in_array('all', $filters)) {
            $filters = ['all'];
        }
        
        if (empty($query)) {
            return response()->json([
                'results' => [],
                'filters' => $filters
            ]);
        }

        $results = collect();
        
        // Adjust limits based on number of filters selected
        $isMultipleFilters = count($filters) > 1;
        $limitPerCategory = $isMultipleFilters ? 4 : 12; // Reduce limit per category when multiple filters
        $limitForAll = $isMultipleFilters ? 3 : 3; // Limit when searching all categories

        // Search based on filters
        if (in_array('all', $filters) || in_array('departments', $filters)) {
            $limit = in_array('all', $filters) ? $limitForAll : $limitPerCategory;
            
            $departments = Department::where('fullname', 'LIKE', "%{$query}%")
                ->orWhere('shortname', 'LIKE', "%{$query}%")
                ->orWhere('description', 'LIKE', "%{$query}%")
                ->orWhere('deptCode', 'LIKE', "%{$query}%")
                ->get()
                ->map(function ($department) {
                    return [
                        'id' => $department->id,
                        'name' => $department->fullname,
                        'shortname' => $department->shortname,
                        'description' => $department->description,
                        'logo' => $department->logo ? asset($department->logo) : asset('assets/img/default-department.png'),
                        'url' => route('user.departments.show', $department->id),
                        'type' => 'department',
                        'badge' => 'Department',
                        'badge_color' => 'primary'
                    ];
                });
            $results = $results->merge($departments);
        }

        if (in_array('all', $filters) || in_array('teachers', $filters)) {
            $limit = in_array('all', $filters) ? $limitForAll : $limitPerCategory;
            
            $teachers = Teacher::with('department')
                ->where('name', 'LIKE', "%{$query}%")
                ->orWhere('position', 'LIKE', "%{$query}%")
                ->orWhere('email', 'LIKE', "%{$query}%")
                ->orWhereHas('department', function ($q) use ($query) {
                    $q->where('fullname', 'LIKE', "%{$query}%")
                      ->orWhere('shortname', 'LIKE', "%{$query}%");
                })
                ->get()
                ->map(function ($teacher) {
                    return [
                        'id' => $teacher->id,
                        'name' => $teacher->name,
                        'position' => $teacher->position,
                        'department' => $teacher->department->fullname ?? 'N/A',
                        'image' => $teacher->image ? asset($teacher->image) : asset('assets/img/default-teacher.png'),
                        'url' => route('teachers.usershow', $teacher->id),
                        'type' => 'teacher',
                        'badge' => 'Teacher',
                        'badge_color' => 'success'
                    ];
                });
            $results = $results->merge($teachers);
        }

        if (in_array('all', $filters) || in_array('students', $filters)) {
            $limit = in_array('all', $filters) ? $limitForAll : $limitPerCategory;
            
            $students = Student::with('department')
                ->where('name', 'LIKE', "%{$query}%")
                ->orWhere('seat_number', 'LIKE', "%{$query}%")
                ->orWhere('year', 'LIKE', "%{$query}%")
                ->orWhere('email', 'LIKE', "%{$query}%")
                ->orWhereHas('department', function ($q) use ($query) {
                    $q->where('fullname', 'LIKE', "%{$query}%")
                      ->orWhere('shortname', 'LIKE', "%{$query}%");
                })
                ->get()
                ->map(function ($student) {
                    return [
                        'id' => $student->id,
                        'name' => $student->name,
                        'year' => $student->year,
                        'seat_number' => $student->seat_number,
                        'department' => $student->department->fullname ?? 'N/A',
                        'image' => $student->image ? asset($student->image) : asset('assets/img/default-student.png'),
                        'url' => route('students.usershow', $student->id),
                        'type' => 'student',
                        'badge' => 'Student',
                        'badge_color' => 'info'
                    ];
                });
            $results = $results->merge($students);
        }

        

        return response()->json([
            'results' => $results->values()->all(),
            'filters' => $filters
        ]);
    }

    /**
     * Export project documentation as PDF
     */
    public function exportProjectDocumentation()
    {
        $html = file_get_contents(public_path('../University_Management_System_Documentation.html'));
        
        $pdf = Pdf::loadHTML($html)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'Times-Roman',
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true
            ]);
        
        return $pdf->download('University_Management_System_Documentation.pdf');
    }
}
