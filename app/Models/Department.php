<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'fullname',
        'shortname',
        'description',
        'deptCode',
        'logo',
        'banner',
    ];

    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }

    public function students()
    {
        //One to Many Relationship
        return $this->hasMany(Student::class);
    }

    public function faculty()
    {
        return $this->hasMany(Faculty::class);
    }

    /**
     * Get the name attribute (alias for fullname)
     */
    public function getNameAttribute()
    {
        return $this->fullname;
    }
}
