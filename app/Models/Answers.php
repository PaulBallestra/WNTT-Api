<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Questions;

class Answers extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'answer_id',
        'question_id',
        'nb_vote'
    ];

    public function questions()
    {
        return $this->belongsTo(Questions::Class);
    }
}
