<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'answers',
        'user_id',
        'category_id'
    ];

    protected $casts = [
        'answers' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::Class);
    }

    public function answers()
    {
        return $this->hasMany(Answers::Class);
    }
}
