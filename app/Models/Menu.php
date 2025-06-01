<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    /** @use HasFactory<\Database\Factories\MenuFactory> */
    use HasFactory, HasUuids;
    protected $table = 'menus';

    protected $primaryKey = 'm_id';
    public $incrementing = false;
    public $timestamps = false;
    
    protected $fillable = [
        'm_id',
        'm_category',
        'm_name',
        'm_image',
        'm_price',
        'm_stock',
        'k_id' // Canteen ID FK
    ];

    protected $casts = [
        'm_id' => 'uuid',
        'm_category' => 'varchar(60)',
        'm_name' => 'varchar(60)',
        'm_image' => 'varchar(255)',
        'm_price' => 'decimal(12,2)',
        'm_stock' => 'integer',
        'k_id' => 'uuid' // Canteen ID FK
    ];
}