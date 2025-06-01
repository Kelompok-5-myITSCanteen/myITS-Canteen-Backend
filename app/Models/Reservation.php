<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    /** @use HasFactory<\Database\Factories\ReservationFactory> */
    use HasFactory, HasUuids;

    protected $table = 'reservations';
    protected $primaryKey = 'r_id';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'r_id',
        'r_time_in',
        'r_time_out',
        't_id'
    ];

    protected $casts = [
        'r_id' => 'string',
    ];

    public function table_reservations(){
        return $this->hasMany(TableReservation::class, 'r_id', 'r_id');
    }

    public function transactions(){
        return $this->belongsTo(Transcations::class, 't_id', 't_id');
    }
}
