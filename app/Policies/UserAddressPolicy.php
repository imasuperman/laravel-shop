<?php

namespace App\Policies;

use App\User;
use App\Models\UserAddress;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserAddressPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the user address.
     *
     * @param  \App\User  $user
     * @param  \App\Models\UserAddress  $userAddress
     * @return mixed
     */
    public function view(User $user, UserAddress $userAddress)
    {
        //
    }

    /**
     * Determine whether the user can create user addresses.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the user address.
     *
     * @param  \App\User  $user
     * @param  \App\Models\UserAddress  $userAddress
     * @return mixed
     */
    public function update(User $user, UserAddress $userAddress)
    {
        return $user->id == $userAddress->user_id;
    }

    /**
     * Determine whether the user can delete the user address.
     *
     * @param  \App\User  $user
     * @param  \App\Models\UserAddress  $userAddress
     * @return mixed
     */
    public function delete(User $user, UserAddress $userAddress)
    {
        return $user->id == $userAddress->user_id;
    }

    /**
     * Determine whether the user can restore the user address.
     *
     * @param  \App\User  $user
     * @param  \App\Models\UserAddress  $userAddress
     * @return mixed
     */
    public function restore(User $user, UserAddress $userAddress)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the user address.
     *
     * @param  \App\User  $user
     * @param  \App\Models\UserAddress  $userAddress
     * @return mixed
     */
    public function forceDelete(User $user, UserAddress $userAddress)
    {
        //
    }
}
