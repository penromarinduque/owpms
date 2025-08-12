<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PaymentOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class IssuedOrController extends Controller
{
    //
    public function index()
    {
        Gate::authorize('viewIssuedOr', PaymentOrder::class);
        $payment_orders = PaymentOrder::where([
            'status' => PaymentOrder::STATUS_PAID
        ])
        ->paginate(50);

        return view('admin.payment_order.issued-or', [
            'payment_orders' => $payment_orders
        ]);
    }
}
