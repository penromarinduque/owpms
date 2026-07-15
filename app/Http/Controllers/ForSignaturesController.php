<?php

namespace App\Http\Controllers;

use App\Helpers\ApplicationHelper;
use App\Http\Controllers\Controller;
use App\Models\InspectionReport;
use App\Models\LtpApplication;
use App\Models\LtpApplicationProgress;
use App\Models\LtpPermit;
use App\Models\PaymentOrder;
use App\Models\Position;
use App\Models\User;
use App\Notifications\LtpPermitApproved;
use App\Notifications\PermitteeBillNotification;
use App\Notifications\SignedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelPdf\Enums\Unit;
use Spatie\LaravelPdf\Facades\Pdf;

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
        return DB::transaction(function() use ($request, $id) {
            $inspection_report_id = Crypt::decryptString($id);
            $inspection_report = InspectionReport::find($inspection_report_id);
            Gate::authorize('permitteeSign', $inspection_report);
            $signature_title = $inspection_report->id . "_ir_permittee.png";
            $signature  = $inspection_report->permittee->getSignature();
            if(!$signature) {
                return redirect()->back()->with('error', 'Signature not found.');
            }
            Storage::disk('s3')->put(
                "generated_signatures/" . $signature_title,
                Storage::disk('s3')->get("signatures/" . $signature->signature)
            );
            $inspection_report->permittee_signed = $signature_title;
            $inspection_report->save();
            Notification::send($inspection_report->inspector, new SignedNotification(route('for-signatures.index', ['type' => 'inspection_report']), 'An inspection report has been signed by the permittee and is now ready for your signature.'));
            return redirect()->route('for-signatures.index', ['type' => 'inspection_report'])->with('success', 'Inspection report signed successfully!');
        });
    }

    public function inspectionReportPermitteeSignDigital(Request $request, string $id) {
        $inspection_report_id = Crypt::decryptString($id);
        $inspection_report = InspectionReport::find($inspection_report_id);
        Gate::authorize('permitteeSign', $inspection_report);
        $ltp_application_id = $inspection_report->ltp_application_id;
        // return "test";
        $ltp_application = LtpApplication::find($ltp_application_id);

        $_helper = new ApplicationHelper;
        $_user = new User;
        $_position = new Position;

        Gate::authorize('inspect', $ltp_application);

        $inspection_report = InspectionReport::find(Crypt::decryptString($id));

        // return Pdf::view('pdfs.inspection-report', [
        //     'ltp_application' => $ltp_application,
        //     'inspection_report' => $inspection_report,
        //     '_helper' => $_helper,
        //     "_user" => $_user,
        //     "_position" => $_position
        // ])
        // ->paperSize(8.5, 13, 'in')
        // ->margins(0.3, 0, 0.3, 0, Unit::Inch)
        // ->withBrowsershot(function ($browsershot) {
        //     $browsershot->timeout(120); // Set timeout to 120 seconds
        // });

        Pdf::view('pdfs.inspection-report', [
            'ltp_application' => $ltp_application,
            'inspection_report' => $inspection_report,
            '_helper' => $_helper,
            "_user" => $_user,
            "_position" => $_position
        ])
        ->disk("s3")
        ->paperSize(8.5, 13, 'in')
        ->margins(0.3, 0, 0.3, 0, Unit::Inch)
        ->waitUntilReady()
        ->save('inspection_report/' . $inspection_report_id . '.pdf');
        
        $pdfUrl = Storage::disk('s3')->temporaryUrl(
            'inspection_report/' . $inspection_report_id . '.pdf', 
            now()->addMinutes(60),
            [
                'ResponseContentType' => 'application/pdf',
                'ResponseContentDisposition' => 'inline; filename="document.pdf"',
            ]
        );

        return redirect()->route("documents.attachSignature", [
            'id' => Crypt::encryptString($inspection_report->id), 
            "webhook" => route("webhooks.permiteeInspectionReportSigned"), 
            "pdfUrl" => $pdfUrl
        ]);
    }

    public function inspectionReportInspectorSign(Request $request, string $id) {
        return DB::transaction(function() use ($request, $id) {
            $inspection_report_id = Crypt::decryptString($id);
            $inspection_report = InspectionReport::find($inspection_report_id);
            Gate::authorize('inspectorSign', $inspection_report);
            $signature_title = $inspection_report->id . "_ir_inspector.png";
            $signature = $inspection_report->inspector->getSignature();
            if(!$signature) {
                return redirect()->back()->with('error', 'Signature not found.');
            }
            Storage::disk('s3')->put(
                "generated_signatures/" . $signature_title,
                Storage::disk('s3')->get("signatures/" . $signature->signature)
            );
            $inspection_report->inspector_signed = $signature_title;
            $inspection_report->save();
            Notification::send($inspection_report->rpsInitial, new SignedNotification(route('for-signatures.index', ['type' => 'inspection_report']), 'An inspection report has been signed by the Inspecting Officer and is now ready for your initial.'));
            return redirect()->route('for-signatures.index', ['type' => 'inspection_report'])->with('success', 'Inspection report signed successfully!');
        });
    }
    
    public function inspectionReportApproverSign(Request $request, string $id) {
        return DB::transaction(function() use ($request, $id) {
            $inspection_report_id = Crypt::decryptString($id);
            $inspection_report = InspectionReport::find($inspection_report_id);
            Gate::authorize('approverSign', $inspection_report);
            $signature_title = $inspection_report->id . "_ir_approver.png";
            $signature = $inspection_report->approver->getSignature();
            if(!$signature) {
                return redirect()->back()->with('error', 'Signature not found.');
            }
            Storage::disk('s3')->put(
                'generated_signatures/' . $signature_title,
                Storage::disk('s3')->get('signatures/' . $signature->signature)
            );
            $inspection_report->approver_signed = $signature_title;
            $permitteeUrl = Storage::disk('s3')->temporaryUrl("generated_signatures/" . $inspection_report->permittee_signed, now()->addMinutes(30));
            $inspectorUrl = Storage::disk('s3')->temporaryUrl("generated_signatures/" . $inspection_report->inspector_signed, now()->addMinutes(30));
            $approverUrl = Storage::disk('s3')->temporaryUrl("generated_signatures/" . $inspection_report->approver_signed, now()->addMinutes(30));
            Pdf::view('pdfs.inspection-report', [
                "ltp_application" => $inspection_report->ltpApplication,
                "inspection_report" => $inspection_report,
                "permitteeUrl" => $permitteeUrl,
                "inspectorUrl" => $inspectorUrl,
                "approverUrl" => $approverUrl
            ])
            ->waitUntilReady()
            ->margins(0.3, 0, 0.3, 0, Unit::Inch)
            ->disk("s3")
            ->save("inspection_report/" . $inspection_report->id . ".pdf");
            $inspection_report->save();
            Notification::send($inspection_report->ltpApplication->ioUser, new SignedNotification(route('ltpapplication.index', ['status' => LtpApplication::STATUS_INSPECTED, 'category' => 'accepted']), 'An inspection report has been signed by the Approver and is now ready for LTP creation.'));
            return redirect()->route('for-signatures.index', ['type' => 'inspection_report'])->with('success', 'Inspection report signed successfully!');
        });
    }

    public function inspectionReportRpsSign(Request $request, string $id) {
        $inspection_report_id = Crypt::decryptString($id);
        $inspection_report = InspectionReport::find($inspection_report_id);
        Gate::authorize('rpsSign', $inspection_report);
        $inspection_report->rps_signed = true;
        $inspection_report->save();
        Notification::send($inspection_report->approver, new SignedNotification(route('for-signatures.index', ['type' => 'inspection_report']), 'An inspection report has been reviewed by the RPS In Charge and is now ready for your approval.'));
        return redirect()->route('for-signatures.index', ['type' => 'inspection_report'])->with('success', 'Inspection report signed successfully!');
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
                'description' => 'Local Transport Permit has been reviewed and initialed by Chief TSD ' . auth()->user()->personalInfo->getFullNameAttribute()
            ]);
            Notification::send($ltpPermit->chiefTsd, new SignedNotification(route('for-signatures.index', ['type' => 'ltp']), 'A Local Transport Permit has been reviewed by the Chief TSD and is now ready for PENRO approval.'));
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
            $signature = $ltpPermit->approver->getSignature();
            if(!$signature) {
                return redirect()->back()->with('error', 'Signature not found.');
            }
            $signatureUrl = Storage::disk('s3')->temporaryUrl("signatures/" . $signature->signature, now()->addMinutes(30));
            Pdf::view('pdfs.permit', [
                "permit" => $ltpPermit,
                "ltp_application" => $ltpApplication,
                "signatureUrl" => $signatureUrl
            ])
            ->waitUntilReady()
            ->headerView('pdfs.header')
            ->footerView('pdfs.footer')
            ->margins(1.3, 0.5, 1.3, 0.5, Unit::Inch)
            ->disk("s3")
            ->save("ltps/" . $ltpPermit->permit_number . ".pdf");
            
            $ltpPermit->save();

            $ltpApplication->application_status = LtpApplication::STATUS_APPROVED;

            $ltpApplication->save();

            LtpApplicationProgress::create([
                'ltp_application_id' => $ltpApplication->id,
                'user_id' => auth()->user()->id,
                'status' => LtpApplication::STATUS_APPROVED,
                'remarks' => 'Local Transport Permit has been approved by PENRO ' . auth()->user()->personalInfo->getFullNameAttribute()
            ]);
    
            $recordsOfficers = User::whereHas('userRoles', function ($query) {
                $query->whereHas('role', function ($query) {
                    $query->whereHas('rolePermissions', function ($query) {
                        $query->where('permission', 'LTP_APPLICATION_RELEASE');
                    });
                });
            })->get();
            Notification::send($recordsOfficers, new SignedNotification(route('ltps.index', ['type' => 'ltp']), 'A Local Transport Permit has been approved by PENRO and is now ready for release.'));
            Notification::send($ltpApplication->permittee->user, new LtpPermitApproved($ltpPermit));
            return redirect()->route('for-signatures.index', ['type' => 'ltp'])->with('success', 'LTP permit signed successfully!');
        });
    }

    public function paymentOrderPreparerSign(Request $request, string $id) {
        return DB::transaction(function () use ($request, $id) {
            $payment_order_id = Crypt::decryptString($id);
            $payment_order = PaymentOrder::find($payment_order_id);
            Gate::authorize('preparerSign', $payment_order);
            $signature_title = $payment_order->id . "_prepared_by.png";
            Storage::disk('s3')->put(
                "generated_signatures/" . $signature_title, 
                Storage::disk('s3')->get("signatures/" . $payment_order->preparedBy->getSignature()->signature)
            );
            $payment_order->prepared_signed = $signature_title;
            $payment_order->save();
            Notification::send($payment_order->approvedBy, new SignedNotification(route('for-signatures.index', ['type' => 'billing_statement']), 'An Assessment of Fees and Charges has been signed by the preparer and is now ready for your approval.'));
            return redirect()->route('for-signatures.index', ['type' => 'payment_order'])->with('success', 'Payment order signed successfully!');
        });
    }

    public function paymentOrderApproverSign(Request $request, string $id) {
        return DB::transaction(function () use ($request, $id) {
            $payment_order_id = Crypt::decryptString($id);
            $payment_order = PaymentOrder::find($payment_order_id);
            Gate::authorize('approverSign', $payment_order);
            $signature_title = $payment_order->id . "_approved_by.png";
            Storage::disk('s3')->put(
                "generated_signatures/" . $signature_title, 
                Storage::disk('s3')->get("signatures/" . $payment_order->preparedBy->getSignature()->signature)
            );
            $payment_order->approved_signed = $signature_title;
            $prepared_by = $payment_order->preparedBy;
            $approved_by = $payment_order->approvedBy;
            $approvedByUrl = Storage::disk('s3')->temporaryUrl('generated_signatures/' . $signature_title, now()->addMinutes(30));
            $preparedByUrl = Storage::disk('s3')->temporaryUrl('generated_signatures/' . $payment_order->prepared_signed, now()->addMinutes(30));
            Pdf::view('pdfs.billing-statement-pdf', [
                "ltp_application" => $payment_order->ltpApplication,
                'payment_order' => $payment_order,
                'approvedByUrl' => $approvedByUrl,
                'preparedByUrl' => $preparedByUrl,
                "ltp_fee" => $payment_order->ltpFee,
                "prepared_by" => $prepared_by,
                "approved_by" => $approved_by
            ])
            ->waitUntilReady()
            ->disk('s3')
            ->save("billing_statemenet/billing_statement_" . $payment_order->id . ".pdf");
            $payment_order->save();
            $serialNoEncoders = User::whereHas('userRoles', function ($query) {
                $query->whereHas('role', function ($query) {
                    $query->whereHas('rolePermissions', function ($query) {
                        $query->where('permission', 'PAYMENT_ORDERS_ENCODE_SERIAL_NO');
                    });
                });
            })->get();
            Notification::send($serialNoEncoders, new SignedNotification(route('paymentorder.show', ['id' => Crypt::encryptString($payment_order->id)]), 'An Assessment of Fees and Charges has been approved by the approver and is now ready for serial number encoding.'));
            return redirect()->route('for-signatures.index', ['type' => 'payment_order'])->with('success', 'Payment order signed successfully!');
        });
    }

    public function paymentOrderOopApproverSign(Request $request, string $id) {
        return DB::transaction(function () use ($request, $id) {
            $payment_order_id = Crypt::decryptString($id);
            $payment_order = PaymentOrder::find($payment_order_id);
            Gate::authorize('oopApproverSign', $payment_order);
            $signature_title = $payment_order->id . "_oop_approved_by.png";
            Storage::disk('s3')->put(
                "generated_signatures/" . $signature_title, 
                Storage::disk('s3')->get("signatures/" . $payment_order->oopApprovedBy->getSignature()->signature)
            );
            $oopApproverUrl = Storage::disk('s3')->temporaryUrl('generated_signatures/' . $signature_title, now()->addMinutes(30));
            Pdf::view('pdfs.oop-pdf', [
                "ltp_application" => $payment_order->ltpApplication,
                'payment_order' => $payment_order,
                'oopApproverUrl' => $oopApproverUrl,
                "ltp_fee" => $payment_order->ltpFee,
                "oop_approved_by" => $payment_order->oopApprovedBy
            ])
            ->paperSize(8.5, 13, 'in')
            ->landscape()
            ->waitUntilReady()
            ->disk('s3')
            ->save("payment_order/payment_order_" . $payment_order->id . ".pdf");
            $payment_order->oop_approved_signed = $signature_title;
            $payment_order->save();
            
            // notify
            $inspectors = User::whereHas('userRoles', function ($query) {
                $query->whereHas('role', function ($query) {
                    $query->whereHas('rolePermissions', function ($query) {
                        $query->where('permission', 'PAYMENT_ORDERS_CREATE');
                    });
                });
            })->get();
            $cashiers = User::whereHas('userRoles', function ($query) {
                $query->whereHas('role', function ($query) {
                        $query->whereHas('rolePermissions', function ($query) {
                            $query->where('permission', 'PAYMENT_ORDERS_UPDATE');
                        });
                    });
                })->get();
            Notification::send($cashiers, new \App\Notifications\OopApproved($payment_order));
            // Notification::send($inspectors, new SignedNotification(route('paymentorder.show', Crypt::encryptString($payment_order->id)), 'Order of Payment has been approved by '. $payment_order->oopApprovedBy->personalInfo->getFullNameAttribute() . ' and is now ready for uploading.'));
            Notification::send($payment_order->ltpApplication->permittee->user, new PermitteeBillNotification($payment_order));
            
            return redirect()->route('for-signatures.index', ['type' => 'payment_order'])->with('success', 'Payment order signed successfully!');
        });
    }

    private function getDocuments($type) {
        if($type == 'inspection_report') {
            return InspectionReport::pendingSignaturesFor(auth()->user()->id)->paginate(50);
        }

        if($type == "ltp") {
            return LtpPermit::pendingSignaturesFor(auth()->user()->id)->paginate(50);
        }

        if($type == "billing_statement") {
            return PaymentOrder::pendingBillingStatementSignaturesFor(auth()->user()->id)->paginate(50);
        }
        if($type == "payment_order") {
            return PaymentOrder::pendingOopSignaturesFor(auth()->user()->id)->paginate(50);
        }

        return [];
        
    }
}
