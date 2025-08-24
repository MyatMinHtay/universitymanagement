<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Order;
use App\Models\Purchase;
use App\Models\SystemRole;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Department;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function show(){
    
        if(Auth::check()){

            $users = User::count();
            $systemroles = SystemRole::all();
            

            
                
            if(auth()->user()->role->role == 'adminstrator' || auth()->user()->role->role == "admin" || auth()->user()->role->role == "author"){
                return view('admin.dashboard.dashboard',[
                    'systemroles' => $systemroles
                ]);
            }else if(auth()->user()->role->role == 'user'){
                return redirect()->back()->with('warning','access deined! You Are Not Admin');
            }
        }else{
            return redirect('/login')->with('warning','access deined! Only Admin Can Access This Page');
        }
    }

    public function showAnalytics(){
        // Total counts for overview cards
        $totalStudents = Student::count();
        $totalTeachers = Teacher::count();
        $totalDepartments = Department::count();
        $totalFaculty = Faculty::count();
        $totalUsers = User::count();
        
        // Students by department for pie chart
        $studentsByDepartment = Department::withCount('students')
            ->having('students_count', '>', 0)
            ->get()
            ->map(function($dept) {
                return [
                    'name' => $dept->shortname,
                    'count' => $dept->students_count
                ];
            });
        
        // Teachers by department for bar chart
        $teachersByDepartment = Department::withCount('teachers')
            ->having('teachers_count', '>', 0)
            ->get()
            ->map(function($dept) {
                return [
                    'name' => $dept->shortname,
                    'count' => $dept->teachers_count
                ];
            });
        
        // Students by gender for donut chart
        $studentsByGender = Student::select('gender', DB::raw('count(*) as count'))
            ->whereNotNull('gender')
            ->groupBy('gender')
            ->get()
            ->map(function($item) {
                return [
                    'gender' => ucfirst($item->gender),
                    'count' => $item->count
                ];
            });
        
        // Teachers by gender for donut chart
        $teachersByGender = Teacher::select('gender', DB::raw('count(*) as count'))
            ->whereNotNull('gender')
            ->groupBy('gender')
            ->get()
            ->map(function($item) {
                return [
                    'gender' => ucfirst($item->gender),
                    'count' => $item->count
                ];
            });
        
        // Students by year for line chart
        $studentsByYear = Student::select('year', DB::raw('count(*) as count'))
            ->whereNotNull('year')
            ->groupBy('year')
            ->orderBy('year')
            ->get()
            ->map(function($item) {
                return [
                    'year' => $item->year,
                    'count' => $item->count
                ];
            });
        
        // Recent registrations trend (last 6 months)
        $registrationTrend = collect();
        for($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->format('M Y');
            
            $studentsCount = Student::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            
            $teachersCount = Teacher::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            
            $registrationTrend->push([
                'month' => $monthName,
                'students' => $studentsCount,
                'teachers' => $teachersCount
            ]);
        }
        
        return view('admin.analytics.index', compact(
            'totalStudents',
            'totalTeachers', 
            'totalDepartments',
            'totalFaculty',
            'totalUsers',
            'studentsByDepartment',
            'teachersByDepartment',
            'studentsByGender',
            'teachersByGender',
            'studentsByYear',
            'registrationTrend'
        ));
    }
}
