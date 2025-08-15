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
            $trainings = Training::all();
            
            if ($request->accepts('application/json')) {
                return response()->json($trainings, 200);
            } else {
                return response()->json(['error' => 'Unsupported format'], 406);
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
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'location' => 'required|string|max:255',
                'start_date' => 'required|date',
                'type' => 'required|string|in:taichi,qigong',
                'price' => 'nullable|numeric|min:0',
                'status' => 'required|string|in:draft,published,archived',
            ]);

            $training = Training::create([
                'title' => $request->title,
                'description' => $request->description,
                'location' => $request->location,
                'start_date' => $request->start_date,
                'type' => $request->type,
                'price' => $request->price,
                'status' => $request->status,
            ]);

            return response()->json($training, 201);
    }

    /**
     * URL: /api/trainings/{id}
     * Method: GET
     * Description: Get a specific training
     * Accepts: JSON
     */
    public function show(Request $request, $id)
    {
            $training = Training::findOrFail($id);

            if ($request->accepts('application/json')) {
                return response()->json($training, 200);
            } else {
                return response()->json(['error' => 'Unsupported format'], 406);
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
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'location' => 'required|string|max:255',
                'start_date' => 'required|date',
                'type' => 'required|string|in:taichi,qigong',
                'price' => 'nullable|numeric|min:0',
                'status' => 'required|string|in:draft,published,archived',
            ]);

            $training = Training::findOrFail($id);
            $training->update([
                'title' => $request->title ?? $training->title,
                'description' => $request->description ?? $training->description,
                'location' => $request->location ?? $training->location,
                'start_date' => $request->start_date ?? $training->start_date,
                'type' => $request->type ?? $training->type,
                'price' => $request->price ?? $training->price,
                'status' => $request->status ?? $training->status,
            ]);

            return response()->json($training, 200);
    }

    /**
     * URL: /api/trainings
     * Method: DELETE
     * Description: Delete a specific training
     * Accepts: JSON
     */
    public function destroy(Request $request)
    {
            $request->validate([
                'training_id' => 'required|integer|exists:trainings,training_id',
            ]);

            $training = Training::findOrFail($request->training_id);
            $training->delete();

            return response()->json(['message' => 'Formation supprimée avec succès'], 200);
    }
}
