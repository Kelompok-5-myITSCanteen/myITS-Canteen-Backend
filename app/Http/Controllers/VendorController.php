<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Http\Requests\StoreVendorRequest;
use App\Http\Requests\UpdateVendorRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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

    public function salesReport(Vendor $vendor, Request $request)
    {
        $view = $request->query('view', 'weekly');

        if ($view === 'monthly') {
            $rows = DB::table('monthly_revenue_logs')
                ->where('v_id', $vendor->v_id)
                ->orderBy('log_month', 'asc')
                ->get(['log_month', 'total_revenue']);

            $records = $rows->map(function($r) {
                return [
                    'month'         => $r->log_month,
                    'total_revenue' => (float) $r->total_revenue,
                ];
            });

            $message = "Monthly sales report berhasil ditemukan";
        } else {
            // default: weekly
            $rows = DB::table('weekly_revenue_logs')
                ->where('v_id', $vendor->v_id)
                ->orderBy('log_week_start', 'asc')
                ->get(['log_week_start', 'total_revenue']);

            $records = $rows->map(function($r) {
                $start = Carbon::parse($r->log_week_start);
                return [
                    'week_start'    => $start->toDateString(),
                    'week_end'      => $start->addDays(6)->toDateString(),
                    'total_revenue' => (float) $r->total_revenue,
                ];
            });

            $message = "Weekly sales report berhasil ditemukan";
        }

        return response()->json([
            'status'  => 'success',
            'message' => $message,
            'data'    => [
                'v_id'    => $vendor->v_id,
                'v_name'  => $vendor->v_name,
                'view'    => $view,
                'records' => $records,
            ],
        ], 200);
    }

    public function salesLastWeek(Vendor $vendor)
    {
        $oneWeekAgo = Carbon::now()->subWeek();
    
        // Ambil total penjualan per hari selama seminggu terakhir
        $salesPerDay = DB::table('transactions as t')
            ->join('transaction_details as td', 't.t_id', '=', 'td.t_id')
            ->join('menus as m', 'td.m_id', '=', 'm.m_id')
            ->where('m.v_id', $vendor->v_id)
            ->where('t.t_time', '>=', $oneWeekAgo)
            ->where('t.t_status', 'Selesai')
            ->select(
                DB::raw('DATE(t.t_time) as date'),
                DB::raw('SUM(td.td_quantity * m.m_price) as daily_total')
            )
            ->groupBy(DB::raw('DATE(t.t_time)'))
            ->orderBy('date', 'asc')
            ->get();
    
        // Total seluruh minggu
        $totalSales = $salesPerDay->sum('daily_total');
        return response()->json([
            'status' => 'success',
            'message' => "Penjualan last week berhasil ditemukan",
            'data' => [
            'v_id'   => $vendor->v_id,
            'v_name' => $vendor->v_name,
            'period' => [
                'from' => $oneWeekAgo->toDateString(),
                'to'   => Carbon::now()->toDateString(),
            ],
            'total_sales_last_week' => (float) $totalSales,
            'sales_per_day' => $salesPerDay,
            ]
        ], 200);
    }
    

    public function topMenuLastWeek(Vendor $vendor)
    {
        $oneWeekAgo = Carbon::now()->subWeek();
    
        $menus = DB::table('transaction_details as td')
            ->join('menus as m', 'td.m_id', '=', 'm.m_id')
            ->join('transactions as t', 'td.t_id', '=', 't.t_id')
            ->where('m.v_id', $vendor->v_id)
            ->where('t.t_time', '>=', $oneWeekAgo)
            ->where('t.t_status', 'Selesai')
            ->select(
                'm.m_id as menu_id',
                'm.m_name as menu_name',
                DB::raw('SUM(td.td_quantity) as total_sold')
            )
            ->groupBy('m.m_id', 'm.m_name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();
    
        $totalSoldAll = $menus->sum('total_sold');
    
        $topMenus = $menus->map(function ($menu) use ($totalSoldAll) {
            return [
                'menu_id'    => $menu->menu_id,
                'menu_name'  => $menu->menu_name,
                'total_sold' => (int) $menu->total_sold,
                'percentage' => $totalSoldAll > 0
                    ? round(($menu->total_sold / $totalSoldAll) * 100, 2)
                    : 0,
            ];
        });
        return response()->json([
            'status' => 'success',
            'message' => "Top menus last week berhasil ditemukan",
            'data' => [
                'v_id'   => $vendor->v_id,
                'v_name' => $vendor->v_name,
                'period' => [
                    'from' => $oneWeekAgo->toDateString(),
                    'to'   => Carbon::now()->toDateString(),
                ],
                'top_menus' => $topMenus,
                'total_menus_sold_last_week' => (int) $totalSoldAll,
            ]
        ], 200);
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
