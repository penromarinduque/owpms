<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentOrder extends Model
{
    use HasFactory;

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


}
