<?php

namespace App\Http\Controllers;

use App\Models\TransactionDetail;
use App\Http\Requests\StoreTransactionDetailRequest;
use App\Http\Requests\UpdateTransactionDetailRequest;

class TransactionDetailController extends Controller
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
    public function store(StoreTransactionDetailRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(TransactionDetail $transactionDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TransactionDetail $transactionDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionDetailRequest $request, TransactionDetail $transactionDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TransactionDetail $transactionDetail)
    {
        //
    }

    public function createForTransaction($t_id, $validatedItems){
        try {
            $createdDetails = [];

            foreach ($validatedItems as $item) {
                $detail = $this->createTransactionDetail($t_id, $item['item'], $item['quantity']);
            
                if (!$detail['success']){
                    throw new \Exception($detail['message']);
                }

                $createdDetails[] = $detail['data'];
            }

            return [
                'success' => true,
                'message' => 'Berhasil membuat detail transaksi',
                'data' => $createdDetails,
            ];


        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal membuat detail transaksi: ' . $e->getMessage(),
            ];
        }
    }

    public function createTransactionDetail($t_id, $menu, $quantity){
        try {
            $transactionDetail = TransactionDetail::create([
                'td_quantity' => $quantity,
                't_id' => $t_id,
                'm_id' => $menu->m_id,
            ]);

            $menu->decrement('m_stock', $quantity);

            return [
                'success' => true,
                'message' => 'Berhasil membuat detail transaksi',
                'data' => $transactionDetail,
            ];


        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal membuat detail transaksi',
            ];
        }
    }
}
