<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourseReservation;
use App\Models\Course;

class CourseReservationController extends Controller
{
    /**
     * URL: /api/course-reservations
     * Method: GET
     * Description: Get all course reservations
     * Accepts: JSON
     */
    public function index(Request $request)
    {
        $courseReservations = CourseReservation::with('course')->get();

        if ($request->accepts('application/json')) {
            return response()->json($courseReservations, 200);
        } else {
            return response()->json(['error' => 'Unsupported format'], 406);
        }
    }

    /**
     * URL: /api/course-reservations
     * Method: POST
     * Description: Create a new course reservation
     * Accepts: JSON
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,course_id',
        ]);

        $courseReservation = CourseReservation::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => $request->status,
            'course_id' => $request->course_id,
        ]);

        return response()->json($courseReservation, 201);
    }

    /**
     * URL: /api/course-reservations/{id}
     * Method: GET
     * Description: Get a specific course reservation by ID
     * Accepts: JSON
     */
    public function show(Request $request, $id)
    {
        $courseReservation = CourseReservation::with('course')->findOrFail($id);

        if ($request->accepts('application/json')) {
            return response()->json($courseReservation, 200);
        } else {
            return response()->json(['error' => 'Unsupported format'], 406);
        }
    }

    /**
     * URL: /api/course/{id}/course-reservations-approved
     * Method: GET
     * Description: Get the number of approved course reservations for a specific course
     * Accepts: JSON
     */
    public function number(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        $approvedReservationsCount = CourseReservation::where('course_id', $id)
            ->where('status', 'approved')
            ->count();

        if ($request->accepts('application/json')) {
            return response()->json($approvedReservationsCount, 200);
        } else {
            return response()->json(['error' => 'Unsupported format'], 406);
        }
    }

    /**
     * URL: /api/course-reservations/{id}
     * Method: PUT/PATCH
     * Description: Update a specific course reservation by ID
     * Accepts: JSON
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255',
            'phone' => 'sometimes|required|string|max:255',
            'status' => 'sometimes|required|string|max:255',
        ]);

        $courseReservation = CourseReservation::findOrFail($id);
        $courseReservation->update([
            'first_name' => $request->first_name ?? $courseReservation->first_name,
            'last_name' => $request->last_name ?? $courseReservation->last_name,
            'email' => $request->email ?? $courseReservation->email,
            'phone' => $request->phone ?? $courseReservation->phone,
            'status' => $request->status ?? $courseReservation->status,
        ]);

        return response()->json($courseReservation, 200);
    }

    /**
     * URL: /api/course-reservations
     * Method: DELETE
     * Description: Delete a specific course reservation by ID
     * Accepts: JSON
     */
    public function destroy(Request $request, $id)
    {
        $courseReservation = CourseReservation::findOrFail($id);
        $courseReservation->delete();

        if ($request->accepts('application/json')) {
            return response()->json(['message' => 'Course reservation deleted successfully'], 200);
        } else {
            return response()->json(['error' => 'Unsupported format'], 406);
        }
    }
}
