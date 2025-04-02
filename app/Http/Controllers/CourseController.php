<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;

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
        $courses = Course::all();

        if ($request->accepts('application/json')) {
            return response()->json($courses, 200);
        } else {
            return response()->json(['error' => 'Unsupported format'], 406);
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
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'start_date' => 'required|date',
            'type' => 'required|string|max:50',
            'status' => 'required|string|max:255',
        ]);

        $course = Course::create([
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'start_date' => $request->start_date,
            'type' => $request->type,
            'status' => $request->status,
        ]);

        return response()->json($course, 201);
    }

    /**
     * URL: /api/courses/{id}
     * Method: GET
     * Description: Get a specific course by ID
     * Accepts: JSON
     */
    public function show(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        if ($request->accepts('application/json')) {
            return response()->json($course, 200);
        } else {
            return response()->json(['error' => 'Unsupported format'], 406);
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
        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'location' => 'sometimes|required|string|max:255',
            'start_date' => 'sometimes|required|date',
            'type' => 'sometimes|required|string|max:50',
            'status' => 'sometimes|required|string|max:255',
        ]);

        $course = Course::findOrFail($id);

        $course->update([
            'title' => $request->title ?? $course->title,
            'description' => $request->description ?? $course->description,
            'location' => $request->location ?? $course->location,
            'start_date' => $request->start_date ?? $course->start_date,
            'type' => $request->type ?? $course->type,
            'status' => $request->status ?? $course->status,
        ]);

        return response()->json($course, 200);
    }

    /**
     * URL: /api/courses/
     * Method: DELETE
     * Description: Delete a specific course by ID
     * Accepts: JSON
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'course_id' => 'required|integer|exists:courses,course_id',
        ]);
        $course = Course::findOrFail($request->course_id);
        $course->delete();

        return response()->json(['message' => 'Course deleted successfully'], 200);
    }
}
