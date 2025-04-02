<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = "courses";
    protected $primaryKey = 'course_id';
    protected $fillable = [
        'location',
        'start_date',
        'status',        
    ];

    public function reservations()
    {
        return $this->hasMany(CourseReservation::class, 'course_id', 'course_id');
    }
}
