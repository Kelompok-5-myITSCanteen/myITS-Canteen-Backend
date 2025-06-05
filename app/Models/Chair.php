<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Chair extends Model
{
    /** @use HasFactory<\Database\Factories\ChairFactory> */
    use HasFactory, HasUuids;

    protected $table = 'chairs';
    protected $primaryKey = 'ch_id';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'ch_id',
        'ch_number',
        'tb_id',
        'k_id'
    ];

    protected $casts = [
        'ch_id' => 'string',
    ];

    public function canteens(){
        return $this->belongsTo(Canteen::class, 'k_id', 'k_id');
    }

    public function table_reservations(){
        return $this->hasMany(ChairReservation::class, 'ch_id', 'ch_id');
    }
}
