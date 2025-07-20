<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name', 'gender', 'date_of_birth', 'year', 'roll_number', 'image', 'phone_number', 'email', 'department_id'];

    public function department()
    {
        //One to Many Relationship
        return $this->belongsTo(Department::class);
    }
}
