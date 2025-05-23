<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Chapter;
use App\Models\Mtc;
use App\Models\Order;
use App\Models\SaveWebtoon;
use App\Models\StarPackage;
use App\Models\Subscribe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function showabout(){
        return view('about');
    }
    
    public function showhelp(){
        return view('help');
    }



    public function showAcademics() {
        return view('academics');
    }

    public function showAdmissions() {
        return view('admissions');
    }

    public function showAlumni() {
        return view('alumni');
    }

    public function showCampusFacilities() {
        return view('campus-facilities');
    }

    public function showContact() {
        return view('contact');
    }

    public function showEventDetails() {
        return view('event-details');
    }

    public function showEvents() {
        return view('events');
    }

    public function showFacultyStaff() {
        return view('faculty-staff');
    }

    public function showNewsDetails() {
        return view('news-details');
    }

    public function showNews() {
        return view('news');
    }

    public function showPrivacy() {
        return view('privacy');
    }

    public function showStudentsLife() {
        return view('students-life');
    }

    public function showTermsOfService() {
        return view('terms-of-service');
    }


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

   
}
