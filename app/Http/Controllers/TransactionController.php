<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Reservation;
use App\Models\Menu;
use App\Models\Chair;
use App\Models\ChairTableView;
use App\Http\Controllers\TransactionDetailController;
use App\Http\Controllers\ReservationController;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransactionController extends Controller
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
    public function store(StoreTransactionRequest $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User tidak terautentikasi.',
            ], 401);
        }

        $c_id = $user->id;

        try {
            DB::beginTransaction();

            $validatedChairs = [];
            $validatedItems = [];

            if ($request->is_dine){
                $availableChairs = ChairTableView::getAvailableChairs(
                    $request->k_id,
                    $request->time_in,
                    $request->time_out
                );

                $availableChairs = $availableChairs->pluck('ch_id')->toArray();
                $unavailableChairs = array_diff($request->kursi, $availableChairs);

                if (!empty($unavailableChairs)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Kursi yang dipilih tidak tersedia.',
                        'unavailable_chairs' => $unavailableChairs,
                    ], 400);
                }
                
                $validatedChairs = Chair::whereIn('ch_id', $request->kursi)->get()->pluck('ch_id')->toArray();
            }

            foreach ($request->cartItems as $item) {
                $menu = Menu::with('vendors.canteen')->where('m_id', $item['id'])->first();

                if (!$menu){
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Menu tidak ditemukan.',
                    ], 404);
                }

                if ($menu->m_stock < $item['quantity']) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Stok untuk menu tidak cukup.',
                    ], 400);
                }

                if ($menu->vendors->canteen->k_id != $request->k_id) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Menu tidak tersedia di kantin yang dipilih.',
                    ], 400);
                }

                $validatedItems[] = [
                    'item' => $menu,
                    'quantity' => $item['quantity'],
                ];
            }

            $transaction = Transaction::create([
                't_is_dine' => $request->is_dine,
                't_time' => now(),
                't_total' => $request->total_price,
                't_discount' => $request->discount ?? 0,
                't_payment' => $request->payment,
                't_status' => 'Menunggu Konfirmasi',
                'c_id' => $c_id,
            ]);

            

            if ($request->is_dine){
                $detailReservation = (new ReservationController())->createForTransaction(
                    $transaction->t_id,
                    $request->time_in,
                    $request->time_out,
                    $validatedChairs
                );

                if (!$detailReservation['success']) {
                    throw new \Exception($detailReservation['message']);
                }
            }

            $detailResult = (new TransactionDetailController())->createForTransaction($transaction->t_id, $validatedItems);

            if (!$detailResult['success']) {
                throw new \Exception($detailResult['message']);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Transaksi berhasil dibuat',
                'data' => $transaction
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'error' => 'Transaksi gagal dibuat: ' . $e->getMessage() 
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }

    public function getByUser(Request $request){
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User tidak terautentikasi.',
            ], 401);
        }

        try {

            $transactions = Transaction::where('c_id', $user->id)
                ->with(['transaction_details.menu', 'reservation.chair_reservations.chair'])
                ->orderBy('t_time')
                ->get()
                ->map(function ($transaction){
                    return [
                        't_id' => $transaction->t_id,
                        't_time' => $transaction->t_time,
                        't_is_dine' => $transaction->t_is_dine,
                        't_total' => $transaction->t_total,
                        't_discount' => $transaction->t_discount,
                        't_payment' => $transaction->t_payment,
                        't_status' => $transaction->t_status,

                        'items' => $transaction->transaction_details->map(function ($transaction_detail){
                            return [
                                'm_id' => $transaction_detail->m_id,
                                'm_name' => $transaction_detail->menu->m_name,
                                'm_price' => $transaction_detail->menu->m_price,
                                'm_quantity' => $transaction_detail->td_quantity,
                            ];
                        }),

                        'reservation' => $transaction->t_is_dine ? [
                            'r_id' => $transaction->reservation->r_id,
                            'time_in' => $transaction->reservation->r_time_in,
                            'time_out' => $transaction->reservation->r_time_out,
                            'chairs' => $transaction->reservation->chair_reservations->map(function ($chair_reservation) {
                                $view = ChairTableView::where('ch_id', $chair_reservation->chair->ch_id)->first();

                                return [
                                    'ch_id' => $chair_reservation->chair->ch_id,
                                    'ch_name' => $view->chair_name
                                ];
                            })
                        ] : null
                    ];
                });

            return response()->json([
                'status' => 'success',
                'message' => 'Transaksi pengguna berhasil ditemukan.',
                'data' => $transactions
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mendapatkan transaksi pengguna.',
            ], 500);
        }
    }

    public function acceptTransaction(Transaction $transaction)
    {
        if ($transaction->t_status !== 'Menunggu Konfirmasi') {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaksi tidak dalam status yang dapat diterima.',
            ], 400);
        }

        $transaction->t_status = 'Selesai';
        $transaction->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Transaksi berhasil diterima.',
            'data' => $transaction
        ], 200);
    }

    public function rejectTransaction(Transaction $transaction)
    {
        if ($transaction->t_status !== 'Menunggu Konfirmasi') {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaksi tidak dalam status yang dapat ditolak.',
            ], 400);
        }

        $transaction->t_status = 'Ditolak';
        $transaction->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Transaksi berhasil ditolak.',
            'data' => $transaction
        ], 200);
    }
}
