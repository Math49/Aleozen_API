<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
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
        try{

        }
        catch (Exception $e) {
            
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
        try{

        }
        catch (Exception $e) {
            
        }
    }

    /**
     * URL: /api/users/{id}
     * Method: GET
     * Description: Get a specific user
     * Accepts: JSON
     */
    public function show(Request $request, $id)
    {
        try{

        }
        catch (Exception $e) {
            
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
        try{

        }
        catch (Exception $e) {
            
        }
    }

    /**
     * URL: /api/users/
     * Method: DELETE
     * Description: Delete a specific user
     * Accepts: JSON
     */
    public function destroy(Request $request)
    {
        try{

        }
        catch (Exception $e) {
            
        }
    }
}
