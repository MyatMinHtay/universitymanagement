<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'year', 'seat_number', 'image', 'phone_number', 'email', 'department_id'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
