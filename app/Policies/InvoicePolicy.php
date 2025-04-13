<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvoicePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Invoice $invoice)
    {
        if ($user->isMerchant()) {
            return $user->merchantProfile->id === $invoice->order->merchant_id;
        }

        if ($user->isCustomer()) {
            return $user->customerProfile->id === $invoice->order->customer_id;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Invoice $invoice)
    {
        return $user->isMerchant() && $user->merchantProfile->id === $invoice->order->merchant_id;
    }

    /**
     * Determine whether the user can mark the invoice as paid.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function markAsPaid(User $user, Invoice $invoice)
    {
        return $user->isCustomer() &&
            $user->customerProfile->id === $invoice->order->customer_id &&
            $invoice->status === 'pending';
    }
}
