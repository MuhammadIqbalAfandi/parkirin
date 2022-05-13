<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Mutation;
use App\Models\TopUp;
use App\Models\TypeMember;
use App\Models\TypeVehicle;
use App\Services\MutationService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $member = Member::get();

        $typeMember = TypeMember::get();

        $typeVehicle = TypeVehicle::get();

        $mutation = Mutation::whereYear('created_at', date('Y'))
            ->get()
            ->groupBy([
                fn($mutation) => $mutation->type,
                fn($mutation) => Carbon::parse($mutation->getRawOriginal('created_at'))->format('M'),
            ]);

        $topUp = TopUp::get()->groupBy('member_id');
        return inertia('home/Index.vue', [
            'cardStatistics' => [
                //  [
                //     'title' => ...,
                //     'icon' => ...',
                //     'amount' => ...,
                //     'amountLabel' => ...,
                //     'value' => ...,
                // ],
                [
                    'title' => __('words.member'),
                    'icon' => 'pi pi-id-card',
                    'amount' => $member->count(),
                    'amountLabel' => __('words.total'),
                ],
                [
                    'title' => __('words.type_member'),
                    'icon' => 'pi pi-id-card',
                    'amount' => $typeMember->count(),
                    'amountLabel' => __('words.total'),
                ],
                [
                    'title' => __('words.type_vehicle'),
                    'icon' => 'pi pi-car',
                    'amount' => $typeVehicle->count(),
                    'amountLabel' => __('words.total'),
                ],
            ],
            'barStatistics' => [
                [
                    'title' => __('words.mutation_statistic'),
                    'description' => __('words.per_year') . ' ' . date('Y'),
                    'data' => (new MutationService)->statistic($mutation),
                ],
            ],
        ]);
    }
}
