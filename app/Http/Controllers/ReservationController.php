<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReservationRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        //
    }

    public function createChairReservation($r_id, $chairs)
    {
        try {
            

            return [
                'success' => true,
                'message' => 'Reservasi kursi berhasil dibuat',
                'data' => $reservation
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal membuat reservasi kursi: ' . $e->getMessage()
            ];
        }
    }

    public function createForTransaction($t_id, $time_in, $time_out, $chairs)
    {
      try {
        $reservation = Reservation::create([
            't_id' => $t_id,
            'r_time_in' => $time_in,
            'r_time_out' => $time_out
        ]);

        $crResult = (new ChairReservationController())->createForTransaction($reservation->r_id, $chairs);

        if (!$crResult['success']) {
            throw new \Exception($crResult['message']);
        }

        return [
            'success' => true,
            'message' => 'Reservasi berhasil dibuat',
            'data' => $reservation
        ];

      } catch (\Exception $e){
            return [
                'success' => false,
                'message' => 'Gagal membuat reservasi: ' . $e->getMessage()
            ];
      }
    }
}
