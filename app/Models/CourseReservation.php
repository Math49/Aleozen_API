<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseReservation extends Model
{
    protected $table = "course_reservations";
    protected $fillable = ["first_name","last_name","email","phone","status","pay"];

    public function reservations()
    {
        return $this->hasMany(CourseReservation::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
