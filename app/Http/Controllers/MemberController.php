<?php

namespace App\Http\Controllers;

use App\Http\Requests\Member\StoreMemberRequest;
use App\Http\Requests\Member\UpdateMemberRequest;
use App\Models\Member;
use App\Models\TypeMember;

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
        Member::create($request->validated());

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
