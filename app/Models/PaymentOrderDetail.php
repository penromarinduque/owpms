<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentOrderDetail extends Model
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $table = 'payment_order_details';
    protected $guarded = [];
    
    public function paymentOrder() {
        return $this->belongsTo(PaymentOrder::class);
    }
}
