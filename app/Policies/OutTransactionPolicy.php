<?php

namespace App\Policies;

use App\Models\OutTransaction;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OutTransactionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->role_id === 3;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OutTransaction  $outTransaction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, OutTransaction $outTransaction)
    {
        return $user->role_id === 3;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->role_id === 3;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OutTransaction  $outTransaction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, OutTransaction $outTransaction)
    {
        return $user->role_id === 3;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OutTransaction  $outTransaction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, OutTransaction $outTransaction)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OutTransaction  $outTransaction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, OutTransaction $outTransaction)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OutTransaction  $outTransaction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, OutTransaction $outTransaction)
    {
        //
    }
}
