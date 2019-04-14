<?php

namespace App\Policies;

use App\Models\Order;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;


    public function own(User $user, Order $order)
    {
        return $order->user_id == $user->id;
    }
}
