<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Http\Requests\StoreVendorRequest;
use App\Http\Requests\UpdateVendorRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $vendors = Vendor::all();

            return response()->json([
                'status' => 'success',
                'message' => "Vendor berhasil ditemukan",
                'data' => $vendors
            ], 200);
        } catch (\Exception $e){
            return response()->json([
                'status' => 'failed',
                'message' => "Vendor gagal ditemukan.",
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
    public function store(StoreVendorRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Vendor $vendor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vendor $vendor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVendorRequest $request, Vendor $vendor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vendor $vendor)
    {
        //
    }

    public function getDailyData(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'status' => 'failed',
                'message' => "User tidak ditemukan.",
            ], 404);
        }

        $vendor = Vendor::where('c_id', $user->id)->first();

        if (!$vendor) {
            return response()->json([
                'status' => 'failed',
                'message' => "Vendor tidak ditemukan.",
            ], 404);
        }

        try {
            $dailyData = DB::table('vendors as v')
                ->leftJoin('vendor_transaction_count_view as vtc', 'vtc.v_id', '=', 'v.v_id')
                ->leftJoin('vendor_purchased_menus_view as vpm', 'vpm.v_id', '=', 'v.v_id')
                ->leftJoin('vendor_earnings_view as ve', 've.v_id', '=', 'v.v_id')
                ->leftJoin('vendor_unique_customer_count_view as vucc', 'vucc.v_id', '=', 'v.v_id')
                ->where('v.v_id', $vendor->v_id)
                ->select([
                    'v.v_id',
                    'vtc.transaction_count',
                    'vpm.total_purchased',
                    've.total_earnings',
                    'vucc.unique_customers'
                ])
                ->first();

            if ($dailyData) {
                $dailyData->transaction_count = (int) ($dailyData->transaction_count ?? 0);
                $dailyData->total_purchased = (int) ($dailyData->total_purchased ?? 0);
                $dailyData->total_earnings = (int) ($dailyData->total_earnings ?? 0);
                $dailyData->unique_customers = (int) ($dailyData->unique_customers ?? 0);
            }

            return response()->json([
                'status' => 'success',
                'message' => "Data harian vendor berhasil ditemukan",
                'data' => $dailyData
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => "Data harian vendor gagal ditemukan.",
            ], 500);
        }
    }
}
