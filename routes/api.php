<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\TrainingReservationController;
use App\Http\Controllers\TrainingContentController;
use App\Http\Controllers\InterventionReservationController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseReservationController;

Route::middleware(['auth:sanctum'])->group(function () {
    // User routes
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users', [UserController::class, 'destroy']);

    // Training routes
    Route::get('/trainings', [TrainingController::class, 'index']);
    Route::post('/trainings', [TrainingController::class, 'store']);
    Route::get('/trainings/{id}', [TrainingController::class, 'show']);
    Route::put('/trainings/{id}', [TrainingController::class, 'update']);
    Route::delete('/trainings', [TrainingController::class, 'destroy']);

    // Training Reservation routes
    Route::get('/training-reservations', [TrainingReservationController::class, 'index']);
    Route::post('/training-reservations', [TrainingReservationController::class, 'store']);
    Route::get('/training-reservations/{id}', [TrainingReservationController::class, 'show']);
    Route::put('/training-reservations/{id}', [TrainingReservationController::class, 'update']);
    Route::delete('/training-reservations', [TrainingReservationController::class, 'destroy']);

    // Training Content routes
    Route::get('/training-contents', [TrainingContentController::class, 'index']);
    Route::post('/training-contents', [TrainingContentController::class, 'store']);
    Route::get('/training-contents/{id}', [TrainingContentController::class, 'show']);
    Route::put('/training-contents/{id}', [TrainingContentController::class, 'update']);
    Route::delete('/training-contents', [TrainingContentController::class, 'destroy']);

    // Course routes
    Route::get('/courses', [CourseController::class, 'index']);
    Route::post('/courses', [CourseController::class, 'store']);
    Route::get('/courses/{id}', [CourseController::class, 'show']);
    Route::put('/courses/{id}', [CourseController::class, 'update']);
    Route::delete('/courses', [CourseController::class, 'destroy']);

    // Course Reservation routes
    Route::get('/course-reservations', [CourseReservationController::class, 'index']);
    Route::post('/course-reservations', [CourseReservationController::class, 'store']);
    Route::get('/course-reservations/{id}', [CourseReservationController::class, 'show']);
    Route::put('/course-reservations/{id}', [CourseReservationController::class, 'update']);
    Route::delete('/course-reservations', [CourseReservationController::class, 'destroy']);

    // Intervention Reservation routes
    Route::get('/intervention-reservations', [InterventionReservationController::class, 'index']);
    Route::post('/intervention-reservations', [InterventionReservationController::class, 'store']);
    Route::get('/intervention-reservations/{id}', [InterventionReservationController::class, 'show']);
    Route::put('/intervention-reservations/{id}', [InterventionReservationController::class, 'update']);
    Route::delete('/intervention-reservations', [InterventionReservationController::class, 'destroy']);
});

