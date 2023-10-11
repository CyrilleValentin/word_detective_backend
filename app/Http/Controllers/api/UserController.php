<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    /**
     * Create User
     * @param Request request
     *  @return User
     */
   
     public function createUser(Request $request)
     {
     try {
            $validateUser=Validator::make($request->all(),
            [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|unique:users',
                'password' => ['required', 'confirmed'],
            ]);
            if ($validateUser->fails()) {
                return response()->json([
                    'statut'=>'false',
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                    ], 401);
            }
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            return response()->json([
                'statut'=>'true',
                'message' => 'Utilisateur crée avec succès',
                'token' => $user->createToken("API TOKEN")->plainTextToken
                ], 200);
         }catch(\Trowable $th){
            return response()->json([
                'statut'=>'false',
                'message' => $th->getMessage(),
                ], 500);
         }
     }  


    /**
     * login User
     * @param Request request
     *  @return User
     */
   
     public function loginUser(Request $request)
{
    try {
        $validateUser = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validateUser->fails()) {
            return response()->json([
                'statut' => 'false',
                'message' => 'Validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'statut' => 'false',
                'message' => 'Email ou mot de passe ne correspondent pas aux enregistrements',
            ], 401);
        }

        $user = User::where('email', $request->email)->first();
        return response()->json([
            'statut' => 'true',
            'message' => 'Connecté avec succès',
            'token' => $user->createToken("API TOKEN")->plainTextToken
        ], 200);
    } catch (\Throwable $th) {
        return response()->json([
            'statut' => 'false',
            'message' => $th->getMessage(),
        ], 500);
    }
}
public function logout(Request $request){
    $accessToken=$request->bearerToken();
    $token=PersonalAccessToken::findToken($accessToken);   
    $token->delete(); 
    return response()->json([
        'statut' => 'true',
        'message' => 'Déconnecté avec succès']);  
}
public function profile(Request $request){
    return response()->json([
        'statut' => 'true',
        'message' => 'Profil de l\'utilisateur',
        'data'=> $request->user()
    ]);  
}

}
