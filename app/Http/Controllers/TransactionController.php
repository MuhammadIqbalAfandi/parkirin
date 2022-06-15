<?php

namespace App\Http\Controllers;

use App\Http\Requests\Transaction\StoreTransactionRequest;
use App\Models\OutTransaction;
use App\Models\TypeVehicle;
use App\Models\Vehicle;
use App\Services\ParkingFeeService;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
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
        $outTransactions = OutTransaction::latest()
            ->get()
            ->take(10)
            ->transform(fn($transactionOut) => [
                'id' => $transactionOut->id,
                'entryCar' => $transactionOut->entryTransaction->created_at,
                'outCar' => $transactionOut->created_at,
                'totalTimeParking' => ParkingFeeService::totalTimeParkingHuman(
                    $transactionOut->entry_transaction_id
                ),
                'totalPriceParking' => ParkingFeeService::totalPriceParkingString(
                    $transactionOut->entry_transaction_id
                ),
                'platNumber' => $transactionOut->plat_number,
                'entryTransactionId' => $transactionOut->entry_transaction_id,
            ]);

        $typeVehicles = function () {
            $vehicle = Vehicle::where('plat_number', request('plat_number'))->first();

            if ($vehicle) {
                return [[
                    'value' => $vehicle->typeVehicle->id,
                    'label' => $vehicle->typeVehicle->type,
                ]];
            } else {
                return TypeVehicle::get()->transform(fn($typeVehicle) => [
                    'value' => $typeVehicle->id,
                    'label' => $typeVehicle->type,
                ]);
            }
        };

        $detailOutTransaction = function () {
            if (request('transaction_number')) {
                return [
                    'totalTimeParking' => ParkingFeeService::totalTimeParkingHuman(
                        request('transaction_number')
                    ),
                    'totalPriceParking' => ParkingFeeService::totalPriceParkingString(
                        request('transaction_number')
                    ),
                ];
            }
        };

        return inertia('transaction/Create.vue', compact([
            'outTransactions',
            'typeVehicles',
            'detailOutTransaction',
        ]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTransactionRequest $request)
    {
        $vehicle = Vehicle::where('plat_number', $request->plat_number)->first();

        if ($vehicle && $vehicle->member_id) {
            $expDate = $vehicle->member->getRawOriginal('exp_date');

            $exp = !Carbon::parse($expDate)->greaterThanOrEqualTo(now());

            if ($exp) {
                return back()->with('warning', __('words.member_expired'));
            }

            OutTransaction::create([
                'plat_number' => $request->plat_number,
                'entry_transaction_id' => $request->entry_transaction_id,
                'type_vehicle_id' => $vehicle->type_vehicle_id,
                'user_id' => auth()->user()->id,
            ]);

            return back()->with('success', __('messages.success.store.transaction'));
        } else {
            DB::beginTransaction();

            try {
                $outTransaction = OutTransaction::create([
                    'plat_number' => $request->plat_number,
                    'entry_transaction_id' => $request->entry_transaction_id,
                    'type_vehicle_id' => $request->type_vehicle_id,
                    'user_id' => auth()->user()->id,
                ]);

                $outTransaction->mutation()->create([
                    'type' => 1,
                    'amount' => ParkingFeeService::totalPriceParking($request->entry_transaction_id),
                ]);

                DB::commit();

                return back()->with('success', __('messages.success.store.transaction'));
            } catch (QueryException $e) {
                DB::rollBack();

                return back()->with('error', __('messages.error.store.transaction'));
            }
        }
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
