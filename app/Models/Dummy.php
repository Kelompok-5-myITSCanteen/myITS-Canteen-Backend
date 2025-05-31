<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Dummy extends Model
{
    /** @use HasFactory<\Database\Factories\DummyFactory> */
    use HasFactory, HasUuids;

    protected $table = 'dummies';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;


    protected $fillable = [
        'id',
        'name',
    ];

}
