<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Order $order)
    {
        if ($user->isMerchant()) {
            return $user->merchantProfile->id === $order->merchant_id;
        }

        if ($user->isCustomer()) {
            return $user->customerProfile->id === $order->customer_id;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Order $order)
    {
        return $user->isMerchant() && $user->merchantProfile->id === $order->merchant_id;
    }

    /**
     * Determine whether the user can cancel the order.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function cancel(User $user, Order $order)
    {
        return $user->isCustomer() &&
            $user->customerProfile->id === $order->customer_id &&
            $order->status === 'pending';
    }
}
