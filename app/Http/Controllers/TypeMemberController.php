<?php

namespace App\Http\Controllers;

use App\Http\Requests\TypeMember\StoreTypeMemberRequest;
use App\Http\Requests\TypeMember\UpdateTypeMemberRequest;
use App\Models\MaxVehicle;
use App\Models\TypeMember;
use App\Models\TypeVehicle;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class TypeMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return inertia('typemember/Index', [
            'typeMembers' => TypeMember::get()->transform(fn($typeMember) => [
                'id' => $typeMember->id,
                'updatedAt' => $typeMember->updated_at,
                'type' => $typeMember->type,
                'description' => $typeMember->description,
                'price' => $typeMember->price,
                'max' => $typeMember->maxVehicleDetail(),
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
        return inertia('typemember/Create', [
            'typeVehicles' => TypeVehicle::get()->transform(fn($typeVehicle) => [
                'value' => $typeVehicle->id,
                'label' => $typeVehicle->type,
            ]),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTypeMemberRequest $request)
    {
        DB::beginTransaction();

        try {
            $typeMember = TypeMember::create($request->validated());

            foreach ($request->maxVehicles as $maxVehicle) {
                $typeMember->maxVehicles()->create([
                    'max' => $maxVehicle['max'],
                    'type_vehicle_id' => $maxVehicle['typeVehicleId'],
                ]);
            }

            DB::commit();

            return back()->with('success', __('messages.success.store.type_member'));
        } catch (QueryException $qe) {
            DB::rollBack();

            return back()->with('error', __('messages.error.store.type_member'));
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
     * @param  TypeMember  $typeMember
     * @return \Inertia\Response
     */
    public function edit(TypeMember $typeMember)
    {
        return inertia('typemember/Edit', [
            'typeMember' => [
                'id' => $typeMember->id,
                'type' => $typeMember->type,
                'description' => $typeMember->description,
                'price' => $typeMember->getRawOriginal('price'),
                'availableToMember' => $typeMember->member()->exists(),
            ],
            'typeVehicles' => TypeVehicle::get()->transform(fn($typeVehicle) => [
                'value' => $typeVehicle->id,
                'label' => $typeVehicle->type,
            ]),
            'maxVehicles' => $typeMember->maxVehicles->transform(fn($maxVehicle) => [
                'max' => $maxVehicle->max,
                'type' => $maxVehicle->typeVehicle->type,
                'typeVehicleId' => $maxVehicle->type_vehicle_id,
            ]),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  TypeMember  $typeMember
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTypeMemberRequest $request, TypeMember $typeMember)
    {
        DB::beginTransaction();

        try {
            $typeMember->update($request->validated());

            MaxVehicle::where('type_member_id', $typeMember->id)->delete();

            foreach ($request->maxVehicles as $maxVehicle) {
                $typeMember->maxVehicles()->create([
                    'max' => $maxVehicle['max'],
                    'type_vehicle_id' => $maxVehicle['typeVehicleId'],
                ]);
            }

            DB::commit();

            return back()->with('success', __('messages.success.update.type_member'));

        } catch (QueryException $qe) {
            DB::rollBack();

            return back()->with('error', __('messages.error.update.type_member'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  TypeMember  $typeMember
     * @return \Illuminate\Http\Response
     */
    public function destroy(TypeMember $typeMember)
    {
        if (!$typeMember->member()->exists()) {
            $typeMember->delete();

            return to_route('users.index')->with('success', __('messages.success.destroy.type_member'));
        }
    }
}
