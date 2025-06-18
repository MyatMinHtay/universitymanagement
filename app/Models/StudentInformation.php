<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentInformation extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'studentName',
        'studentDOB',
        'sirb',
        'allInOne',
        'created_at',
        'updated_at'
    ];

     // One student can have many course enrollments
     public function courseStudents()
     {
         return $this->hasMany(Courses_Student::class, 'student_id');
     }
 
     // Many-to-many with Courses through course__students table
     public function courses()
     {
         return $this->belongsToMany(Courses::class, 'course__students', 'student_id', 'courses_id');
     }
}
