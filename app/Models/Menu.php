<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Vendor;

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
        'm_name',
        'm_category',
        'm_price',
        'm_stock',
        'm_image',
        'v_id'
    ];

    protected $casts = [
        'm_id' => 'uuid',
        'm_name' => 'varchar:60',
        'm_category' => 'varchar:60',
        'm_price' => 'decimal(12,2)',
        'm_stock' => 'integer',
        'm_image' => 'varchar:255',
        'v_id' => 'uuid'

    ];

    public function transaction_details(){
        return $this->hasMany(TransactionDetail::class, 'm_id', 'm_id');
    }

    public function vendors(){
        return $this->belongsTo(Vendor::class, 'v_id', 'v_id');
    }

}
