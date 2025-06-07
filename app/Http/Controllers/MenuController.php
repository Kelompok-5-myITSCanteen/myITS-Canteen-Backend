<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Vendor;
use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function showMenuByVendor(Vendor $vendor)
    {
        try {
            $menus = Menu::where('v_id', $vendor->v_id)->get();
            return response()->json([
                'status' => 'success',
                'message' => "Menu berhasil ditemukan",
                'data' => $menus
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => "Menu gagal ditemukan",
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
    public function store(StoreMenuRequest $request)
    {
        //
        try {
            $menu = Menu::create($request->validated());

            return response()->json([
                'status' => 'success',
                'message' => "Menu berhasil ditambahkan",
                'data' => $menu
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => "Menu gagal ditambahkan: "
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMenuRequest $request, Menu $menu)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        //
    }
}
