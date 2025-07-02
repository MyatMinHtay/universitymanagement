<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    use HasFactory;

    protected $table = 'faculty';

    protected $fillable = [
        'name',
        'position',
        'image',
        'department_id',
        'phone_number',
        'email',
    ];

    /**
     * Get the department that the faculty belongs to.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
} 