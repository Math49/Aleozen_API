<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InterventionReservation;

class InterventionReservationController extends Controller
{
    /**
     * URL: /api/intervention-reservations
     * Method: GET
     * Description: Get all intervention reservations
     * Accepts: JSON
     */
    public function index(Request $request)
    {
        $interventionReservations = InterventionReservation::all();

        if ($request->accepts('application/json')) {
            return response()->json($interventionReservations, 200);
        } else {
            return response()->json(['error' => 'Unsupported format'], 406);
        }
    }

    /**
     * URL: /api/intervention-reservations
     * Method: POST
     * Description: Create a new intervention reservation
     * Accepts: JSON
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'intervention_date' => 'required|date',
            'type' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'status' => 'required|string|max:255',
        ]);

        $interventionReservation = InterventionReservation::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'intervention_date' => $request->intervention_date,
            'type' => $request->type,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return response()->json($interventionReservation, 201);
    }

    /**
     * URL: /api/intervention-reservations/{id}
     * Method: GET
     * Description: Get a specific intervention reservation by ID
     * Accepts: JSON
     */
    public function show(Request $request, $id)
    {
        $interventionReservation = InterventionReservation::findOrFail($id);

        if ($request->accepts('application/json')) {
            return response()->json($interventionReservation, 200);
        } else {
            return response()->json(['error' => 'Unsupported format'], 406);
        }
    }

    /**
     * URL: /api/intervention-reservations/{id}
     * Method: PUT
     * Description: Update a specific intervention reservation by ID
     * Accepts: JSON
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'intervention_date' => 'required|date',
            'type' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'status' => 'required|string|max:255',
        ]);

        $interventionReservation = InterventionReservation::findOrFail($id);
        $interventionReservation->update([
            'first_name' => $request->first_name ?? $interventionReservation->first_name,
            'last_name' => $request->last_name ?? $interventionReservation->last_name,
            'email' => $request->email ?? $interventionReservation->email,
            'phone' => $request->phone ?? $interventionReservation->phone,
            'intervention_date' => $request->intervention_date ?? $interventionReservation->intervention_date,
            'type' => $request->type ?? $interventionReservation->type,
            'description' => $request->description ?? $interventionReservation->description,
            'status' => $request->status ?? $interventionReservation->status,
        ]);

        return response()->json($interventionReservation, 200);
    }

    /**
     * URL: /api/intervention-reservations
     * Method: DELETE
     * Description: Delete a specific intervention reservation by ID
     * Accepts: JSON
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'reservation_id' => 'required|integer|exists:intervention_reservations,reservation_id',
        ]);

        $interventionReservation = InterventionReservation::findOrFail($request->reservation_id);
        $interventionReservation->delete();

        return response()->json(['message' => 'Réservation d\'intervention supprimée avec succès'], 200);
    }
}
