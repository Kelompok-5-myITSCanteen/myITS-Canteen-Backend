<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    /** @use HasFactory<\Database\Factories\TransctionDetailFactory> */
    use HasFactory, HasUuids;

    protected $table = 'transaction_details';
    protected $primaryKey = 'td_id';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'td_id',
        'td_quantity',
        't_id',
        'm_id'
    ];

    protected $casts = [
        'td_id' => 'string',
    ];

    public function transactions(){
        return $this->belongsTo(Transaction::class, 't_id', 't_id');
    }

    public function menu(){
        return $this->belongsTo(Menu::class, 'm_id', 'm_id');
    }
}
