<?php

namespace App\Http\Controllers;

use App\Http\Requests\TopUp\StoreTopUpRequest;
use App\Http\Requests\TopUp\UpdateTopUpRequest;
use App\Models\Member;
use App\Models\TopUp;
use App\Models\TypeMember;
use Inertia\Inertia;

class TopUpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return inertia('topup/Index', [
            'filters' => request()->all('startDate', 'endDate', 'search'),
            'topUp' => TopUp::filter(request()->only('startDate', 'endDate', 'search'))
                ->latest()
                ->paginate(10)
                ->withQueryString()
                ->through(fn($topUp) => [
                    'updatedAt' => $topUp->updated_at,
                    'name' => $topUp->member->name,
                    'phone' => $topUp->member->phone,
                    'platNumber' => $topUp->member->plat_number,
                    'balance' => $topUp->balance,
                    'expDate' => $topUp->exp_date,
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
        return inertia('topup/Create', [
            'typeMembers' => TypeMember::get()->transform(fn($typeMember) => [
                'label' => $typeMember->type,
                'value' => $typeMember->id,
            ]),
            'members' => Inertia::lazy(
                fn() => Member::filter(request()->only('search'))->latest()->get()->transform(fn($member) => [
                    'id' => $member->id,
                    'name' => $member->name,
                    'phone' => $member->phone,
                    'platNumber' => $member->plat_number,
                ])
            ),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTopUpRequest $request)
    {
        TopUp::create([
            'balance' => $request->balance,
            'exp_date' => $request->exp_date,
            'member_id' => $request->member_id,
            'user_id' => auth()->user()->id,
        ]);

        return back()->with('success', __('messages.success.store.top_up'));
    }

    /**
     * Display the specified resource.
     *
     * @param  TopUp  $topUp
     * @return \Inertia\Response
     */
    public function show(TopUp $topUp)
    {
        return inertia('topup/Show', [
            'topUp' => [
                'updatedAt' => $topUp->updated_at,
                'name' => $topUp->member->name,
                'phone' => $topUp->member->phone,
                'platNumber' => $topUp->member->plat_number,
                'balance' => $topUp->balance,
                'expDate' => $topUp->exp_date,
                'user' => [
                    'name' => $topUp->user->name,
                    'phone' => $topUp->user->phone,
                    'email' => $topUp->user->email,
                ],
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  TopUp  $topUp
     * @return \Inertia\Response
     */
    public function edit(TopUp $topUp)
    {
        return inertia('topup/Index', [
            'topUp' => [
                'balance' => $topUp->balance,
                'exp_date' => $topUp->balance,
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  TopUp $topUp
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTopUpRequest $request, TopUp $topUp)
    {
        $topUp->update($request->validated());

        return back()->with('success', __('messages.success.update.top_up'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  TopUp $topUp
     * @return \Illuminate\Http\Response
     */
    public function destroy(TopUp $topUp)
    {
        $topUp->delete();

        return to_route('users.index')->with('success', __('messages.success.destroy.top_up'));
    }
}
