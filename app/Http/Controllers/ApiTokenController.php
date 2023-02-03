<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Vote;

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

    public function login(Request $request)
    {
        //Validation champs 422
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('username', $request->username)->first();

        //Si l'user n'existe pas 401
        if(!$user || !Hash::check($request->password, $user->password)){
            return response()->json(['errors' => "Identifiants inconnus ou erronés"], 401);
        }

        //Suppresion de l'ancien token
        $user->tokens()->where('tokenable_id', $user->id)->delete();

        //Création du nouveau token
        $token = $user->createToken($request->username)->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user
        ], 200);

    }

    //DESTROY DE L'USER
    public function destroy(Request $request)
    {
        //401 SANCTUM UNAUTHENTICATED

        //400 si l'user n'existe pas
        if(!User::where('id', $request->user()->id)->exists()){
            return response()->json([
                'errors' => "L'user n'existe pas.",
            ], 400);
        }

        //Suppresion des votes de l'user
        Vote::where('user_id', $request->user()->id)->delete();
        
        //Suppresion du token
        $request->user()->tokens()->delete();

        //Suppresion de l'user
        User::where('id', $request->user()->id)->first()->delete();

        return response()->json([
            'log' => "User supprimé.",
        ], 200);
    }

}
