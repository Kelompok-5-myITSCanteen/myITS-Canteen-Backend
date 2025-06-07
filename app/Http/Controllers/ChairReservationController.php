<?php

namespace App\Http\Controllers;

use App\Models\ChairReservation;
use App\Http\Requests\StoreChairReservationRequest;
use App\Http\Requests\UpdateChairReservationRequest;

class ChairReservationController extends Controller
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
    public function store(StoreChairReservationRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ChairReservation $chairReservation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ChairReservation $chairReservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChairReservationRequest $request, ChairReservation $chairReservation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChairReservation $chairReservation)
    {
        //
    }

    public function createForTransaction($r_id, $chairs)
    {
        try {
            $createdReservations = [];

            foreach ($chairs as $chair){
                $reservation = ChairReservation::create([
                    'r_id' => $r_id,
                    'ch_id' => $chair->ch_id,
                ]);

                $createdReservations[] = $reservation;
            }

            return [
                'success' => true,
                'message' => 'Reservasi kursi berhasil dibuat',
                'data' => $createdReservations
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal membuat reservasi kursi: ' . $e->getMessage()
            ];
        }
    }

    


}
