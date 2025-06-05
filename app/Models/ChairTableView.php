<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ChairTableView extends Model
{
    /** @use HasFactory<\Database\Factories\ChairTableViewFactory> */
    use HasFactory;

    protected $table = 'chair_table_view';
    protected $primaryKey = 'ch_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $guarded = ['*'];

    protected $casts = [
        'ch_id' => 'string',
        'tb_id' => 'string',
        'k_id' => 'string',
        'chair_name' => 'string',
    ];

    public static function getAvailableChairs($canteenId, $time_in, $time_out){
        return DB::table('chair_table_view as ctv')
            ->leftJoin('chair_reservations as cr', 'ctv.ch_id', '=', 'cr.ch_id')
            ->leftJoin('reservations as r', function($join) use ($time_in, $time_out) {
                $join->on('cr.r_id', '=', 'r.r_id')
                     ->where('r.r_time_in', '<', $time_out)
                     ->where('r.r_time_out', '>', $time_in);
            })
            ->where('ctv.k_id', $canteenId)
            ->whereNull('r.r_id')
            ->select('ctv.ch_id', 'ctv.chair_name')
            ->get();
    }
}
