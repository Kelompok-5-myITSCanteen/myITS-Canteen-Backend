<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    /** @use HasFactory<\Database\Factories\TableFactory> */
    use HasFactory, HasUuids;

    protected $table = 'tables';
    protected $primaryKey = 'tb_id';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'tb_id',
        'tb_number',
        'tb_capacity',
        'k_id',
    ];

    protected $casts = [
        'tb_id' => 'string',
    ];

    public function canteens(){
        return $this->belongsTo(Canteen::class, 'k_id', 'k_id');
    }

    public function table_reservations(){
        return $this->hasMany(TableReservation::class, 'tb_id', 'tb_id');
    }
}
