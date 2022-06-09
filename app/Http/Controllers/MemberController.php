<?php

namespace App\Http\Controllers;

use App\Http\Requests\Member\StoreMemberRequest;
use App\Http\Requests\Member\UpdateMemberRequest;
use App\Models\Member;
use App\Models\TypeMember;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return inertia('member/Index', [
            'initialSearch' => request('search'),
            'members' => Member::filter(request()->only('search'))
                ->latest()
                ->paginate(10)
                ->withQueryString()
                ->through(fn($member) => [
                    'id' => $member->id,
                    'updatedAt' => $member->updated_at,
                    'name' => $member->name,
                    'phone' => $member->phone,
                    'platNumber' => $member->vehicleDetail(),
                    'type' => $member->typeMember->type,
                    'price' => $member->topUps()->latest()->first()->amount,
                    'expDate' => $member->exp_date,
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
        return inertia('member/Create', [
            'typeMembers' => TypeMember::get()->transform(fn($typeMember) => [
                'label' => $typeMember->type,
                'value' => $typeMember->id,
            ]),
            'typeMember' => Inertia::lazy(
                fn() => TypeMember::filter(request('id'))->get()->transform(fn($typeMember) => [
                    'type' => $typeMember->type,
                    'description' => $typeMember->description,
                    'price' => $typeMember->price,
                    'max' => $typeMember->maxVehicleDetail(),
                    'maxVehicles' => $typeMember->fresh()->maxVehicles->transform(fn($maxVehicle) => [
                        'value' => $maxVehicle->id,
                        'label' => $maxVehicle->typeVehicle->type,
                        'maxVehicle' => $maxVehicle->max,
                        'typeVehicleId' => $maxVehicle->typeVehicle->id,
                    ]),
                ])->first(),
            ),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMemberRequest $request)
    {
        DB::beginTransaction();

        try {
            $member = Member::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'exp_date' => now()->addDays(30),
                'type_member_id' => $request->type_member_id,
            ]);

            foreach ($request->vehicles as $vehicle) {
                $member->vehicles()->create([
                    'plat_number' => $vehicle['platNumber'],
                    'type_vehicle_id' => $vehicle['typeVehicleId'],
                ]);
            }

            $topUp = $member->topUps()->create([
                'amount' => TypeMember::find($request->type_member_id)->getRawOriginal('price'),
                'exp_date' => now()->addDays(30),
                'user_id' => auth()->user()->id,
            ]);

            $topUp->mutation()->create([
                'type' => 1,
                'amount' => TypeMember::find($request->type_member_id)->getRawOriginal('price'),
            ]);

            DB::commit();

            return back()->with('success', __('messages.success.store.member'));
        } catch (QueryException $qe) {
            DB::rollBack();

            return back()->with('error', __('messages.error.store.member'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Member  $member
     * @return \Inertia\Response
     */
    public function show(Member $member)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Member  $member
     * @return \Inertia\Response
     */
    public function edit(Member $member)
    {
        if (request('id')) {
            $typeMember = $member->typeMember->filter(request('id'))->first();
        } else {
            $typeMember = $member->typeMember;
        }

        return inertia('member/Edit', [
            'member' => [
                'id' => $member->id,
                'name' => $member->name,
                'phone' => $member->phone,
                'typeMemberId' => $member->type_member_id,
            ],
            'initialVehicles' => $member->vehicles->transform(fn($vehicle) => [
                'platNumber' => $vehicle->plat_number,
                'typeVehicle' => $vehicle->typeVehicle->type,
                'typeVehicleId' => $vehicle->typeVehicle->id,
                'maxVehicleId' => $member->typeMember->maxVehicles
                    ->where('type_vehicle_id', $vehicle->typeVehicle->id)
                    ->pluck('id')[0],
                'maxVehicle' => $member->typeMember->maxVehicles
                    ->where('type_vehicle_id', $vehicle->typeVehicle->id)
                    ->pluck('max')[0],
            ]),
            'typeMembers' => TypeMember::get()->transform(fn($typeMember) => [
                'label' => $typeMember->type,
                'value' => $typeMember->id,
            ]),
            'typeMember' => fn() => [
                'type' => $typeMember->type,
                'description' => $typeMember->description,
                'price' => $typeMember->price,
                'max' => $typeMember->maxVehicleDetail(),
                'maxVehicles' => $typeMember->fresh()->maxVehicles->transform(fn($maxVehicle) => [
                    'value' => $maxVehicle->id,
                    'label' => $maxVehicle->typeVehicle->type,
                    'maxVehicle' => $maxVehicle->max,
                    'typeVehicleId' => $maxVehicle->typeVehicle->id,
                ]),
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Member  $member
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMemberRequest $request, Member $member)
    {
        DB::beginTransaction();

        try {
            if ($member->type_member_id !== $request->type_member_id) {
                $member->update([
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'exp_date' => now()->addDays(30),
                    'type_member_id' => $request->type_member_id,
                ]);

                $member->vehicles()->delete();

                foreach ($request->vehicles as $vehicle) {
                    $member->vehicles()->create([
                        'plat_number' => $vehicle['platNumber'],
                        'type_vehicle_id' => $vehicle['typeVehicleId'],
                    ]);
                }

                $topUp = $member->topUps()->create([
                    'amount' => TypeMember::find($request->type_member_id)->getRawOriginal('price'),
                    'exp_date' => now()->addDays(30),
                    'user_id' => auth()->user()->id,
                ]);

                $topUp->mutation()->create([
                    'type' => 1,
                    'amount' => TypeMember::find($request->type_member_id)->getRawOriginal('price'),
                ]);
            } else {
                $member->update($request->validated());

                $member->vehicles()->delete();

                foreach ($request->vehicles as $vehicle) {
                    $member->vehicles()->create([
                        'plat_number' => $vehicle['platNumber'],
                        'type_vehicle_id' => $vehicle['typeVehicleId'],
                    ]);
                }
            }

            DB::commit();

            return back()->with('success', __('messages.success.update.member'));
        } catch (QueryException $qe) {
            DB::rollBack();

            return back()->with('error', __('messages.error.update'));
        }
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
