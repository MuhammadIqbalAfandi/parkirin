<?php

namespace App\Providers;

use App\Models\Expense;
use App\Models\Member;
use App\Models\Mutation;
use App\Models\OutTransaction;
use App\Models\ParkingFee;
use App\Models\TopUp;
use App\Models\TypeMember;
use App\Models\TypeVehicle;
use App\Models\User;
use App\Policies\ExpensePolicy;
use App\Policies\MemberPolicy;
use App\Policies\MutationPolicy;
use App\Policies\OutTransactionPolicy;
use App\Policies\ParkingFeePolicy;
use App\Policies\TopUpPolicy;
use App\Policies\TypeMemberPolicy;
use App\Policies\TypeVehiclePolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Class::class => ClassPolicy::class,
        User::class => UserPolicy::class,
        Expense::class => ExpensePolicy::class,
        Member::class => MemberPolicy::class,
        Mutation::class => MutationPolicy::class,
        ParkingFee::class => ParkingFeePolicy::class,
        TopUp::class => TopUpPolicy::class,
        TypeMember::class => TypeMemberPolicy::class,
        TypeVehicle::class => TypeVehiclePolicy::class,
        OutTransaction::class => OutTransactionPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
