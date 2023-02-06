<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Vote;

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

        //Check de la category

        $question = Question::create([
            'title' => $request->title,
            'answers' => $request->answers,
            'user_id' => $request->user_id,
            'category_id' => $request->category_id
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
            'category_id' => $question->category_id,
            'user' => [
                'id' => $request->user()->id,
                'created_at' => $request->user()->created_at,
                'updated_at' => $request->user()->updated_at,
                'username' => $request->user()->username
            ]
        ], 201);

    }

    //Delete Question
    public function delete(Request $request, $id)
    {
        //401 SANCTUM
        
        //Check de la question (404)
        if(!Question::where('id', $id)->exists()){
            return response()->json([
                'errors' => "La question n'existe pas."
            ], 404);
        }

        //Recuperation de la tache
        $question = Question::where('id', $id)->first();

        //Verif si c'est la question de l'user (403)
        if($question->user_id !== $request->user()->id){
            return response()->json([
                'errors' => "Accès à la question non autorisé."
            ], 403);
        }

        //Delete des votes reliés a la question
        Vote::where('question_id', $id)->delete();

        //Delete de la question
        Question::where('id', $id)->first()->delete();

        return response()->json([
            'id' => $question->id,
            'created_at' => $question->created_at,
            'updated_at' => $question->updated_at,
            'title' => $question->title,
            'answers' => $question->answers,
        ], 200); 

    }

    //Show All Questions
    public function showAll()
    {
        //401 UNAUTHENTICATED GÉRÉ PAR SANCTUM

        $infos = array();
        $i = 0;

        $questions = Question::orderBy('created_at', 'DESC')->get();

        foreach($questions as $q){
            $infos[$i] = [
                'question' => $q,
                'nb_votes' => Vote::where('question_id', $q->id)->get()->count()
            ];
            $i++;
        }

        return response()->json([
            'infos' => $infos
        ], 201);
    }
}
