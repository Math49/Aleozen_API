<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * URL: /api/register
     * Method: POST
     * Description: Register a new user
     * Accepts: JSON
     */
    public function register(Request $request)
    {
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email',
                'phone' => 'required|string|max:255',
                'password' => 'required|string|min:8|confirmed',
            ]);
            
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => 'user',
            ]);
            
            return response()->json([
                'message' => 'Utilisateur créé avec succès',
                'user' => $user,
                'token' => $user->createToken('auth-token')->plainTextToken
            ], 201);
            
    }

    /**
     * URL: /api/login
     * Method: POST
     * Description: Login a user
     * Accepts: JSON
     */
    public function login(Request $request){

            $request->validate([
                'email' => 'required|email|max:255',
                'password' => 'required|string|min:8',
            ]);
        
            $user = User::where('email', $request->email)->first();
        
            if (!$user || !Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'error' => ['Les informations de connexion sont incorrectes.'],
                ]);
            }
        
            return response()->json([
                'message' => 'Connexion réussie',
                'user' => [
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'role' => $user->role,
                ],
                'token' => $user->createToken('auth-token')->plainTextToken
            ]);

        
    }

    /**
     * URL: /api/logout
     * Method: POST
     * Description: Logout a user
     * Accepts: JSON
     */
    public function logout(Request $request){
            $request->user()->tokens()->delete();
            return response()->json(['message' => 'Déconnexion réussie']);
       
    }

}

