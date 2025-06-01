<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Canteen extends Model
{
    /** @use HasFactory<\Database\Factories\CanteenFactory> */
    use HasFactory, HasUuids;

    protected $table = 'canteens';
    protected $primaryKey = 'k_id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'k_id',
        'k_name',
        'k_address'
    ];

    protected $casts = [
        'k_id' => 'uuid',
        'k_name' => 'varchar(60)',
        'k_address' => 'varchar(255)'
    ];

    public function vendors(){
        return $this->hasMany(Vendor::class, 'k_id', 'k_id');
    }

    public function tables(){
        return $this->hasMany(Table::class, 'k_id', 'k_id');
    }
}