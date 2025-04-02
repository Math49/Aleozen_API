<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrainingReservation extends Model
{
    use HasFactory;
    protected $table = "training_reservations";
    protected $primaryKey = 'reservation_id';
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'application_file',
        'interview_date',
        'status',
        'pay',
        'training_id',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function reservations()
    {
        return $this->hasMany(TrainingReservation::class);
    }
    public function training()
    {
        return $this->belongsTo(Training::class, 'training_id');
    }

}
