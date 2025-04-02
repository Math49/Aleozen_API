<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use App\Models\TrainingReservation;

class TrainingReservationController extends Controller
{
    /**
     * URL: /api/training-reservations
     * Method: GET
     * Description: Get all training reservations
     * Accepts: JSON
     */
    public function index(Request $request)
    {
        try{
            $trainingReservations = TrainingReservation::all()->load('training.trainingContents', 'user');
            
            if ($request->accepts('application/json')) {
                return response()->json($trainingReservations, 200);
            } else {
                return response()->json(['error' => 'Unsupported format'], 406);
            }
        }
        catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la récupération des réservations de formation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * URL: /api/training-reservations
     * Method: POST
     * Description: Create a new training reservation
     * Accepts: JSON
     */
    public function store(Request $request)
    {
        try{
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:255',
                'application_file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
                'interview_date' => 'nullable|date',
                'status' => 'required|string|in:pending,confirmed,cancelled',
                'pay' => 'nullable|boolean',
                'training_id' => 'required|exists:trainings,training_id',
                'user_id' => 'nullable|exists:users,id',
            ]);

            $trainingReservation = TrainingReservation::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'application_file' => $request->file('application_file') ? $request->file('application_file')->store('applications') : null,
                'interview_date' => $request->interview_date,
                'status' => $request->status,
                'pay' => $request->pay,
                'training_id' => $request->training_id,
                'user_id' => $request->user_id,
            ]);

            return response()->json($trainingReservation, 201);
        }
        catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la création de la réservation de formation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * URL: /api/training-reservations/{id}
     * Method: GET
     * Description: Get a specific training reservation
     * Accepts: JSON
     */
    public function show(Request $request, $id)
    {
        try{
            $trainingReservation = TrainingReservation::findOrFail($id)->load('training.trainingContents', 'user');
            
            if ($request->accepts('application/json')) {
                return response()->json($trainingReservation, 200);
            } else {
                return response()->json(['error' => 'Unsupported format'], 406);
            }
        }
        catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la récupération de la réservation de formation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * URL: /api/training-reservations/{id}
     * Method: PUT
     * Description: Update a specific training reservation
     * Accepts: JSON
     */
    public function update(Request $request, $id)
    {
        try{
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:255',
                'application_file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
                'interview_date' => 'nullable|date',
                'status' => 'required|string|in:pending,confirmed,cancelled',
                'pay' => 'nullable|boolean',
                'training_id' => 'required|exists:trainings,training_id',
                'user_id' => 'nullable|exists:users,id',
            ]);

            $trainingReservation = TrainingReservation::findOrFail($id);
            $trainingReservation->update([
                'first_name' => $request->first_name ?? $trainingReservation->first_name,
                'last_name' => $request->last_name ?? $trainingReservation->last_name,
                'email' => $request->email ?? $trainingReservation->email,
                'phone' => $request->phone ?? $trainingReservation->phone,
                'application_file' => $request->file('application_file') ? $request->file('application_file')->store('applications') : null,
                'interview_date' => $request->interview_date ?? $trainingReservation->interview_date,
                'status' => $request->status ?? $trainingReservation->status,
                'pay' => $request->pay ?? $trainingReservation->pay,
            ]);

            return response()->json($trainingReservation, 200);
        }
        catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la mise à jour de la réservation de formation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * URL: /api/training-reservations/
     * Method: DELETE
     * Description: Delete a specific training reservation
     * Accepts: JSON
     */
    public function destroy(Request $request)
    {
        try{
            $request->validate([
                'id' => 'required|integer|exists:training_reservations,reservation_id',
            ]);

            $trainingReservation = TrainingReservation::findOrFail($request->id);
            $trainingReservation->delete();

            return response()->json(['message' => 'Réservation de formation supprimée avec succès'], 200);
        }
        catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la suppression de la réservation de formation',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
