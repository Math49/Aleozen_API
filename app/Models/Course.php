<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $table = "courses";
    protected $primaryKey = 'course_id';
    protected $fillable = [
        'title',
        'description',
        'location',
        'start_date',
        'type',
        'status',        
    ];

    public function reservations()
    {
        return $this->hasMany(CourseReservation::class, 'course_id', 'course_id');
    }
}
