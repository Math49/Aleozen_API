<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InterventionReservation extends Model
{
    use HasFactory;
    protected $table = "intervention_reservations";
    protected $primaryKey = 'reservation_id';
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'intervention_date',
        'type',
        'description',
        'status',
    ];
}
