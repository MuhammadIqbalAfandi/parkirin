<?php

namespace App\Http\Controllers;

use App\Http\Requests\Member\StoreMemberRequest;
use App\Http\Requests\Member\UpdateMemberRequest;
use App\Models\Member;
use App\Models\TypeMember;
use App\Models\TypeVehicle;
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
                    'platNumber' => $member->plat_number,
                    'type' => $member->typeMember->type,
                    'price' => $member->typeMember->price,
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
            'typeVehicles' => TypeVehicle::get()->transform(fn($typeVehicle) => [
                'label' => $typeVehicle->type,
                'value' => $typeVehicle->id,
            ]),
            'typeMember' => Inertia::lazy(
                fn() => TypeMember::filter(request('id'))->get()->transform(fn($typeMember) => [
                    'type' => $typeMember->type,
                    'description' => $typeMember->description,
                    'price' => $typeMember->price,
                    'max' => $typeMember->max,
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
        dd(request());

        $member = Member::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'exp_date' => now()->addDays(30),
            'type_member_id' => $request->type_member_id,
        ]);

        $member->topUps()->create([
            'amount' => TypeMember::find($request->type_member_id)->getRawOriginal('price'),
            'exp_date' => now()->addDays(30),
            'user_id' => auth()->user()->id,
        ]);

        foreach ($request->vehicles as $vehicle) {
            $member->vehicles()->create([
                'plat_number' => $vehicle['platNumber'],
                'type_vehicle_id' => $vehicle['typeVehicleId'],
            ]);
        }

        return back()->with('success', __('messages.success.store.member'));
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
                'plat_number' => $member->plat_number,
                'type_member_id' => $member->type_member_id,
            ],
            'typeMembers' => TypeMember::get()->transform(fn($typeMember) => [
                'label' => $typeMember->type,
                'value' => $typeMember->id,
            ]),
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
    public function destroy($member)
    {
        $member->delete();

        return to_route('members.index')->with('success', __('messages.success.destroy.member'));
    }
}
