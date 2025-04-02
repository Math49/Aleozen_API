<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingContent extends Model
{
    protected $table = "training_contents";
    protected $primaryKey = 'content_id';
    protected $fillable = [
        'name',
        'description',
        'files',
        'training_id',
    ];

    public function training()
    {
        return $this->belongsTo(Training::class);
    }
    public function trainingReservation()
    {
        return $this->hasMany(TrainingReservation::class);
    }
}
