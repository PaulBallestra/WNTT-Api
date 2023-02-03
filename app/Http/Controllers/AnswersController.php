<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Answer;
use App\Models\Question;
use App\Models\Vote;

class AnswersController extends Controller
{
    public function vote(Request $request, $question, $answer)
    {
        //401 SANCTUM

        //Check si la reponse et la question existent
        if(!Question::where('id', $question)->exists() || !Answer::where('answer_id', $answer)->exists()){
            return response()->json([
                'errors' => "Le vote n'a pas été pris en compte."
            ], 404);
        }

        //Recuperation de la reponse
        $fanswer = Answer::where('question_id', $question)->where('answer_id', $answer)->first();

        //Check si il avait déjà voté, si oui on delete l'ancien vote
        if(Vote::where('user_id', $request->user()->id)->where('question_id', $question)->exists()){
            Vote::where('user_id', $request->user()->id)->where('question_id', $question)->first()->delete();
        }

        //Ajout du vote
        $fanswer->vote()->create([
            'user_id' => $request->user()->id,
            'question_id' => $question,
            'answer_id' => $answer
        ]);

        return response()->json([
            'answer' => $fanswer
        ], 201);
    }
}
