<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'answer_id',
        'question_id',
    ];

    public function questions()
    {
        return $this->belongsTo(Question::Class);
    }

    public function vote()
    {
        return $this->morphMany(Vote::Class, 'votable');
    }

    public function hasVoted()
    {
        return $this->vote->where('user_id', Auth::user()->id)->count();
    }

}
