<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questions extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'answers',
        'user_id'
    ];

    protected $casts = [
        'answers' => 'array'
    ];
}
