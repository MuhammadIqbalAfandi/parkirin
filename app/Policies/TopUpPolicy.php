<?php

namespace App\Policies;

use App\Models\TopUp;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TopUpPolicy
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
     * @param  \App\Models\TopUp  $topUp
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, TopUp $topUp)
    {
        return $user->role_id !== 1;
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
     * @param  \App\Models\TopUp  $topUp
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, TopUp $topUp)
    {
        return $user->role_id === 3;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TopUp  $topUp
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, TopUp $topUp)
    {
        return $user->role_id === 3;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TopUp  $topUp
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, TopUp $topUp)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TopUp  $topUp
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, TopUp $topUp)
    {
        //
    }
}
