<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ChairReservation extends Model
{
    /** @use HasFactory<\Database\Factories\TableReservationFactory> */
    use HasFactory;

    protected $chair = 'chair_reservations';

    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'ch_id',
        'r_id'
    ];

    protected $casts = [
        'ch_id' => 'string',
        'r_id' => 'string',
    ];

    protected function setKeysForSaveQuery($query)
    {
        $query
            ->where('ch_id', '=', $this->getAttribute('ch_id'))
            ->where('r_id', '=', $this->getAttribute('r_id'));

        return $query;
    }

    public function getKey()
    {
        return [
            'ch_id' => $this->getAttribute('ch_id'),
            'r_id' => $this->getAttribute('r_id')
        ];
    }

    public function chair(){
        return $this->belongsTo(Chair::class, 'ch_id', 'ch_id');
    }

    public function reservation(){
        return $this->belongsTo(Reservation::class, 'r_id', 'r_id');
    }
}
