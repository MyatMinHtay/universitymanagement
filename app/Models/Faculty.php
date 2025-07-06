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
        'department',
        'phone_number',
        'email',
    ];

    // Department is now stored as a string field, no relationship needed
} 