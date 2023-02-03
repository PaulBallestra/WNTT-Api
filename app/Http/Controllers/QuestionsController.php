<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Question;
use App\Models\Answer;

class QuestionsController extends Controller
{
    //Create Question
    public function create(Request $request)
    {
        //401 SANCTUM

        //422
        $request->validate([
            'title' => 'required',
            'answers' => 'required|array|min:2|max:5',
            'user_id' => 'required',
        ]);

        $question = Question::create([
            'title' => $request->title,
            'answers' => $request->answers,
            'user_id' => $request->user_id,
        ]);

        $answers = array();
        
        //Ajout des answers
        for($i = 0; $i < count($request->answers); $i++){
            $answers[$i] = Answer::create([
                'title' => $request->answers[$i],
                'answer_id' => $i,
                'question_id' => $question->id,
            ]);
        }


        //STATUS 201, QUESTION CREATED
        return response()->json([
            'id' => $question->id,
            'created_at' => $question->created_at,
            'updated_at' => $question->updated_at,
            'title' => $question->title,
            'answers' => $answers,
            'user' => [
                'id' => $request->user()->id,
                'created_at' => $request->user()->created_at,
                'updated_at' => $request->user()->updated_at,
                'username' => $request->user()->username
            ]
        ], 201);

    }

    //Show All Questions
    public function showAll()
    {
        //401 UNAUTHENTICATED GÉRÉ PAR SANCTUM
        $questions = Question::orderBy('created_at', 'DESC')->get();

        return response()->json([
            'questions' => $questions
        ], 201);
    }
}
