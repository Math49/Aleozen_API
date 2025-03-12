<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    protected $primaryKey = 'training_id';
    protected $fillable = [
        'location',
        'start_date',
        'price',
        'user_id',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function training(){
        return $this->hasMany(Training::class);
    }
    public function trainingReservations(){
        return $this->hasMany(TrainingReservation::class);
    }
}
