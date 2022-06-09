<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Expense\StoreExpenseRequest;
use App\Models\Expense;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ExpenseController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Expense::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return inertia('expense/Index', [
            'initialDateRange' => request()->collect(['startDate', 'endDate'])->flatten(),
            'expenses' => Expense::filter(request()->only('startDate', 'endDate', 'outlet'))
                ->latest()
                ->paginate(10)
                ->withQueryString()
                ->through(fn($expense) => [
                    'id' => $expense->id,
                    'createdAt' => $expense->created_at,
                    'amount' => $expense->amount,
                    'user' => $expense->user->name,
                ]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return inertia('expense/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreExpenseRequest $request)
    {
        DB::beginTransaction();

        try {
            $expense = Expense::create([
                'description' => $request->description,
                'amount' => $request->amount,
                'user_id' => $request->user()->id,
            ]);

            $expense->mutation()->create([
                'type' => 2,
                'amount' => $expense->getRawOriginal('amount'),
            ]);

            DB::commit();

            return back()->with('success', __('messages.success.store.expense'));
        } catch (QueryException $e) {

            DB::rollBack();

            return back()->with('error', __('messages.error.store.expense'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Expense  $expense
     * @return \Inertia\Response
     */
    public function show(Expense $expense)
    {
        return inertia('expense/Show', [
            'expense' => [
                'created_at' => $expense->created_at,
                'amount' => $expense->amount,
                'description' => $expense->description,
                'user' => [
                    'name' => $expense->user->name,
                    'phone' => $expense->user->phone,
                    'email' => $expense->user->email,
                ],
            ],
        ]);
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
