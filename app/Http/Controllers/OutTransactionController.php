<?php

namespace App\Http\Controllers;

use App\Models\OutTransaction;
use App\Services\ParkingFeeService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OutTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $outTransaction = OutTransaction::find($id);

        return inertia('outtransaction/Show', [
            'outTransaction' => [[
                'entryCar' => $outTransaction->entryTransaction->created_at,
                'outCar' => $outTransaction->created_at,
                'totalTimeParking' => Carbon::parse(
                    $outTransaction->getRawOriginal('created_at')
                )->diffForHumans(
                    $outTransaction->entryTransaction->getRawOriginal('created_at')
                ),
                'totalPriceParking' => ParkingFeeService::totalPriceParkingString($outTransaction->entry_transaction_id),
                'platNumber' => $outTransaction->plat_number,
                'entryTransactionId' => $outTransaction->entry_transaction_id
            ]],
            'user' => $outTransaction->user->name
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
