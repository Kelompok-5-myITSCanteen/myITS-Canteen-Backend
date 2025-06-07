<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Reservation;
use App\Models\Menu;
use App\Models\Chair;
use App\Http\Controllers\TransactionDetailController;
use App\Http\Controllers\ReservationController;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use Illuminate\Support\Facades\DB;
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
                $time_in = Carbon::createFromFormat('H:i', $request->time_in);
                $time_out = Carbon::createFromFormat('H:i', $request->time_out);

                foreach ($request->kursi as $chairId) {
                    $chair = Chair::where('ch_id', $chairId)->first();

                    if (!$chair) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Kursi tidak ditemukan.',
                        ], 404);
                    }

                    $validatedChairs[] = $chair;
                }
            }

            foreach ($request->cartItems as $item) {
                $menu = Menu::where('m_id', $item['id'])->first();

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
                'c_id' => $c_id,
            ]);

            

            if ($request->is_dine){
                $detailReservation = (new ReservationController())->createForTransaction(
                    $transaction->t_id,
                    $time_in,
                    $time_out,
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
}
