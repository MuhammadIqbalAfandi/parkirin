<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Mutation;
use App\Models\TypeMember;
use App\Models\TypeVehicle;
use App\Models\User;
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
        $members = Member::get();

        $typeMembers = TypeMember::get();

        $typeVehicles = TypeVehicle::get();

        $users = User::get();

        $mutations = Mutation::whereYear('created_at', date('Y'))
            ->get()
            ->groupBy([
                fn($mutation) => $mutation->type,
                fn($mutation) => Carbon::parse($mutation->getRawOriginal('created_at'))->format('M')
            ]);

        return inertia('home/Index.vue', [
            'cardStatistics' => [
                //  [
                //     'title' => ...,
                //     'icon' => ...',
                //     'amount' => ...,
                //     'amountLabel' => ...,
                //     'value' => ...,
                //     'roleId' => [...,...]
                // ],
                [
                    'title' => __('words.member'),
                    'icon' => 'pi pi-id-card',
                    'amount' => $members->count(),
                    'amountLabel' => __('words.total'),
                    'roleId' => [3]
                ],
                [
                    'title' => __('words.type_member'),
                    'icon' => 'pi pi-id-card',
                    'amount' => $typeMembers->count(),
                    'amountLabel' => __('words.total'),
                    'roleId' => [2]
                ],
                [
                    'title' => __('words.type_vehicle'),
                    'icon' => 'pi pi-car',
                    'amount' => $typeVehicles->count(),
                    'amountLabel' => __('words.total'),
                    'roleId' => [3]
                ],
                [
                    'title' => __('words.user'),
                    'icon' => 'pi pi-user',
                    'amount' => $users->count(),
                    'amountLabel' => __('words.total'),
                    'roleId' => [1]
                ]
            ],
            'barStatistics' => [
                // [
                //     'title' => ...,
                //     'description' => ...,
                //     'data' => ...,
                //     'roleId' => [..., ...],
                // ],
                [
                    'title' => __('words.mutation_statistic'),
                    'description' => __('words.per_year') . ' ' . date('Y'),
                    'data' => MutationService::statistic($mutations),
                    'roleId' => [2, 3]
                ]
            ]
        ]);
    }
}
