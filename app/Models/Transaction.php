<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /** @use HasFactory<\Database\Factories\TransactionFactory> */
    use HasFactory, HasUuids;

    protected $table = 'transactions';
    protected $primaryKey = 't_id';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        't_id',
        't_is_dine',
        't_time',
        't_total',
        't_discount',
        't_payment',
        'c_id'
    ];

    protected $casts = [
        't_id' => 'string',
    ];

    public function transaction_details(){
        return $this->hasMany(TransactionDetail::class, 't_id', 't_id');
    }

    public function users(){
        return $this->belongsTo(User::class, 'c_id', 'id');
    }
}
