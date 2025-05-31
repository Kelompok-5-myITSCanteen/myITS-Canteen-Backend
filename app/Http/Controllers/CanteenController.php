<?php

namespace App\Http\Controllers;

use App\Models\Canteen;
use App\Http\Requests\StorecanteenRequest;
use App\Http\Requests\UpdatecanteenRequest;

class CanteenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $canteens = canteen::all();

            return response()->json([
                'status' => 'success',
                'message' => "Kantin berhasil ditemukan",
                'data' => $canteens
            ], 200);
        } catch (\Exception $e){
            return response()->json([
                'status' => 'failed',
                'message' => "Kantin gagal ditemukan: " . $e->getMessage(),
            ], 500);
        }
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
    public function store(StorecanteenRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(canteen $canteen)
    {
        try {
            return response()->json([
                'status' => 'success',
                'message' => 'Kantin berhasil ditemukan',
                'data' => $canteen
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Kantin gagal ditemukan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(canteen $canteen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatecanteenRequest $request, canteen $canteen)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(canteen $canteen)
    {
        //
    }
}