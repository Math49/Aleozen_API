<?php

namespace App\Http\Controllers;

use App\Models\TrainingContent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;

class TrainingContentController extends Controller
{
    /**
     * URL:
     * Method: GET
     * Description:
     * Accepts: JSON
     */
    public function index(Request $request)
    {
        try{
            $trainingContents = TrainingContent::all()->load('training.trainingReservations');
            
            if ($request->accepts('application/json')) {
                return response()->json($trainingContents, 200);
            } else {
                return response()->json(['error' => 'Unsupported format'], 406);
            }
        }
        catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la récupération des contenus de formation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * URL:
     * Method: POST
     * Description:
     * Accepts: JSON
     */
    public function store(Request $request)
    {
        try{
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'files' => 'required|file|mimes:pdf,doc,docx|max:2048',
                'training_id' => 'required|exists:trainings,id',
            ]);

            $trainingContent = TrainingContent::create([
                'name' => $request->name,
                'description' => $request->description,
                'files' => $request->file('files')->store('training_contents'),
                'training_id' => $request->training_id,
            ]);

            return response()->json($trainingContent, 201);
        }
        catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la création du contenu de formation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * URL:
     * Method: GET
     * Description:
     * Accepts: JSON
     */
    public function show(Request $request, $id)
    {
        try{
            $trainingContent = TrainingContent::findOrFail($id)->load('training.trainingReservations');
            
            if ($request->accepts('application/json')) {
                return response()->json($trainingContent, 200);
            } else {
                return response()->json(['error' => 'Unsupported format'], 406);
            }
        }
        catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la récupération du contenu de formation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * URL:
     * Method: PUT
     * Description:
     * Accepts: JSON
     */
    public function update(Request $request, $id)
    {
        try{
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'files' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
                'training_id' => 'required|exists:trainings,id',
            ]);

            $trainingContent = TrainingContent::findOrFail($id);
            $trainingContent->update([
                'name' => $request->name ?? $trainingContent->name,
                'description' => $request->description ?? $trainingContent->description,
                'files' => $request->file('files') ? $request->file('files')->store('training_contents') : $trainingContent->files,
                'training_id' => $request->training_id ?? $trainingContent->training_id,
            ]);

            return response()->json($trainingContent, 200);
        }
        catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la mise à jour du contenu de formation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * URL:
     * Method: DELETE
     * Description:
     * Accepts: JSON
     */
    public function destroy(Request $request)
    {
        try{
            $request->validate([
                'id' => 'required|exists:training_contents,id',
            ]);

            $trainingContent = TrainingContent::findOrFail($request->id);
            $trainingContent->delete();

            return response()->json(['message' => 'Training content deleted successfully'], 200);
        }
        catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la suppression du contenu de formation',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
