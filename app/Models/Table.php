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
        'tb_char',
    ];

    protected $casts = [
        'tb_id' => 'string',
    ];

    public function chairs(){
        return $this->hasMany(Chair::class, 'tb_id', 'tb_id');
    }
}
