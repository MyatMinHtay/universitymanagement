<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'gender',
        'date_of_birth',
        'position',
        'image',
        'phone_number',
        'email',
        'department_id',
    ];

    /**
     * Get the department that the teacher belongs to.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
