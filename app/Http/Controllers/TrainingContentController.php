<?php

namespace App\Http\Controllers;

use App\Models\TrainingContent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TrainingContentController extends Controller
{
    /**
     * URL: /api/training-contents
     * Method: GET
     * Description: Get all training contents
     * Accepts: JSON
     */
    public function index(Request $request)
    {
        $trainingContents = TrainingContent::all()->load('training.trainingReservations');

        if ($request->accepts('application/json')) {
            return response()->json($trainingContents, 200);
        } else {
            return response()->json(['error' => 'Unsupported format'], 406);
        }
    }

    /**
     * URL: /api/training-contents
     * Method: POST
     * Description: Create a new training content
     * Accepts: JSON
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'files' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'training_id' => 'required|exists:trainings,training_id',
        ]);

        $trainingContent = TrainingContent::create([
            'name' => $request->name,
            'description' => $request->description,
            'files' => $request->file('files')->store('training_contents'),
            'training_id' => $request->training_id,
        ]);

        return response()->json($trainingContent, 201);
    }

    /**
     * URL: /api/training-contents/{id}
     * Method: GET
     * Description: Get a specific training content by ID
     * Accepts: JSON
     */
    public function show(Request $request, $id)
    {
        $trainingContent = TrainingContent::findOrFail($id)->load('training.trainingReservations');

        if ($request->accepts('application/json')) {
            return response()->json($trainingContent, 200);
        } else {
            return response()->json(['error' => 'Unsupported format'], 406);
        }
    }

    /**
     * URL: /api/training-contents/{id}
     * Method: PUT
     * Description: Update a specific training content
     * Accepts: JSON
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'files' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $trainingContent = TrainingContent::findOrFail($id);
        $trainingContent->update([
            'name' => $request->name ?? $trainingContent->name,
            'description' => $request->description ?? $trainingContent->description,
            'files' => $request->file('files') ? $request->file('files')->store('training_contents') : $trainingContent->files,
        ]);

        return response()->json($trainingContent, 200);
    }

    /**
     * URL: /api/training-contents/
     * Method: DELETE
     * Description: Delete a specific training content
     * Accepts: JSON
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'content_id' => 'required|exists:training_contents,content_id',
        ]);

        $trainingContent = TrainingContent::findOrFail($request->content_id);
        $trainingContent->delete();

        return response()->json(['message' => 'Training content deleted successfully'], 200);
    }
}
