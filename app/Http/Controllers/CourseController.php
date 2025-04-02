<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use Exception;

class CourseController extends Controller
{
    /**
     * URL: /api/courses
     * Method: GET
     * Description: Get all courses
     * Accepts: JSON
     */
    public function index(Request $request)
    {
        try {
            $courses = Course::all();

            if ($request->accepts('application/json')) {
                return response()->json($courses, 200);
            } else {
                return response()->json(['error' => 'Unsupported format'], 406);
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la récupération des cours',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * URL: /api/courses
     * Method: POST
     * Description: Create a new course
     * Accepts: JSON
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'location' => 'required|string|max:255',
                'start_date' => 'required|date',
                'status' => 'required|string|max:255',
            ]);

            $course = Course::create([
                'location' => $request->location,
                'start_date' => $request->start_date,
                'status' => $request->status,
            ]);

            return response()->json($course, 201);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la création du cours',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * URL: /api/courses/{id}
     * Method: GET
     * Description: Get a specific course by ID
     * Accepts: JSON
     */
    public function show(Request $request, $id)
    {
        try {
            $course = Course::find($id);

            if (!$course) {
                return response()->json(['error' => 'Course not found'], 404);
            }

            if ($request->accepts('application/json')) {
                return response()->json($course, 200);
            } else {
                return response()->json(['error' => 'Unsupported format'], 406);
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la récupération du cours',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * URL: /api/courses/{id}
     * Method: PUT/PATCH
     * Description: Update a specific course by ID
     * Accepts: JSON
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'location' => 'sometimes|required|string|max:255',
                'start_date' => 'sometimes|required|date',
                'status' => 'sometimes|required|string|max:255',
            ]);

            $course = Course::find($id);

            if (!$course) {
                return response()->json(['error' => 'Course not found'], 404);
            }

            $course->update([
                'location' => $request->location ?? $course->location,
                'start_date' => $request->start_date ?? $course->start_date,
                'status' => $request->status ?? $course->status,
            ]);

            return response()->json($course, 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la mise à jour du cours',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * URL: /api/courses/
     * Method: DELETE
     * Description: Delete a specific course by ID
     * Accepts: JSON
     */
    public function destroy(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|integer|exists:courses,course_id',
            ]);
            $course = Course::findOrFail($request->id);
            $course->delete();

            return response()->json(['message' => 'Course deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la suppression du cours',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
