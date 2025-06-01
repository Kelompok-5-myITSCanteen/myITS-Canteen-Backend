<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUuids, HasApiTokens, HasRoles;
    protected $table = 'vendors';
    protected $primaryKey = 'v_id';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'v_id',
        'v_name',
        'v_join_date',
        'k_id', // Canteen ID FK
        'c_id' // Account ID FK
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'v_id' => 'uuid',
        'v_name' => 'varchar(60)',
        'v_join_date' => 'date',
        'k_id' => 'uuid', // Canteen ID FK
        'c_id' => 'uuid' // Account ID FK
    ];
}
