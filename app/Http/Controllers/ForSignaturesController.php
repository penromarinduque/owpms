<?php

namespace App\Http\Controllers;

use App\Helpers\ApplicationHelper;
use App\Http\Controllers\Controller;
use App\Models\InspectionReport;
use App\Models\LtpApplication;
use App\Models\LtpApplicationProgress;
use App\Models\LtpPermit;
use App\Models\PaymentOrder;
use App\Notifications\LtpPermitApproved;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;

class ForSignaturesController extends Controller
{

    //
    public function index(Request $request) {
        $_helper = new ApplicationHelper;
        $type = $request->input('type');
        
        $docs = $this->getDocuments($type);
        
        return view('for_signatures.index', [
            'docs' => $docs,
            "_helper" => $_helper
        ]);
    }

    public function inspectionReportPermitteeSign(Request $request, string $id) {
        $inspection_report_id = Crypt::decryptString($id);
        $inspection_report = InspectionReport::find($inspection_report_id);
        Gate::authorize('permitteeSign', $inspection_report);
        $inspection_report->permittee_signed = true;
        $inspection_report->save();
        return redirect()->route('for-signatures.index', ['type' => 'inspection_report'])->with('success', 'Inspection report signed successfully!');
    }

    public function inspectionReportInspectorSign(Request $request, string $id) {
        $inspection_report_id = Crypt::decryptString($id);
        $inspection_report = InspectionReport::find($inspection_report_id);
        Gate::authorize('inspectorSign', $inspection_report);
        $inspection_report->inspector_signed = true;
        $inspection_report->save();
        return redirect()->route('for-signatures.index', ['type' => 'inspection_report'])->with('success', 'Inspection report signed successfully!');
    }
    
    public function inspectionReportApproverSign(Request $request, string $id) {
        $inspection_report_id = Crypt::decryptString($id);
        $inspection_report = InspectionReport::find($inspection_report_id);
        Gate::authorize('approverSign', $inspection_report);
        $inspection_report->approver_signed = true;
        $inspection_report->save();
        return redirect()->route('for-signatures.index', ['type' => 'inspection_report'])->with('success', 'Inspection report signed successfully!');
    }

    public function ltpChiefRpsSign(Request $request, string $id) {
        $ltpPermit = LtpPermit::find(Crypt::decryptString($id));
        $ltpApplication = $ltpPermit->ltpApplication;
        Gate::authorize('chiefRpsSign', $ltpPermit);
        return DB::transaction(function () use ($ltpPermit, $ltpApplication) {
            $ltpPermit->chief_rps_signed = true;
            $ltpPermit->save();
            LtpApplicationProgress::create([
                'ltp_application_id' => $ltpApplication->id,
                'user_id' => auth()->user()->id,
                'status' => $ltpApplication->application_status,
                'remarks' => 'Local Transport Permit has been reviewed and initialed by Chief TSD ' . auth()->user()->personalInfo->getFullNameAttribute()
            ]);
            return redirect()->route('for-signatures.index', ['type' => 'ltp'])->with('success', 'LTP permit signed successfully!');
        });
    }

    public function ltpChiefTsdSign(Request $request, string $id) {
        $ltpPermit = LtpPermit::find(Crypt::decryptString($id));
        $ltpApplication = $ltpPermit->ltpApplication;
        Gate::authorize('chiefTsdSign', $ltpPermit);
        return DB::transaction(function () use ($ltpPermit, $ltpApplication) {
            $ltpPermit->chief_tsd_signed = true;
            $ltpPermit->save();
            LtpApplicationProgress::create([
                'ltp_application_id' => $ltpApplication->id,
                'user_id' => auth()->user()->id,
                'status' => $ltpApplication->application_status,
                'remarks' => 'Local Transport Permit has been reviewed and initialed by Chief TSD ' . auth()->user()->personalInfo->getFullNameAttribute()
            ]);
            return redirect()->route('for-signatures.index', ['type' => 'ltp'])->with('success', 'LTP permit signed successfully!');
        });
    }

    public function ltpPenroSign(Request $request, string $id) {
        $ltpPermit = LtpPermit::find(Crypt::decryptString($id));
        $ltpApplication = $ltpPermit->ltpApplication;
        Gate::authorize('penroSign', $ltpPermit);
        return DB::transaction(function () use ($ltpPermit, $ltpApplication) {
            $ltpPermit->penro_signed = true;
            $ltpPermit->approved_at = now();
            $ltpPermit->save();

            $ltpApplication->application_status = LtpApplication::STATUS_APPROVED;
            $ltpApplication->save();

            LtpApplicationProgress::create([
                'ltp_application_id' => $ltpApplication->id,
                'user_id' => auth()->user()->id,
                'status' => LtpApplication::STATUS_APPROVED,
                'remarks' => 'Local Transport Permit has been approved by PENRO ' . auth()->user()->personalInfo->getFullNameAttribute()
            ]);
    
            Notification::send($ltpApplication->permittee->user, new LtpPermitApproved($ltpPermit));
    
            return redirect()->route('for-signatures.index', ['type' => 'ltp'])->with('success', 'LTP permit signed successfully!');
        });
    }

    public function paymentOrderPreparerSign(Request $request, string $id) {
        $payment_order_id = Crypt::decryptString($id);
        $payment_order = PaymentOrder::find($payment_order_id);
        Gate::authorize('preparerSign', $payment_order);
        $payment_order->prepared_signed = true;
        $payment_order->save();
        return redirect()->route('for-signatures.index', ['type' => 'payment_order'])->with('success', 'Payment order signed successfully!');
    }

    public function paymentOrderApproverSign(Request $request, string $id) {
        $payment_order_id = Crypt::decryptString($id);
        $payment_order = PaymentOrder::find($payment_order_id);
        Gate::authorize('approverSign', $payment_order);
        $payment_order->approved_signed = true;
        $payment_order->save();
        return redirect()->route('for-signatures.index', ['type' => 'payment_order'])->with('success', 'Payment order signed successfully!');
    }

    private function getDocuments($type) {
        if($type == 'inspection_report') {
            return InspectionReport::pendingSignaturesFor(auth()->user()->id)->paginate(50);
        }

        if($type == "ltp") {
            return LtpPermit::pendingSignaturesFor(auth()->user()->id)->paginate(50);
        }

        if($type == "payment_order") {
            return PaymentOrder::pendingSignaturesFor(auth()->user()->id)->paginate(50);
        }

        return [];
        
    }
}
