<?php

namespace App\Http\Controllers;

use App\Exports\MutationExport;
use App\Models\Mutation;
use App\Services\MutationService;
use Inertia\Inertia;

class ReportMutationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $mutations = Mutation::filter(request()->only('startDate', 'endDate'));

        return inertia('mutation/Report', [
            'filters' => request()->all('startDate', 'endDate', 'outlet'),
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
                            'transactionOutId' => $mutation->transaction_out,
                            'expenseId' => $mutation->expense_id,
                            'topupId' => $mutation->top_up_id,
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
        return new MutationExport(request());
    }
}
