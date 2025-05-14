<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseReservation extends Model
{
    use HasFactory;

    protected $table = "course_reservations";
    protected $primaryKey = 'reservation_id';
    protected $fillable = ["first_name","last_name","email","phone","status", "course_id"];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
