<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LtpApplication;
use App\Models\PaymentOrder;
use App\Models\PaymentOrderDetail;
use App\Models\LtpFee;
use App\Models\Signatory;
use App\Models\LtpApplicationProgress;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PaymentOrderController extends Controller
{
    public function index() {
        $paymentOrders = PaymentOrder::query()->with([
            'details',
        ])
        ->paginate(20);

        return view('admin.payment_order.index', [
            'paymentOrders' => $paymentOrders
        ]);
    }

    public function create(Request $request, string $id) {
        $_ltp_application = new LtpApplication;
        $_ltp_fee = new LtpFee;

        $ltp_application_id = Crypt::decryptString($id);
        $ltp_application = LtpApplication::query()->with(["permittee.user"])->find($ltp_application_id);
        $ltp_fee = $_ltp_fee->getActiveFee();

        $prepare_signatory = Signatory::where([
            'generated_document_type_id' => 2,
            'signatory_role_id' => 1
        ]);

        $approve_signatory = Signatory::where([
            'generated_document_type_id' => 2,
            'signatory_role_id' => 2
        ]);

        if(!$ltp_fee) {
            return redirect()->back()->with('error', 'No active fee found!');
        }

        return view('admin.payment_order.create', [
            '_ltp_application' => $_ltp_application,
            'ltp_application' => $ltp_application,
            'ltp_fee' => $ltp_fee,
            'signatories' => [
                'prepare' => $prepare_signatory->first(),
                'approve' => $approve_signatory->first()
            ]
        ]);
    }

    public function store(Request $request) {
        //
        return DB::transaction(function () use ($request) {
            //
            $request->validate([
                'bill_no' => 'required|unique:payment_orders,order_number',
                'ltp_fee_id' => 'required',
                'ltp_application_id' => 'required',
                'prepared_by' => 'required',
                'approved_by' => 'required'
            ]);

            $paymentOrder = PaymentOrder::create([
                'ltp_application_id' => $request->ltp_application_id,
                'ltp_fee_id' => $request->ltp_fee_id,
                'order_number' => $request->bill_no,
                'status' => PaymentOrder::STATUS_PENDING,
                'payment_method' => PaymentOrder::PAYMENT_METHOD_CASH,
                'issued_date' => now(),
                'prepared_by' => $request->prepared_by,
                'approved_by' => $request->approved_by
            ]);

            $fee = LtpFee::find($request->ltp_fee_id);

            PaymentOrderDetail::create([
                'payment_order_id' => $paymentOrder->id,
                'item_description' => $fee->fee_name,
                'legal_basis' => $fee->legal_basis
            ]);

            LtpApplication::find($request->ltp_application_id)->update([
                'application_status' => LtpApplication::STATUS_PAYMENT_IN_PROCESS
            ]);

            LtpApplicationProgress::create([
                "ltp_application_id" => $request->ltp_application_id,
                "user_id" => auth()->user()->id,
                "status" => LtpApplicationProgress::STATUS_PAYMENT_IN_PROCESS
            ]);

            return redirect()->route('paymentorder.index')->with('success', 'Payment Order created successfully');
        });
    }

    public function print($id) {
        
        $paymentOrder = PaymentOrder::query()->with([
            'details',
            'ltpApplication.permittee.user',
            'ltpFee'
        ])->find(Crypt::decryptString($id));

        return view('admin.payment_order.print', [
            'paymentOrder' => $paymentOrder
        ]);
    }

    public function upload(Request $request, string $id)
    {
        // Validate before transaction
        // Validate with error bag
        $validator = Validator::make($request->all(), [
            'document_file' => 'required|mimes:pdf|max:1024'
        ], [], [
            'document_file' => 'document file'
        ]);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'upload')->withInput()->with('error', 'Failed to upload document');
        }

        try {
            return DB::transaction(function () use ($request, $id) {
                $decryptedId = Crypt::decryptString($id);
                $paymentOrder = PaymentOrder::findOrFail($decryptedId); // Fail if not found

                $filename = 'payment_order_' . $paymentOrder->id . '.pdf';
                $path = $request->file('document_file')->storeAs('payment_order', $filename);

                $paymentOrder->update(['document' => $path]);

                $ltpApplicatipn = LtpApplication::find($paymentOrder->ltp_application_id);
                LtpApplicationProgress::create([
                    "ltp_application_id" => $paymentOrder->ltp_application_id,
                    "user_id" => auth()->user()->id,
                    'remarks' => '<br>' . auth()->user()->personalInfo->first_name . ' ' . auth()->user()->personalInfo->last_name . ' updated the document.',
                    "status" => LtpApplicationProgress::STATUS_PAYMENT_IN_PROCESS
                ]);

                return redirect()->route('paymentorder.index')->with('success', 'Document uploaded successfully');
            });
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function download(string $id) {
        $paymentOrder = PaymentOrder::query()->with([
            'details',
            'ltpApplication.permittee.user',
            'ltpFee'
        ])->find(Crypt::decryptString($id));

        return Storage::disk('public')->download($paymentOrder->document, 'Payment Order No. ' . $paymentOrder->order_number . '.pdf');
    }

    public function show(string $id) {
        //
        $paymentOrder = PaymentOrder::query()->with([
            'details',
            'ltpApplication.permittee.user',
            'ltpFee'
        ])->find(Crypt::decryptString($id));

        return view('admin.payment_order.show', [
            'paymentOrder' => $paymentOrder
        ]);
    }

}
