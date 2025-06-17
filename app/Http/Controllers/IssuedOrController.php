<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PaymentOrder;
use Illuminate\Http\Request;

class IssuedOrController extends Controller
{
    //
    public function index()
    {
        $payment_orders = PaymentOrder::where([
            'status' => PaymentOrder::STATUS_PAID
        ])
        ->paginate(50);

        return view('admin.payment_order.issued-or', [
            'payment_orders' => $payment_orders
        ]);
    }
}
