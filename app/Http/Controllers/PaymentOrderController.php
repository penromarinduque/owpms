<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LtpApplication;
use App\Models\LtpFee;
use Illuminate\Support\Facades\Crypt;

class PaymentOrderController extends Controller
{
    public function create(Request $request, string $id) {
        $_ltp_application = new LtpApplication;
        $_ltp_fee = new LtpFee;

        $ltp_application_id = Crypt::decryptString($id);
        $ltp_application = LtpApplication::query()->with(["permittee.user"])->find($ltp_application_id);
        $ltp_fee = $_ltp_fee->getActiveFee();

        if(!$ltp_fee) {
            return redirect()->back()->with('error', 'No active fee found!');
        }

        return view('admin.payment_order.create', [
            '_ltp_application' => $_ltp_application,
            'ltp_application' => $ltp_application,
            'ltp_fee' => $ltp_fee
        ]);
    }
}
