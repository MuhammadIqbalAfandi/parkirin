<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\TestTransaction\StoreTestTransactionRequest;
use App\Models\EntryTransaction;
use App\Models\OutTransaction;
use App\Models\TypeVehicle;
use App\Models\Vehicle;
use App\Services\ParkingFeeService;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;

class TestTransactionController extends Controller
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
     * @return \Inertia\Response
     */
    public function create()
    {
        $entryTransactions = EntryTransaction::whereNotIn('transaction_number', OutTransaction::pluck('entry_transaction_id'))
            ->latest()
            ->paginate(10)
            ->withQueryString()
            ->through(fn($transactionIn) => [
                'id' => $transactionIn->id,
                'entryCar' => $transactionIn->created_at,
                'transactionNumber' => $transactionIn->transaction_number
            ]);

        $typeVehicles = function () {
            $vehicle = Vehicle::where('plat_number', request('plat_number'))->first();

            if ($vehicle) {
                return [[
                    'value' => $vehicle->typeVehicle->id,
                    'label' => $vehicle->typeVehicle->type
                ]];
            } else {
                return TypeVehicle::get()->transform(fn($typeVehicle) => [
                    'value' => $typeVehicle->id,
                    'label' => $typeVehicle->type
                ]);
            }
        };

        return inertia('testtransaction/Create.vue', compact([
            'entryTransactions',
            'typeVehicles'
        ]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTestTransactionRequest $request)
    {
        // if transaction out, else transaction in
        if ($request->id === 2) {
            $vehicle = Vehicle::where('plat_number', $request->plat_number)->first();

            // Check member or not
            if ($vehicle && $vehicle->member_id) {
                $expDate = $vehicle->member->getRawOriginal('exp_date');

                // Check expired member or not
                $exp = !Carbon::parse($expDate)->greaterThanOrEqualTo(now());

                if ($exp) {
                    return back()->with('warning', __('words.member_expired'));
                }

                OutTransaction::create([
                    'plat_number' => $request->plat_number,
                    'entry_transaction_id' => $request->entry_transaction_id,
                    'type_vehicle_id' => $vehicle->type_vehicle_id,
                    'user_id' => auth()->user()->id
                ]);

                return back()->with('success', __('messages.success.store.transaction'));
            } else {
                /*
                 * Pseudo code
                 *
                 * waktu_kendaraan_masuk
                 * waktu_kendaraan_keluar
                 * waktu_lama_parkir = perbedaan_waktu(waktu_kendaraan_keluar [dengan] waktu_kendaraan_masuk)
                 *
                 * tarif_waktu_parkir = [jam_pertama, jam_pertama + jam_kedua, dst]
                 * tarif_harga_parkir = [harga_pertama, harga_pertama + harga_kedua, dst]
                 *
                 * foreach: waktu_parkir, index of tarif_waktu_parkir
                 *      if: waktu_lama_parkir > waktu_parkir
                 *          return index
                 *      else:
                 *          return index
                 *
                 * lama_parkir = tarif_waktu_parkir[index]
                 * harga_parkir = tarif_harga_parkir[index]
                 *
                 */

                DB::beginTransaction();

                try {
                    $outTransaction = OutTransaction::create([
                        'plat_number' => $request->plat_number,
                        'entry_transaction_id' => $request->entry_transaction_id,
                        'type_vehicle_id' => $request->type_vehicle_id,
                        'user_id' => auth()->user()->id
                    ]);

                    $outTransaction->mutation()->create([
                        'type' => 1,
                        'amount' => ParkingFeeService::totalPriceParking($request->entry_transaction_id)
                    ]);

                    DB::commit();

                    return back()->with('success', __('messages.success.store.transaction'));
                } catch (QueryException $e) {
                    DB::rollBack();

                    return back()->with('error', __('messages.error.store.transaction'));
                }
            }
        } else {
            EntryTransaction::create([
                'transaction_number' => Str::uuid()
            ]);

            return back()->with('success', __('messages.success.store.transaction'));
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
