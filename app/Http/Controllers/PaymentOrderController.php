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
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Helpers\ApplicationHelper;
use App\Models\User;
use App\Notifications\LtpApplicationPaid;
use App\Notifications\PaymentOrderCreated;
use Barryvdh\DomPDF\Facade\Pdf;
use Faker\Provider\ar_EG\Payment;
use Illuminate\Support\Facades\Notification;

class PaymentOrderController extends Controller
{
    public function index(Request $request) {
        Gate::authorize('viewAny', PaymentOrder::class);

        $query = PaymentOrder::query()->with([
            'details'
        ]);

        if($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('order_number', 'like', '%' . $search . '%');
        }

        $paymentOrders = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.payment_order.index', [
            'paymentOrders' => $paymentOrders
        ]);
    }

    public function create(Request $request, string $id) {
        $_ltp_application = new LtpApplication;
        $_ltp_fee = new LtpFee;
        $_user = new User();

        $ltp_application_id = Crypt::decryptString($id);
        $ltp_application = LtpApplication::query()->with(["permittee.user"])->find($ltp_application_id);
        $ltp_fee = $_ltp_fee->getActiveFee();

        Gate::authorize('generatePaymentOrder', $ltp_application);

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
            '_user' => $_user,
            'signatories' => [
                'prepare' => $prepare_signatory->first(),
                'approve' => $approve_signatory->first()
            ]
        ]);
    }

    public function test() {
        $query = PaymentOrder::query()
        ->where('id', 2);

        $paymentOrder = $query->first();
        // return view('admin.payment_order.pdf', [
        //     'payment_order' => $paymentOrder,
        //     "ltp_application" => $paymentOrder->ltpApplication,
        //     "ltp_fee" => $paymentOrder->ltpFee,
        // ]);
        $view = Pdf::loadView('admin.payment_order.billing-statement-pdf', [
            'payment_order' => $paymentOrder,
            "ltp_application" => $paymentOrder->ltpApplication,
            "ltp_fee" => $paymentOrder->ltpFee,
            'approved_by' => User::find($paymentOrder->approved_by),
            'prepared_by' => User::find($paymentOrder->prepared_by),
            'oop_approved_by' => User::find($paymentOrder->oop_approved_by),
            
        ]);
        $view->setPaper('letter', 'portrait');
        // return $view->download('payment_order.pdf');
        return $view->stream('payment_order.pdf');
        // return $query->first();
    }

    public function printBillingStatementTemplate() {
        $query = PaymentOrder::query()
        ->where('id', 2);
        $paymentOrder = $query->first();
        $view = Pdf::loadView('admin.payment_order.billing-statement-pdf', [
            'payment_order' => $paymentOrder,
            "ltp_application" => $paymentOrder->ltpApplication,
            "ltp_fee" => $paymentOrder->ltpFee,
            'approved_by' => User::find($paymentOrder->approved_by),
            'prepared_by' => User::find($paymentOrder->prepared_by),
            'oop_approved_by' => User::find($paymentOrder->oop_approved_by),
        ]);
        $view->setPaper('letter', 'portrait');
        return $view->stream('payment_order.pdf');
    }

    public function printOopTemplate() {
        $query = PaymentOrder::query()
        ->with([
            'details',
            'ltpApplication.permittee.user',
            'ltpFee'
        ])
        ->where('id', 2);
        $paymentOrder = $query->first();
        // return view('admin.payment_order.oop-pdf', [
        //     'payment_order' => $paymentOrder,
        //     "ltp_application" => $paymentOrder->ltpApplication,
        //     "ltp_fee" => $paymentOrder->ltpFee,
        //     'approved_by' => User::find($paymentOrder->approved_by),
        //     'prepared_by' => User::find($paymentOrder->prepared_by),
        //     'oop_approved_by' => User::find($paymentOrder->oop_approved_by),
            
        // ]);
        $view = Pdf::loadView('admin.payment_order.oop-pdf', [
            'payment_order' => $paymentOrder,
            "ltp_application" => $paymentOrder->ltpApplication,
            "ltp_fee" => $paymentOrder->ltpFee,
            'approved_by' => User::find($paymentOrder->approved_by),
            'prepared_by' => User::find($paymentOrder->prepared_by),
            'oop_approved_by' => User::find($paymentOrder->oop_approved_by),
            
        ]);
        $view->setPaper('letter', 'landscape');
        return $view->stream('payment_order.pdf');
    }

    public function store(Request $request) {
        //
        return DB::transaction(function () use ($request) {
            //
            $request->validate([
                'ltp_fee_id' => 'required',
                'ltp_application_id' => 'required',
                'prepared_by' => 'required',
                'approved_by' => 'required',
                'oop_approved_by' => 'required'
            ]);

            $ltp_application = LtpApplication::find($request->ltp_application_id);

            Gate::authorize('generatePaymentOrder', $ltp_application);

            $year = date('Y');  
            $latest = PaymentOrder::where('year', $year)->orderBy('no', 'DESC')->first();
            $no = $latest ? $latest->no + 1 : 1;
            $bill_no = $year."-".date('m-').str_pad($no, 5, '0', STR_PAD_LEFT);

            $paymentOrder = PaymentOrder::create([
                'ltp_application_id' => $request->ltp_application_id,
                'ltp_fee_id' => $request->ltp_fee_id,
                'order_number' => $bill_no,
                'year' => $year,
                'no' => $no,
                'status' => PaymentOrder::STATUS_PENDING,
                'payment_method' => PaymentOrder::PAYMENT_METHOD_CASH,
                'issued_date' => now(),
                'prepared_by' => $request->prepared_by,
                'approved_by' => $request->approved_by,
                'oop_approved_by' => $request->oop_approved_by,
                'prepared_by_position' => User::find($request->prepared_by)->empPosition ? User::find($request->prepared_by)->empPosition->position : '',
                'approved_by_position' => User::find($request->approved_by)->empPosition ? User::find($request->approved_by)->empPosition->position : '',
                'oop_approved_by_position' => User::find($request->oop_approved_by)->empPosition ? User::find($request->oop_approved_by)->empPosition->position :'',
                'address' => $ltp_application->permittee->user->personalInfo->address,
            ]);

            $fee = LtpFee::find($request->ltp_fee_id);

            $payment_order = PaymentOrderDetail::create([
                'payment_order_id' => $paymentOrder->id,
                'item_description' => $fee->fee_name,
                'legal_basis' => $fee->legal_basis
            ]);

            $ltp_application->application_status = LtpApplication::STATUS_PAYMENT_IN_PROCESS;
            $ltp_application->save();

            LtpApplicationProgress::create([
                "ltp_application_id" => $request->ltp_application_id,
                "user_id" => auth()->user()->id,
                "status" => LtpApplicationProgress::STATUS_PAYMENT_IN_PROCESS,
                "description" => "Order of Payment has been prepared by " . auth()->user()->personalInfo->getFullNameAttribute(),
            ]);

            Notification::send($paymentOrder->ltpApplication->permittee->user, new PaymentOrderCreated($paymentOrder));
            
            return redirect()->route('paymentorder.show', ['id' => Crypt::encryptString($payment_order->id)])->with('success', 'Document downloaded successfully');
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

        return DB::transaction(function () use ($request, $id) {
            $decryptedId = Crypt::decryptString($id);
            $paymentOrder = PaymentOrder::findOrFail($decryptedId); // Fail if not found

            $filename = 'payment_order_' . $paymentOrder->id . '.pdf';
            $statement_filename = 'billing_statement_' . $paymentOrder->id . '.pdf';
            $path = $request->file('document_file')->storeAs('payment_order', $filename, 'private');
            $path2 = $request->file('billing_statement_file')->storeAs('billing_statemenet', $statement_filename, 'private');

            $paymentOrder->update(['document' => $path]);
            $paymentOrder->update(['billing_statement_doc' => $path2]);

            $ltpApplication = LtpApplication::find($paymentOrder->ltp_application_id);

            LtpApplicationProgress::create([
                "ltp_application_id" => $paymentOrder->ltp_application_id,
                "user_id" => auth()->user()->id,
                'description' => "Signed Order of Payment has been uploaded by " . auth()->user()->personalInfo->getFullNameAttribute(),
                "status" => LtpApplicationProgress::STATUS_PAYMENT_IN_PROCESS
            ]);

            return redirect()->route('paymentorder.index')->with('success', 'Document uploaded successfully');
        });
    }

    public function download(string $id, string $type) {
        $paymentOrder = PaymentOrder::query()->with([
            'details',
            'ltpApplication.permittee.user',
            'ltpFee'
        ])->find(Crypt::decryptString($id));

        if($type == 'payment_order') {
            return Storage::disk('private')->download($paymentOrder->document, 'Payment Order No. ' . $paymentOrder->order_number . '.pdf');
        }
        if($type == 'billing_statement') {
            return Storage::disk('private')->download($paymentOrder->billing_statement_doc, 'Billing Statement No. ' . $paymentOrder->order_number . '.pdf');
        }
        return Storage::disk('private')->download($paymentOrder->document, 'Payment Order No. ' . $paymentOrder->order_number . '.pdf');
        
    }
    
    public function show(string $id) {
        $_helper = new ApplicationHelper;

        Gate::authorize('viewAny', PaymentOrder::class);

        $paymentOrder = PaymentOrder::query()->with([
            'details',
            'ltpApplication.permittee.user',
            'ltpFee'
        ])->find(Crypt::decryptString($id));

        return view('admin.payment_order.show', [
            'paymentOrder' => $paymentOrder,
            '_helper' => $_helper
        ]);
    }

    public function view(string $id) {
        $paymentOrder = PaymentOrder::find(Crypt::decryptString($id));

        Gate::authorize('view', $paymentOrder);
        
        if(!Gate::allows('view-payment-order', $paymentOrder)) {
            return redirect()->back()->with('error', 'You are not authorized to view this document');
        }

        $path = storage_path('app/private/' . $paymentOrder->document);
        return response()->file($path);
    }

    public function update(Request $request, string $payment_order_id) {

        //
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:cancelled,paid',
            'or_no' => 'required_if:status,paid',
            'receipt' => 'required_if:status,paid',
            'serial_no' => 'required_if:status,paid|max:255'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'updatePayment')->withInput();
        }

        try {
            return DB::transaction(function () use ($request, $payment_order_id) {
                $paymentOrder = PaymentOrder::find(Crypt::decryptString($payment_order_id));

                Gate::authorize('updatePayment', $paymentOrder);

                $file = $request->file('receipt');

                $filename = $paymentOrder->ltp_application_id . '.' . $file->getClientOriginalExtension();

                $path = $file->storeAs('receipts', $filename, 'private');

                $paymentOrder->update([
                    'status' => $request->status,
                    'payment_reference' => $request->or_no,
                    'receipt_url' => $path,
                    'serial_number' => $request->serial_no
                ]);
                

                // update application progress
                if($request->status == PaymentOrder::STATUS_PAID) {
                    $ltpApplication = $paymentOrder->ltpApplication;
                    $ltpApplication->update([
                        'application_status' => LtpApplication::STATUS_PAID
                    ]);
                }

                LtpApplicationProgress::create([
                    "ltp_application_id" => $paymentOrder->ltp_application_id,
                    "user_id" => auth()->user()->id,
                    'description' => "Payment has been completed.",
                    "status" => LtpApplicationProgress::STATUS_PAID
                ]);

                Notification::send($paymentOrder->ltpApplication->permittee->user, new LtpApplicationPaid($paymentOrder->ltpApplication));

                return redirect()->back()->with('success', 'Payment updated successfully');
            });
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }        
    }

    public function viewReceipt(string $id) {
        try {
            $paymentOrder = PaymentOrder::find(Crypt::decryptString($id));
            $ltp_application = $paymentOrder->ltpApplication;

            Gate::authorize('viewReceipt', $paymentOrder);

            return Storage::disk('private')->response($paymentOrder->receipt_url);
            
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }   
}
