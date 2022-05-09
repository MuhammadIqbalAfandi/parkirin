<?php

namespace App\Http\Controllers;

use App\Http\Requests\TypeVehicle\StoreTypeVehicleRequest;
use App\Http\Requests\TypeVehicle\UpdateTypeVehicleRequest;
use App\Models\TypeVehicle;
use App\Services\TypeVehicleService;

class TypeVehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return inertia('typevehicle/Index', [
            'typeVehicles' => TypeVehicle::latest()
                ->paginate(10)
                ->withQueryString()
                ->through(fn($typeVehicle) => [
                    'id' => $typeVehicle->id,
                    'updatedAt' => $typeVehicle->updated_at,
                    'type' => $typeVehicle->type,
                    'isUsed' => (new TypeVehicleService)->isUsed($typeVehicle),
                ]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        return inertia('typevehicle/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTypeVehicleRequest $request)
    {
        TypeVehicle::create($request->validated());

        return back()->with('success', __('messages.success.store.type_vehicle'));
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
     * @param  TypeVehicle  $typeVehicle
     * @return \Inertia\Response
     */
    public function edit(TypeVehicle $typeVehicle)
    {
        return inertia('typevehicle/Edit', [
            'typeVehicle' => [
                'id' => $typeVehicle->id,
                'type' => $typeVehicle->type,
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  TypeVehicle  $typeVehicle
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTypeVehicleRequest $request, TypeVehicle $typeVehicle)
    {
        $isUsed = (new TypeVehicleService)->isUsed($typeVehicle);
        if (!$isUsed) {
            $typeVehicle->update($request->validated());

            return back()->with('success', __('messages.success.update.type_vehicle'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  TypeVehicle  $typeVehicle
     * @return \Illuminate\Http\Response
     */
    public function destroy(TypeVehicle $typeVehicle)
    {
        $isUsed = (new TypeVehicleService)->isUsed($typeVehicle);
        if (!$isUsed) {
            $typeVehicle->delete();

            return to_route('type-vehicles.index')->with('success', __('messages.success.destroy.type_vehicle'));
        }
    }
}
