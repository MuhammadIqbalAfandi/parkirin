<?php

namespace App\Http\Controllers;

use App\Http\Requests\TypeMember\StoreTypeMemberRequest;
use App\Http\Requests\TypeMember\UpdateTypeMemberRequest;
use App\Models\TypeMember;

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
        return inertia('typemember/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTypeMemberRequest $request)
    {
        TypeMember::create($request->validated());

        return back()->with('success', __('messages.success.store.type_member'));
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
            ],
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
        $typeMember->update($request->validated());

        return back()->with('success', __('messages.success.update.type_member'));
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
