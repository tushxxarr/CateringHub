<?php

namespace App\Policies;

use App\Models\FoodItem;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FoodItemPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FoodItem  $foodItem
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, FoodItem $foodItem)
    {
        return $user->isMerchant() && $user->merchantProfile->id === $foodItem->merchant_id;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FoodItem  $foodItem
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, FoodItem $foodItem)
    {
        return $user->isMerchant() && $user->merchantProfile->id === $foodItem->merchant_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FoodItem  $foodItem
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, FoodItem $foodItem)
    {
        return $user->isMerchant() && $user->merchantProfile->id === $foodItem->merchant_id;
    }
}
