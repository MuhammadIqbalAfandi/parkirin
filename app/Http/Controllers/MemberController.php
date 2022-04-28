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
            'filters' => request()->all('search'),
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
                        'max' => $maxVehicle->max,
                    ]),
                ])->first()
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
        } catch (QueryException $e) {
            DB::rollBack();

            return back()->with('success', __('messages.error.store.member'));
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
        return inertia('member/Edit', [
            'member' => [
                'id' => $member->id,
                'name' => $member->name,
                'phone' => $member->phone,
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
        $member->update($request->validated());

        return back()->with('success', __('messages.success.update.member'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Member  $member
     * @return \Illuminate\Http\Response
     */
    public function destroy(Member $member)
    {
        $member->delete();

        return to_route('members.index')->with('success', __('messages.success.destroy.member'));
    }
}
