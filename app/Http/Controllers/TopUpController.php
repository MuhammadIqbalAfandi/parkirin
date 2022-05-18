<?php

namespace App\Http\Controllers;

use App\Http\Requests\TopUp\StoreTopUpRequest;
use App\Http\Requests\TopUp\UpdateTopUpRequest;
use App\Models\Member;
use App\Models\TopUp;
use App\Models\TypeMember;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class TopUpController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(TopUp::class);
    }

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
                    'id' => $topUp->id,
                    'updatedAt' => $topUp->updated_at,
                    'name' => $topUp->member->name,
                    'phone' => $topUp->member->phone,
                    'platNumber' => $topUp->member->vehicleDetail(),
                    'amount' => $topUp->amount,
                    'type' => $topUp->member->typeMember->type,
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
            'members' => Inertia::lazy(
                fn() => Member::filter(request()->only('search'))->latest()->get()->transform(fn($member) => [
                    'id' => $member->id,
                    'name' => $member->name,
                    'phone' => $member->phone,
                    'platNumber' => $member->vehicleDetail(),
                    'type' => $member->typeMember->type,
                    'price' => $member->typeMember->price,
                    'expDate' => $member->exp_date,
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
        $member = Member::find($request->member_id);

        $latest = $member->topUps->last()->getRawOriginal('exp_date');

        DB::beginTransaction();

        try {
            $topUp = $member->topUps()->create([
                'amount' => TypeMember::find($member->typeMember->id)->getRawOriginal('price'),
                'exp_date' => Carbon::create($latest)->addDays(30),
                'user_id' => auth()->user()->id,
            ]);

            $topUp->mutation()->create([
                'type' => 1,
                'amount' => TypeMember::find($member->typeMember->id)->getRawOriginal('price'),
            ]);

            DB::commit();

            return back()->with('success', __('messages.success.store.top_up'));
        } catch (QueryException $qe) {
            DB::rollBack();

            return back()->with('error', __('messages.error.store.top_up'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  TopUp  $topUp
     * @return \Inertia\Response
     */
    public function show(TopUp $topUp)
    {
        $member = $topUp->member;

        return inertia('topup/Show', [
            'member' => [
                'name' => $member->name,
                'phone' => $member->phone,
            ],
            'topUp' => $member->topUps()
                ->latest()
                ->paginate(15)
                ->withQueryString()
                ->through(fn($topUp) => [
                    'id' => $topUp->id,
                    'updatedAt' => $topUp->updated_at,
                    'name' => $topUp->user->name,
                    'phone' => $topUp->user->phone,
                    'platNumber' => $topUp->member->vehicleDetail(),
                    'amount' => $topUp->amount,
                    'type' => $topUp->member->typeMember->type,
                    'expDate' => $topUp->exp_date,
                ]),
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
