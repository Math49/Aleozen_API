<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterventionReservation extends Model
{
    protected $table = "intervention_reservations";
    protected $primaryKey = 'reservation_id';
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'status',
        'pay',
    ];
}
