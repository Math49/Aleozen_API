<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * URL: /api/users
     * Method: GET
     * Description: Get all users
     * Accepts: JSON
     */
    public function index(Request $request)
    {
        $users = User::all();

        foreach ($users as $user) {
            $user->password = null;
        }

        if ($request->accepts('application/json')) {
            return response()->json($users, 200);
        } else {
            return response()->json(['error' => 'Unsupported format'], 406);
        }
    }

    /**
     * URL: /api/users
     * Method: POST
     * Description: Create a new user
     * Accepts: JSON
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'required|string|max:10',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'role' => $request->role ?? 'user',
        ]);

        $user->password = null;

        return response()->json($user, 201);
    }

    /**
     * URL: /api/users/{id}
     * Method: GET
     * Description: Get a specific user
     * Accepts: JSON
     */
    public function show(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->password = null;

        if ($request->accepts('application/json')) {
            return response()->json($user, 200);
        } else {
            return response()->json(['error' => 'Unsupported format'], 406);
        }
    }

    /**
     * URL: /api/users/{id}
     * Method: PUT
     * Description: Update a specific user
     * Accepts: JSON
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id . ',user_id',
            'phone' => 'required|string|max:10',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'nullable|string|in:user,admin',
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
            'role' => $request->role ?? $user->role,
        ]);
        $user->password = null;

        return response()->json($user, 200);
    }

    /**
     * URL: /api/users/
     * Method: DELETE
     * Description: Delete a specific user
     * Accepts: JSON
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}
