<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PaymentOrder extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'payment_orders';
    protected $primaryKey = 'id';

    protected $guarded = [];

    // 'cash','linkbiz','gcash','paymaya','bank-transfer','others'
    const PAYMENT_METHOD_CASH = 'cash';
    const PAYMENT_METHOD_LINKBIZ = 'linkbiz';
    const PAYMENT_METHOD_GCASH = 'gcash';
    const PAYMENT_METHOD_PAYMAYA = 'paymaya';
    const PAYMENT_METHOD_BANK_TRANSFER = 'bank-transfer';
    const PAYMENT_METHOD_OTHERS = 'others';

    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_CANCELLED = 'cancelled';

    protected $casts = [
        'issued_date' => 'datetime'
    ];


    public function details() {
        return $this->hasMany(PaymentOrderDetail::class);
    }

    public function ltpFee() {
        return $this->belongsTo(LtpFee::class);
    }

    public function ltpApplication() {
        return $this->belongsTo(LtpApplication::class);
    }

    public function preparedBy() {
        return $this->belongsTo(User::class, 'prepared_by', 'id');
    }

    public function approvedBy() {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }

    public function scopePendingOopSignaturesFor($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('prepared_signed', true)
            ->where('approved_signed', true)
            ->where('oop_approved_by', $userId)
            ->where(function ($q)  {
                $q->whereNull('oop_approved_signed')
                ->orWhere('oop_approved_signed', false);
            });
        });
    }

    public function scopePendingBillingStatementSignaturesFor($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            // Preparer: Hasn't signed yet (null or false)
            $q->where('prepared_by', $userId)
            ->where(function ($subQ) {
                $subQ->whereNull('prepared_signed')
                    ->orWhere('prepared_signed', false);
            });
        })->orWhere(function ($q) use ($userId) {
            // Approver: Only if preparer has signed (true), but approver hasn't signed yet
            $q->where('approved_by', $userId)
            ->where(function ($subQ) {
                 $subQ->whereNull('approved_signed')
                    ->orWhere('approved_signed', false);
            })
            ->where('prepared_signed', true);
        });
    }

    


}
