<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    /** @use HasFactory<\Database\Factories\VendorFactory> */
    use HasFactory, HasUuids;

    protected $table = 'vendors';
    protected $primaryKey = 'v_id';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'v_name',
        'v_join_date',
        'k_id', 
        'c_id', 
    ];

    protected $casts = [
        'v_id' => 'string',
    ];

    public function canteen()
    {
        return $this->belongsTo(Canteen::class, 'k_id', 'k_id');
    }
    public function menus(){
        return $this->hasMany(Menu::class, 'v_id', 'v_id');
    }

}
