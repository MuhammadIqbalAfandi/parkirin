<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParkingFee\StoreParkingFeeRequest;
use App\Models\ParkingFee;
use Illuminate\Http\Request;

class ParkingFeeController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(ParkingFee::class);
    }

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
     * @return \Inertia\Response
     */
    public function create()
    {
        $parkingFees = ParkingFee::get()->transform(fn($parkingFee) => [
            'id' => $parkingFee->id,
            'price' => $parkingFee->price,
            'timePeriod' => $parkingFee->time_period,
        ]);
        return inertia('parkingfee/Create', compact('parkingFees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreParkingFeeRequest $request)
    {
        foreach ($request->id as $key => $id) {
            ParkingFee::upsert([
                'id' => $id,
                'time_period' => $request->timePeriod[$key],
                'price' => $request->price[$key],
            ], ['id']);
        }

        return back()->with('success', __('messages.success.store.parking_fee'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
