<?php

namespace App\Http\Controllers;

use App\Models\InspectionReport;
use App\Models\LtpApplication;
use App\Models\LtpPermit;
use App\Models\PaymentOrder;
use App\Models\PaymentOrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MigrationController extends Controller
{
    //
    public function importLtp(Request $request)
    {
        return view('admin.data_entry.import-ltp');
    }

    public function saveLtp(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $ltpApplication = LtpApplication::where("application_no", $request->application_no)->first();
            $orderNo = $request->order_no;
            $serialNo = $request->serial_no;
            $no = $request->no;
            $paymentReference = $request->payment_reference;
            $receiptDate = $request->receipt_date;
            $permitNumber = $request->permit_number;
            $permitNoNo = $request->permit_no_no;
            $dateApproved = $request->date_approved;
            $dateReleased = $request->date_released;

            $request->validate([
                "order_no" => "required",
                "serial_no" => "required",
                "no" => "required",
                "payment_reference" => "required",
                "receipt_date" => "required",
                "permit_number" => "required",
                "permit_no_no" => "required",
                "date_approved" => "required",
                "date_released" => "required",
                "application_no" => "required"
            ]);

            $ltpApplication->update([
                "application_status" => LTPApplication::STATUS_RELEASED,
            ]);

            $paymentOrder = PaymentOrder::create([
                "ltp_application_id" => $ltpApplication->id,
                "ltp_fee_id" => 1,
                "order_number" => $orderNo,
                "serial_number" => $serialNo,
                "no" => $no,
                "year" => $request->receipt_date ? date('Y', strtotime($request->receipt_date)) : null,
                "status" => "paid",
                "payment_method" => "cash",
                "payment_reference" => $paymentReference,
                "prepared_by" => 8,
                "approved_by" => 12,
                "oop_approved_by" => 17,
                "prepared_by_position" => "OIC-Chief, RPS",
                "approved_by_position" => "OIC-Chief, TSD",
                "oop_approved_by_position" => "Accountant III",
                "issued_date" => $receiptDate,
                "payment_date" => $receiptDate
            ]);

            $paymentOrderDetails = PaymentOrderDetail::create([
                "payment_order_id" => $paymentOrder->id, 
                "item_description" => "Local Transport Permit",
                "legal_basis" => "DAO-5504"
            ]);

            $inspectionReport = InspectionReport::create([
                "ltp_application_id" => $ltpApplication->id,
                "user_id" => $ltpApplication->permittee->user_id,
                "inspector_id" => 16,
                "approver_id" => 12,
                "inspection_date" => $receiptDate,
                "approver_position" => "OIC-Chief, TSD",
                "rps_initial_id" => 8,
                "rps_signed" => 1
            ]);

            $ltp = LtpPermit::create([
                "ltp_application_id" => $ltpApplication->id,
                "permit_number" => $permitNumber,
                "no" => $permitNoNo,
                "penro" => 11,
                "approver_designation" => "PENR Officer",
                "chief_tsd" => 12,
                "chief_tsd_signed" => 1,
                "penro_signed" => 1,
                "approved_at" => $dateApproved,
                "released_date" => $dateReleased
            ]);

            return redirect()->back()->with('success', 'LTP data imported successfully.');
        });
    }
}
