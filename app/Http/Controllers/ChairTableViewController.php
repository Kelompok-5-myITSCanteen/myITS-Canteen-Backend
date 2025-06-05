<?php

namespace App\Http\Controllers;

use App\Models\ChairTableView;
use App\Http\Requests\StoreChairTableViewRequest;
use App\Http\Requests\UpdateChairTableViewRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Canteen;


class ChairTableViewController extends Controller
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
    public function store(StoreChairTableViewRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ChairTableView $chairTableView)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ChairTableView $chairTableView)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChairTableViewRequest $request, ChairTableView $chairTableView)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChairTableView $chairTableView)
    {
        //
    }

    public function getAvailableChairs(Request $request, Canteen $canteen)
    {
        try {
            $validated = $request->validate([
                'time_in' => 'required|date_format:Y-m-d H:i:s',
                'time_out' => 'required|date_format:Y-m-d H:i:s|after:time_in',
            ]);
 
            $availChairs = ChairTableView::getAvailableChairs(
                $canteen->k_id,
                $validated['time_in'],
                $validated['time_out']
            );

            return response()->json([
                'message' => 'Available chairs retrieved successfully',
                'data' => $availChairs
            ], 200);
 
 
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);


        }catch (\Exception $e) {
            return response()->json([
                'message' => 'Error retrieving available chairs',
                'error' => $e->getMessage()
            ], 500);
        } 
    }
}
