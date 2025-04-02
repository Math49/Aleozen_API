<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Exception;

class AuthController extends Controller
{
    // POST /register
    public function register(Request $request)
    {
        try{
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
            ]);
            
            return response()->json([
                'message' => 'Utilisateur créé avec succès',
                'user' => $user,
                'token' => $user->createToken('auth-token')->plainTextToken
            ], 201);
            
        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la création de l\'utilisateur',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // POST /login
    public function login(Request $request){
        try{

            $request->validate([
                'email' => 'required|email|max:255',
                'password' => 'required|string|min:8',
            ]);
        
            $user = User::where('email', $request->email)->first();
        
            if (!$user || !Hash::check($request->Password, $user->Password)) {
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

        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la connexion',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // POST /logout
    public function logout(Request $request){
        try{
            $request->user()->tokens()->delete();
            return response()->json(['message' => 'Déconnexion réussie']);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Erreur lors de la déconnexion',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}

