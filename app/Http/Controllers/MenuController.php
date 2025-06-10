<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Vendor;
use App\Models\Canteen;
use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function showMenuByCanteen(Canteen $canteen)
    {
        try {
            $menus = DB::table('menus')
                ->join('vendors', 'menus.v_id', '=', 'vendors.v_id')
                ->join('canteens', 'vendors.k_id', '=', 'canteens.k_id')
                ->where('vendors.k_id', $canteen->k_id)
                ->select('menus.m_id', 'menus.m_name', 'menus.m_category', 'menus.m_price', 'menus.m_stock')
                ->get();
            if ($menus->isEmpty()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => "Menu tidak ditemukan untuk kantin ini",
                ], 404);
            }
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

    public function showMenuByVendor(Vendor $vendor)
    {
        try {
            $menus = Menu::where('v_id', $vendor->v_id)
                ->with('vendors')
                ->get();
            if ($menus->isEmpty()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => "Menu tidak ditemukan untuk vendor ini",
                ], 404);
            }

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

    // Display menu from vendor side
    public function index()
    {
        try {
            $vendorId = Vendor::where('c_id', auth()->user()->id)->value('v_id');
            // Find the vendor associated with this user
            if (!$vendorId) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Vendor tidak ditemukan untuk akun ini',
                ], 404);
            }

            // Get menus for this vendor only, including latest changed_at from menu_update_logs
            $menus = DB::table('menus')
                ->leftJoin(DB::raw('(
                    SELECT m_id, MAX(changed_at) as last_modified
                    FROM menu_update_logs
                    GROUP BY m_id
                ) as mul'), 'menus.m_id', '=', 'mul.m_id')
                ->where('menus.v_id', $vendorId)
                ->select(
                    'menus.m_id',
                    'menus.m_name',
                    'menus.m_category',
                    'menus.m_price',
                    'menus.m_stock',
                    'mul.last_modified'
                )
                ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Menu berhasil ditemukan',
                'data' => $menus
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Menu gagal ditemukan. ' . $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMenuRequest $request){
        try {
            $vendorId = Vendor::where('c_id', auth()->user()->id)->value('v_id');
            $data = $request->validated();
            $data['v_id'] = $vendorId;

            // Create the menu first (without image)
            $menu = Menu::create($data);

            // Handle image upload if present
            if ($request->hasFile('m_image')) {
                $imageName = $menu->m_id . '.' . $request->file('m_image')->getClientOriginalExtension();
                Storage::disk('public')->putFileAs(
                    'images/menus',
                    $request->file('m_image'),
                    $imageName
                );
                $menu->m_image = 'storage/images/menus/' . $imageName;
                $menu->save();
            }

            return response()->json([
                'status' => 'success',
                'message' => "Menu berhasil ditambahkan",
                'data' => $menu
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => "Menu gagal ditambahkan: " . $e->getMessage()
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
     * Update the specified resource in storage.
     */
    public function update(UpdateMenuRequest $request, Menu $menu)
    {
        try {
            $vendorId = Vendor::where('c_id', auth()->user()->id)->value('v_id');
            // Find the vendor associated with this user
            if (!$vendorId) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Vendor tidak ditemukan untuk akun ini',
                ], 404);
            }
            $menu->update($request->all());
            $menu->refresh();
            return response()->json([
                'status' => 'success',
                'message' => "Menu berhasil diupdate",
                'data' => $menu
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => "Menu gagal diupdate"
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        try {
            $vendorId = Vendor::where('c_id', auth()->user()->id)->value('v_id');
            // Find the vendor associated with this user
            if (!$vendorId) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Vendor tidak ditemukan untuk akun ini',
                ], 404);
            }
            // Check if the menu belongs to this vendor
            if ($menu->v_id != $vendorId) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Anda tidak berhak menghapus menu ini',
                ], 403);
            }

            $menu->delete();
            return response()->json([
                'status' => 'success',
                'message' => "Menu berhasil dihapus"
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => "Menu gagal dihapus"
            ], 500);
        }
    }
}
