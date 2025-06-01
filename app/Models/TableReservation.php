<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Table_Reservation extends Model
{
    /** @use HasFactory<\Database\Factories\TableReservationFactory> */
    use HasFactory, HasUuids;

    protected $table = 'table_reservations';

    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'tb_id',
        'r_id'
    ];

    protected $casts = [
        'tb_id' => 'string',
        'r_id' => 'string',
    ];

    protected function setKeysForSaveQuery($query)
    {
        $query
            ->where('tb_id', '=', $this->getAttribute('tb_id'))
            ->where('r_id', '=', $this->getAttribute('r_id'));

        return $query;
    }

    public function getKey()
    {
        return [
            'tb_id' => $this->getAttribute('tb_id'),
            'r_id' => $this->getAttribute('r_id')
        ];
    }

    public function tables(){
        return $this->belongsTo(Table::class, 'tb_id', 'tb_id');
    }

    public function reservations(){
        return $this->belongsTo(Reservation::class, 'r_id', 'r_id');
    }
}
