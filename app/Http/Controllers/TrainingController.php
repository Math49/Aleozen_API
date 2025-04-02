<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use App\Models\Training;

class TrainingController extends Controller
{
    /**
     * URL: /api/trainings
     * Method: GET
     * Description: Get all trainings
     * Accepts: JSON
     */
    public function index(Request $request)
    {
        try{
            $trainings = Training::all();
            
            if ($request->accepts('application/json')) {
                return response()->json($trainings, 200);
            } else {
                return response()->json(['error' => 'Unsupported format'], 406);
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la récupération des formations',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * URL: /api/trainings
     * Method: POST
     * Description: Create a new training
     * Accepts: JSON
     */
    public function store(Request $request)
    {
        try{
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'location' => 'required|string|max:255',
                'start_date' => 'required|date',
                'price' => 'required|numeric|min:0',
                'status' => 'required|string|in:pending,completed,cancelled',
            ]);

            $training = Training::create([
                'title' => $request->title,
                'description' => $request->description,
                'location' => $request->location,
                'start_date' => $request->start_date,
                'price' => $request->price,
                'status' => $request->status,
            ]);

            return response()->json($training, 201);
        }
        catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la création de la formation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * URL: /api/trainings/{id}
     * Method: GET
     * Description: Get a specific training
     * Accepts: JSON
     */
    public function show(Request $request, $id)
    {
        try{
            $training = Training::findOrFail($id);

            if ($request->accepts('application/json')) {
                return response()->json($training, 200);
            } else {
                return response()->json(['error' => 'Unsupported format'], 406);
            }
        }
        catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la récupération de la formation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * URL: /api/trainings/{id}
     * Method: PUT
     * Description: Update a specific training
     * Accepts: JSON
     */
    public function update(Request $request, $id)
    {
        try{
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'location' => 'required|string|max:255',
                'start_date' => 'required|date',
                'price' => 'required|numeric|min:0',
                'status' => 'required|string|in:pending,completed,cancelled',
            ]);

            $training = Training::findOrFail($id);
            $training->update([
                'title' => $request->title,
                'description' => $request->description,
                'location' => $request->location,
                'start_date' => $request->start_date,
                'price' => $request->price,
                'status' => $request->status,
            ]);

            return response()->json($training, 200);
        }
        catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la mise à jour de la formation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * URL: /api/trainings
     * Method: DELETE
     * Description: Delete a specific training
     * Accepts: JSON
     */
    public function destroy(Request $request)
    {
        try{
            $request->validate([
                'id' => 'required|integer|exists:trainings,training_id',
            ]);

            $training = Training::findOrFail($request->id);
            $training->delete();

            return response()->json(['message' => 'Formation supprimée avec succès'], 200);
        }
        catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la suppression de la formation',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
