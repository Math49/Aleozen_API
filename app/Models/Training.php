<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Training extends Model
{
    use HasFactory;
    protected $primaryKey = 'training_id';
    protected $fillable = [
        'title',
        'description',
        'location',
        'start_date',
        'price',
        'status',
    ];

    public function trainingReservations(){
        return $this->hasMany(TrainingReservation::class, 'training_id', 'training_id');
    }
    public function trainingContents()
    {
        return $this->hasMany(TrainingContent::class, 'training_id', 'training_id');
    }

}
