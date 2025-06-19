<?php

namespace App\Policies;

use App\Models\PaymentOrder;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PaymentOrderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array('PAYMENT_ORDERS_INDEX', $user->getUserPermissions());
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewIssuedOr(User $user): bool
    {
        return in_array('ISSUED_OR_INDEX', $user->getUserPermissions());
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PaymentOrder $paymentOrder): bool
    {
        return $user->id == $paymentOrder->ltpApplication->permittee->user_id || in_array('PAYMENT_ORDERS_INDEX', $user->getUserPermissions());
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PaymentOrder $paymentOrder): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PaymentOrder $paymentOrder): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PaymentOrder $paymentOrder): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PaymentOrder $paymentOrder): bool
    {
        return false;
    }

    public function approverSign(User $user, PaymentOrder $paymentOrder): bool
    {
        return $paymentOrder->prepared_signed && $user->id == $paymentOrder->approved_by && in_array($paymentOrder->approved_signed, [null, false]) ;
    }

    public function preparerSign(User $user, PaymentOrder $paymentOrder): bool
    {
        return $user->id == $paymentOrder->prepared_by && in_array($paymentOrder->prepared_signed, [null, false]);
    }

    public function uploadDocument(User $user, PaymentOrder $paymentOrder): bool
    {
        return $paymentOrder->prepared_signed && $paymentOrder->approved_signed && in_array('PAYMENT_ORDERS_UPDATE', $user->getUserPermissions());
    }

    public function updatePayment(User $user, PaymentOrder $paymentOrder): bool
    {
        return $paymentOrder->prepared_signed && $paymentOrder->approved_signed && in_array('PAYMENT_ORDERS_UPDATE', $user->getUserPermissions()) && $paymentOrder->document;
    }

    public function downloadSignedOrder(User $user, PaymentOrder $paymentOrder): bool
    {
        return $paymentOrder->prepared_signed && $paymentOrder->approved_signed && in_array('PAYMENT_ORDERS_INDEX', $user->getUserPermissions()) && $paymentOrder->document;
    }

    public function uploadSignedOrder(User $user, PaymentOrder $paymentOrder): bool
    {
        return $paymentOrder->prepared_signed && $paymentOrder->approved_signed && in_array('PAYMENT_ORDERS_INDEX', $user->getUserPermissions()) ;
    }

}
