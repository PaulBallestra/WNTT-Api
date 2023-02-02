<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Answer;

class AnswersController extends Controller
{
    public function vote(Request $request, $question, $answer)
    {
        $fanswer = Answer::where('question_id', $question)->where('answer_id', $answer)->first();

        return response()->json([
            'answer' => $fanswer
        ], 201);
    }
}
