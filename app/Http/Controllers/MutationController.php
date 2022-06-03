<?php

namespace App\Http\Controllers;

use App\Exports\MutationExport;
use App\Models\Mutation;
use App\Services\MutationService;
use Inertia\Inertia;

class MutationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $this->authorize('viewAny', Mutation::class);

        $mutations = Mutation::filter(request()->only('startDate', 'endDate'));

        return inertia('mutation/Report', [
            'initialDateRage' => request()->collect('startDate', 'endDate')->flatten(),
            'mutations' => Inertia::lazy(
                fn() => [
                    'totalIncome' => (new MutationService)->totalIncomeAsString($mutations->get()),
                    'totalExpense' => (new MutationService)->totalExpenseAsString($mutations->get()),
                    'totalAmount' => (new MutationService)->totalAmountAsString($mutations->get()),
                    'details' => $mutations
                        ->latest()
                        ->paginate(10)
                        ->withQueryString()
                        ->through(fn($mutation) => [
                            'createdAt' => $mutation->created_at,
                            'amount' => $mutation->amount,
                            'type' => $mutation->type,
                            'outTransactionId' => $mutation->out_transaction_id,
                            'expenseId' => $mutation->expense_id,
                            'topUpId' => $mutation->top_up_id,
                        ]),
                ]
            ),
        ]);
    }

    /**
     * Export to excel
     */
    public function exportExcel()
    {
        $this->authorize('viewAny', Mutation::class);

        return new MutationExport(request());
    }
}
