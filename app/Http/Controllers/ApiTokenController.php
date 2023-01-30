<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ApiTokenController extends Controller
{
    //AUTH
    public function signup(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'confirm_password' => 'required'
        ]);

        //Si les mots de passe ne correspondent pas
        if($request->password !== $request->confirm_password){
            return response()->json(['errors' => "Les mots de passe ne correspondent pas"], 409);
        }

        //Check si l'user existe
        $exists = User::where('username', $request->username)->exists();
    
        //Si l'user existe 409
        if($exists){
            return response()->json(['errors' => "Pseudo déjà utilisé"], 409);
        }
    
        //Sinon on le créé
        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password)
        ]);

        //TOKEN CREATION
        $token = $user->createToken($request->username)->plainTextToken;

        //RETURN 201 OK
        return response()->json([
            'token' => $token,
            'user' => $user
        ], 201);
    }
}
