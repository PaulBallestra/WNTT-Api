<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\User;
use App\Http\Models\Answers;

class AnswersController extends Controller
{
    //
    public function create(Request $request)
    {
        $request->validate([
            'answers' => 'required|array|min:2|max:5'
        ]);
    }
}
