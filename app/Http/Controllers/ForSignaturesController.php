<?php

namespace App\Http\Controllers;

use App\ElectronicSigratureTrait;
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
use Illuminate\Support\Facades\Log;
use Spatie\LaravelPdf\Enums\Unit;
use Spatie\LaravelPdf\Facades\Pdf;

class ForSignaturesController extends Controller
{
    use ElectronicSigratureTrait;

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
            $permitteeSignature = $this->getElectronicSignature(
                $inspection_report->permittee->personalInfo->first_name . " " . $inspection_report->permittee->personalInfo->middle_name . " " . $inspection_report->permittee->personalInfo->last_name,
                "Permittee",
                Storage::disk('s3')->temporaryUrl("signatures/" . $signature->signature, now()->addMinutes(30)),
                "Inspection Report for LTP Application #" . $inspection_report->ltpApplication->application_no,
                "8",
                $inspection_report->id,
                $inspection_report->permittee->id,
                null
            );

            // Storage::disk('s3')->put(
            //     "generated_signatures/" . $signature_title,
            //     "data:image/png;base64," . base64_encode($permitteeSignature->body())
            // );
            Storage::disk('s3')->put(
                "generated_signatures/" . $signature_title,
                // "data:image/png;base64," . base64_encode($permitteeSignature->body())
                // Storage::disk('s3')->get("signatures/" . $signature->signature)
                $permitteeSignature->body()
            );
            $inspection_report->permittee_signed = $signature_title;
            $inspection_report->save();
            LtpApplicationProgress::create([
                'ltp_application_id' => $inspection_report->ltp_application_id,
                'user_id' => auth()->user()->id,
                'status' => LtpApplication::STATUS_INSPECTED,
                'remarks' => "Inspection report has been signed by the Permittee: " . auth()->user()->personalInfo->getFullNameAttribute()
            ]);
            Notification::send($inspection_report->inspector, new SignedNotification(route('for-signatures.index', ['type' => 'inspection_report']), 'An inspection report has been signed by the permittee and is now ready for your signature.'));
            return redirect()->route('for-signatures.index', ['type' => 'inspection_report'])->with('success', 'Inspection report signed successfully!');
        });
    }

    // not in use
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
            $inspectorSignature = $this->getElectronicSignature(
                $inspection_report->inspector->personalInfo->first_name . " " . $inspection_report->inspector->personalInfo->middle_name . " " . $inspection_report->inspector->personalInfo->last_name,
                $inspection_report->inspector->empPosition->position,
                Storage::disk('s3')->temporaryUrl("signatures/" . $signature->signature, now()->addMinutes(30)),
                "Inspection Report for LTP Application #" . $inspection_report->ltpApplication->application_no,
                "8",
                $inspection_report->id,
                $inspection_report->inspector->id,  
                null
            );
            Storage::disk('s3')->put(
                "generated_signatures/" . $signature_title,
                // "data:image/png;base64," . base64_encode($inspectorSignature->body())
                // Storage::disk('s3')->get("signatures/" . $signature->signature)
                $inspectorSignature->body()
            );
            $inspection_report->inspector_signed = $signature_title;
            $inspection_report->save();
            LtpApplicationProgress::create([
                'ltp_application_id' => $inspection_report->ltp_application_id,
                'user_id' => auth()->user()->id,
                'status' => LtpApplication::STATUS_INSPECTED,
                'remarks' => "Inspection report has been signed by the Inspector: " . auth()->user()->personalInfo->getFullNameAttribute()
            ]);
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
            $approverSignature = $this->getElectronicSignature(
                $inspection_report->approver->personalInfo->first_name . " " . $inspection_report->approver->personalInfo->middle_name . " " . $inspection_report->approver->personalInfo->last_name,
                $inspection_report->approver->empPosition->position,
                Storage::disk('s3')->temporaryUrl("signatures/" . $signature->signature, now()->addMinutes(30)),
                "Inspection Report for LTP Application #" . $inspection_report->ltpApplication->application_no,
                "8",
                $inspection_report->id,
                $inspection_report->approver->id,  
                null
            );
            Storage::disk('s3')->put(
                'generated_signatures/' . $signature_title,
                // "data:image/png;base64," . base64_encode($approverSignature->body())
                $approverSignature->body()
            );
            $inspection_report->approver_signed = $signature_title;
            $permitteeUrl = Storage::disk('s3')->temporaryUrl("generated_signatures/" . $inspection_report->permittee_signed, now()->addMinutes(30));
            $inspectorUrl = Storage::disk('s3')->temporaryUrl("generated_signatures/" . $inspection_report->inspector_signed, now()->addMinutes(30));
            $approverUrl = Storage::disk('s3')->temporaryUrl("generated_signatures/" . $inspection_report->approver_signed, now()->addMinutes(30));
            // Pdf::view('pdfs.inspection-report', [
            //     "ltp_application" => $inspection_report->ltpApplication,
            //     "inspection_report" => $inspection_report,
            //     "permitteeUrl" => $permitteeUrl,
            //     "inspectorUrl" => $inspectorUrl,
            //     "approverUrl" => $approverUrl
            // ])
            // ->waitUntilReady();

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
            LtpApplicationProgress::create([
                'ltp_application_id' => $inspection_report->ltp_application_id,
                'user_id' => auth()->user()->id,
                'status' => LtpApplication::STATUS_INSPECTED,
                'remarks' => "Inspection report has been approved by " . auth()->user()->personalInfo->getFullNameAttribute()
            ]);
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
        LtpApplicationProgress::create([
            'ltp_application_id' => $inspection_report->ltp_application_id,
            'user_id' => auth()->user()->id,
            'status' => LtpApplication::STATUS_INSPECTED,
            'remarks' => "Inspection report has been signed by the RPS In Charge/Chief: " . auth()->user()->personalInfo->getFullNameAttribute()
        ]);
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
            $signature_title = $ltpPermit->id . "_penro_approved_by.png";
            $signatureUrl = Storage::disk('s3')->temporaryUrl("signatures/" . $signature->signature, now()->addMinutes(30));
            $approverSignature = $this->getElectronicSignature(
                $ltpPermit->approver->personalInfo->first_name . " " . $ltpPermit->approver->personalInfo->middle_name . " " . $ltpPermit->approver->personalInfo->last_name,
                $ltpPermit->approver->empPosition->position,
                Storage::disk('s3')->temporaryUrl("signatures/" . $signature->signature, now()->addMinutes(30)),
                "LTP Application #" . $ltpPermit->permit_number,
                "9",
                $ltpPermit->id,
                $ltpPermit->approver->id,  
                null
            );
            Storage::disk('s3')->put(
                'generated_signatures/' . $signature_title,
                $approverSignature->body()
            );
            Pdf::view('pdfs.permit', [
                "permit" => $ltpPermit,
                "ltp_application" => $ltpApplication,
                "signatureUrl" => 'data:image/png;base64,' . base64_encode($approverSignature->body())
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
            $signature = $payment_order->preparedBy->getSignature();
            if(!$signature) {
                return redirect()->back()->with('error', 'Signature not found.');
            }
            
            Storage::disk('s3')->put(
                "generated_signatures/" . $signature_title, 
                Storage::disk('s3')->get("signatures/" . $payment_order->preparedBy->getSignature()->signature)
            );
            $payment_order->prepared_signed = $signature_title;
            $payment_order->save();
            LtpApplicationProgress::create([
                'ltp_application_id' => $payment_order->ltp_application_id,
                'user_id' => auth()->user()->id,
                'status' => LtpApplication::STATUS_PAYMENT_IN_PROCESS,
                'remarks' => "Assessment of Fees and Charges has been signed by the RPS In Charge/Chief: " . auth()->user()->personalInfo->getFullNameAttribute()
            ]);
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
            $signature = $payment_order->approvedBy->getSignature();
            if(!$signature) {
                return redirect()->back()->with('error', 'Signature not found.');
            }
            Storage::disk('s3')->put(
                "generated_signatures/" . $signature_title, 
                Storage::disk('s3')->get("signatures/" . $payment_order->approvedBy->getSignature()->signature)
            );
            $payment_order->approved_signed = $signature_title;
            $prepared_by = $payment_order->preparedBy;
            $approved_by = $payment_order->approvedBy;
            $approvedByUrl = Storage::disk('s3')->temporaryUrl('generated_signatures/' . $signature_title, now()->addMinutes(30));
            $preparedByUrl = Storage::disk('s3')->temporaryUrl('generated_signatures/' . $payment_order->prepared_signed, now()->addMinutes(30));
            
            $approvedBySignature = $this->getElectronicSignature(
                $approved_by->personalInfo->first_name . " " . $approved_by->personalInfo->middle_name . " " . $approved_by->personalInfo->last_name,
                $approved_by->empPosition->position,
                $approvedByUrl,
                $payment_order->order_number,
                "7",
                $payment_order->id,
                $payment_order->approvedBy->id,
                null
            );

            $preparedBySignature = $this->getElectronicSignature(
                $prepared_by->personalInfo->first_name . " " . $prepared_by->personalInfo->middle_name . " " . $prepared_by->personalInfo->last_name,
                $prepared_by->empPosition->position,
                $preparedByUrl,
                $payment_order->order_number,
                "7",
                $payment_order->id,
                $payment_order->preparedBy->id,
                null
            );

            Pdf::view('pdfs.billing-statement-pdf', [
                "ltp_application" => $payment_order->ltpApplication,
                'payment_order' => $payment_order,
                'approvedByUrl' => 'data:image/png;base64,' . base64_encode($approvedBySignature->body()),
                'preparedByUrl' => 'data:image/png;base64,' . base64_encode($preparedBySignature->body()),
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
            
            LtpApplicationProgress::create([
                'ltp_application_id' => $payment_order->ltp_application_id,
                'user_id' => auth()->user()->id,
                'status' => LtpApplication::STATUS_PAYMENT_IN_PROCESS,
                'remarks' => "Assessment of Fees and Charges has been signed by the TSD In Charge/Chief: " . auth()->user()->personalInfo->getFullNameAttribute()
            ]);
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
            $signature = $payment_order->oopApprovedBy->getSignature();
            if(!$signature) {
                return redirect()->back()->with('error', 'Signature not found.');
            }
            Storage::disk('s3')->put(
                "generated_signatures/" . $signature_title, 
                Storage::disk('s3')->get("signatures/" . $payment_order->oopApprovedBy->getSignature()->signature)
            );
            $oopApproverUrl = Storage::disk('s3')->temporaryUrl('generated_signatures/' . $signature_title, now()->addMinutes(30));
                 
            $oopApproverSignature = $this->getElectronicSignature(
                $payment_order->oopApprovedBy->personalInfo->first_name . " " . $payment_order->oopApprovedBy->personalInfo->middle_name . " " . $payment_order->oopApprovedBy->personalInfo->last_name,
                $payment_order->oopApprovedBy->empPosition->position,
                $oopApproverUrl,
                $payment_order->serial_number,
                "6",
                $payment_order->id,
                $payment_order->oopApprovedBy->id,  
                null
            );
            
            Pdf::view('pdfs.oop-pdf', [
                "ltp_application" => $payment_order->ltpApplication,
                'payment_order' => $payment_order,
                'oopApproverUrl' => 'data:image/png;base64,' . base64_encode($oopApproverSignature->body()),
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
            LtpApplicationProgress::create([
                'ltp_application_id' => $payment_order->ltp_application_id,
                'user_id' => auth()->user()->id,
                'status' => LtpApplication::STATUS_PAYMENT_IN_PROCESS,
                'remarks' => "Order of Payment has been signed by the Accountant: " . auth()->user()->personalInfo->getFullNameAttribute()
            ]);
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

    // public function billingStatementSignSelected(Request $request) {
    //     return DB::transaction(function() use ($request) {
    //         $ids = explode(",", $request->input('ids'));
    //         $ltpApplications = LtpApplication::whereIn('id', $ids)->get();
    //         $billingStatements = PaymentOrder::whereIn('ltp_application_id', $ids)->get();
    //         foreach($billingStatements as $billingStatement) {
    //             $payment_order = $billingStatement;
    //             if($billingStatement->prepared_by == auth()->user()->id) {
    //                 Gate::authorize('preparerSign', $payment_order);
    //                 $signature_title = $payment_order->id . "_prepared_by.png";
    //                 $signature = $payment_order->preparedBy->getSignature();
    //                 if(!$signature) {
    //                     return redirect()->back()->with('error', 'Signature not found.');
    //                 }
                    
    //                 Storage::disk('s3')->put(
    //                     "generated_signatures/" . $signature_title, 
    //                     Storage::disk('s3')->get("signatures/" . $payment_order->preparedBy->getSignature()->signature)
    //                 );
    //                 $payment_order->prepared_signed = $signature_title;
    //                 $payment_order->save();
    //                 Notification::send($payment_order->approvedBy, new SignedNotification(route('for-signatures.index', ['type' => 'billing_statement']), 'An Assessment of Fees and Charges has been signed by the preparer and is now ready for your approval.'));
    //             }
    
    //             if($billingStatement->approved_by == auth()->user()->id) {
    //                 Gate::authorize('approverSign', $payment_order);
    //                 $signature_title = $payment_order->id . "_approved_by.png";
    //                 $signature = $payment_order->approvedBy->getSignature();
    //                 if(!$signature) {
    //                     return redirect()->back()->with('error', 'Signature not found.');
    //                 }
    //                 Storage::disk('s3')->put(
    //                     "generated_signatures/" . $signature_title, 
    //                     Storage::disk('s3')->get("signatures/" . $payment_order->approvedBy->getSignature()->signature)
    //                 );
    //                 $payment_order->approved_signed = $signature_title;
    //                 $prepared_by = $payment_order->preparedBy;
    //                 $approved_by = $payment_order->approvedBy;
    //                 $approvedByUrl = Storage::disk('s3')->temporaryUrl('generated_signatures/' . $signature_title, now()->addMinutes(30));
    //                 $preparedByUrl = Storage::disk('s3')->temporaryUrl('generated_signatures/' . $payment_order->prepared_signed, now()->addMinutes(30));
                    
    //                 $approvedBySignature = $this->getElectronicSignature(
    //                     $approved_by->personalInfo->first_name . " " . $approved_by->personalInfo->middle_name . " " . $approved_by->personalInfo->last_name,
    //                     $approved_by->empPosition->position,
    //                     $approvedByUrl,
    //                     $payment_order->order_number,
    //                     "7",
    //                     $payment_order->id,
    //                     $payment_order->approvedBy->id,
    //                     null
    //                 );

    //                 $preparedBySignature = $this->getElectronicSignature(
    //                     $prepared_by->personalInfo->first_name . " " . $prepared_by->personalInfo->middle_name . " " . $prepared_by->personalInfo->last_name,
    //                     $prepared_by->empPosition->position,
    //                     $preparedByUrl,
    //                     $payment_order->order_number,
    //                     "7",
    //                     $payment_order->id,
    //                     $payment_order->preparedBy->id,
    //                     null
    //                 );

    //                 Pdf::view('pdfs.billing-statement-pdf', [
    //                     "ltp_application" => $payment_order->ltpApplication,
    //                     'payment_order' => $payment_order,
    //                     'approvedByUrl' => 'data:image/png;base64,' . base64_encode($approvedBySignature->body()),
    //                     'preparedByUrl' => 'data:image/png;base64,' . base64_encode($preparedBySignature->body()),
    //                     "ltp_fee" => $payment_order->ltpFee,
    //                     "prepared_by" => $prepared_by,
    //                     "approved_by" => $approved_by
    //                 ])
    //                 ->waitUntilReady()
    //                 ->disk('s3')
    //                 ->save("billing_statemenet/billing_statement_" . $payment_order->id . ".pdf");
    //                 $payment_order->save();
    //                 $serialNoEncoders = User::whereHas('userRoles', function ($query) {
    //                     $query->whereHas('role', function ($query) {
    //                         $query->whereHas('rolePermissions', function ($query) {
    //                             $query->where('permission', 'PAYMENT_ORDERS_ENCODE_SERIAL_NO');
    //                         });
    //                     });
    //                 })->get();
    //                 Notification::send($serialNoEncoders, new SignedNotification(route('paymentorder.show', ['id' => Crypt::encryptString($payment_order->id)]), 'An Assessment of Fees and Charges has been approved by the approver and is now ready for serial number encoding.'));
    //             }
    //         }
    //         return redirect()->route('for-signatures.index', ['type' => 'billing_statement'])->with('success', 'Billing statements signed successfully!');
    //     });
    // }

    public function billingStatementSignSelected(Request $request)
    {
        $ids = explode(",", $request->input('ids'));

        // 1. Eager load all required relationships at once (Fixes N+1 queries)
        $billingStatements = PaymentOrder::with([
            'preparedBy.personalInfo',
            'preparedBy.empPosition',
            'approvedBy.personalInfo',
            'approvedBy.empPosition',
            'ltpApplication',
            'ltpFee'
        ])->whereIn('ltp_application_id', $ids)->get();

        // 2. Query encoders once outside the loop
        $serialNoEncoders = User::whereHas('userRoles.role.rolePermissions', function ($query) {
            $query->where('permission', 'PAYMENT_ORDERS_ENCODE_SERIAL_NO');
        })->get();

        $userId = auth()->id();

        foreach ($billingStatements as $paymentOrder) {
            // PREPARER FLOW
            if ($paymentOrder->prepared_by == $userId) {
                Gate::authorize('preparerSign', $paymentOrder);

                $signature = $paymentOrder->preparedBy?->getSignature();
                if (!$signature || !$signature->signature) {
                    return redirect()->back()->with('error', 'Signature not found for order #' . $paymentOrder->id);
                }

                $signatureTitle = $paymentOrder->id . "_prepared_by.png";

                // S3 Transfer
                Storage::disk('s3')->put(
                    "generated_signatures/" . $signatureTitle,
                    Storage::disk('s3')->get("signatures/" . $signature->signature)
                );

                // DB Update inside isolated transaction
                DB::transaction(function () use ($paymentOrder, $signatureTitle) {
                    $paymentOrder->prepared_signed = $signatureTitle;
                    $paymentOrder->save();
                });

                // Send notification outside transaction
                if ($paymentOrder->approvedBy) {
                    Notification::send($paymentOrder->approvedBy, new SignedNotification(
                        route('for-signatures.index', ['type' => 'billing_statement']),
                        'An Assessment of Fees and Charges has been signed by the preparer and is now ready for your approval.'
                    ));
                }
            }

            // APPROVER FLOW
            if ($paymentOrder->approved_by == $userId) {
                Gate::authorize('approverSign', $paymentOrder);

                $signature = $paymentOrder->approvedBy?->getSignature();
                if (!$signature || !$signature->signature) {
                    return redirect()->back()->with('error', 'Signature not found for order #' . $paymentOrder->id);
                }

                $signatureTitle = $paymentOrder->id . "_approved_by.png";

                Storage::disk('s3')->put(
                    "generated_signatures/" . $signatureTitle,
                    Storage::disk('s3')->get("signatures/" . $signature->signature)
                );

                $preparedBy = $paymentOrder->preparedBy;
                $approvedBy = $paymentOrder->approvedBy;

                $approvedByUrl = Storage::disk('s3')->temporaryUrl('generated_signatures/' . $signatureTitle, now()->addMinutes(30));
                $preparedByUrl = Storage::disk('s3')->temporaryUrl('generated_signatures/' . $paymentOrder->prepared_signed, now()->addMinutes(30));

                $approvedBySignature = $this->getElectronicSignature(
                    "{$approvedBy->personalInfo->first_name} {$approvedBy->personalInfo->middle_name} {$approvedBy->personalInfo->last_name}",
                    $approvedBy->empPosition->position ?? '',
                    $approvedByUrl,
                    $paymentOrder->order_number,
                    "7",
                    $paymentOrder->id,
                    $approvedBy->id,
                    null
                );

                $preparedBySignature = $this->getElectronicSignature(
                    "{$preparedBy->personalInfo->first_name} {$preparedBy->personalInfo->middle_name} {$preparedBy->personalInfo->last_name}",
                    $preparedBy->empPosition->position ?? '',
                    $preparedByUrl,
                    $paymentOrder->order_number,
                    "7",
                    $paymentOrder->id,
                    $preparedBy->id,
                    null
                );

                Pdf::view('pdfs.billing-statement-pdf', [
                    "ltp_application" => $paymentOrder->ltpApplication,
                    'payment_order'   => $paymentOrder,
                    'approvedByUrl'   => 'data:image/png;base64,' . base64_encode($approvedBySignature->body()),
                    'preparedByUrl'   => 'data:image/png;base64,' . base64_encode($preparedBySignature->body()),
                    "ltp_fee"         => $paymentOrder->ltpFee,
                    "prepared_by"     => $preparedBy,
                    "approved_by"     => $approvedBy
                ])
                ->waitUntilReady()
                ->disk('s3')
                ->save("billing_statemenet/billing_statement_" . $paymentOrder->id . ".pdf");

                // DB Update
                DB::transaction(function () use ($paymentOrder, $signatureTitle) {
                    $paymentOrder->approved_signed = $signatureTitle;
                    $paymentOrder->save();
                });

                // Send batch notification outside transaction
                if ($serialNoEncoders->isNotEmpty()) {
                    Notification::send($serialNoEncoders, new SignedNotification(
                        route('paymentorder.show', ['id' => Crypt::encryptString($paymentOrder->id)]),
                        'An Assessment of Fees and Charges has been approved by the approver and is now ready for serial number encoding.'
                    ));
                }
            }
        }

        return redirect()->route('for-signatures.index', ['type' => 'billing_statement'])
            ->with('success', 'Billing statements signed successfully!');
    }

    public function billingStatementSignAll(Request $request)
    {
        $userId = auth()->user()->id;

        // 1. Properly group conditions to avoid global orWhere leaks
        // and filter out already-signed records
        $billingStatements = PaymentOrder::with([
            'preparedBy.personalInfo',
            'preparedBy.empPosition',
            'approvedBy.personalInfo',
            'approvedBy.empPosition',
            'ltpApplication',
            'ltpFee'
        ])
        ->where(function ($query) use ($userId) {
            $query->where(function ($q) use ($userId) {
                $q->where('prepared_by', $userId)
                ->whereNull('prepared_signed');
            })->orWhere(function ($q) use ($userId) {
                $q->where('approved_by', $userId)
                ->whereNull('approved_signed');
            });
        })
        ->get();

        if ($billingStatements->isEmpty()) {
            return redirect()->back()->with('info', 'No pending billing statements found to sign.');
        }

        // 2. Query encoders once outside loop
        $serialNoEncoders = User::whereHas('userRoles.role.rolePermissions', function ($query) {
            $query->where('permission', 'PAYMENT_ORDERS_ENCODE_SERIAL_NO');
        })->get();

        $processedCount = 0;
        $errors = [];

        foreach ($billingStatements as $paymentOrder) {
            // PREPARER FLOW
            if ($paymentOrder->prepared_by == $userId && !$paymentOrder->prepared_signed) {
                Gate::authorize('preparerSign', $paymentOrder);

                $signature = $paymentOrder->preparedBy?->getSignature();
                if (!$signature || !$signature->signature) {
                    $errors[] = "Signature not found for Order #{$paymentOrder->order_number}";
                    continue;
                }

                $signatureTitle = $paymentOrder->id . "_prepared_by.png";

                Storage::disk('s3')->put(
                    "generated_signatures/" . $signatureTitle,
                    Storage::disk('s3')->get("signatures/" . $signature->signature)
                );

                DB::transaction(function () use ($paymentOrder, $signatureTitle) {
                    $paymentOrder->prepared_signed = $signatureTitle;
                    $paymentOrder->save();
                });

                if ($paymentOrder->approvedBy) {
                    Notification::send($paymentOrder->approvedBy, new SignedNotification(
                        route('for-signatures.index', ['type' => 'billing_statement']),
                        'An Assessment of Fees and Charges has been signed by the preparer and is now ready for your approval.'
                    ));
                }

                $processedCount++;
            }

            // APPROVER FLOW
            if ($paymentOrder->approved_by == $userId && !$paymentOrder->approved_signed) {
                Gate::authorize('approverSign', $paymentOrder);

                // Ensure preparer signature exists before approving
                if (!$paymentOrder->prepared_signed) {
                    $errors[] = "Cannot approve Order #{$paymentOrder->order_number}: Missing preparer signature.";
                    continue;
                }

                $signature = $paymentOrder->approvedBy?->getSignature();
                if (!$signature || !$signature->signature) {
                    $errors[] = "Signature not found for Order #{$paymentOrder->order_number}";
                    continue;
                }

                $signatureTitle = $paymentOrder->id . "_approved_by.png";

                Storage::disk('s3')->put(
                    "generated_signatures/" . $signatureTitle,
                    Storage::disk('s3')->get("signatures/" . $signature->signature)
                );

                $preparedBy = $paymentOrder->preparedBy;
                $approvedBy = $paymentOrder->approvedBy;

                $approvedByUrl = Storage::disk('s3')->temporaryUrl('generated_signatures/' . $signatureTitle, now()->addMinutes(30));
                $preparedByUrl = Storage::disk('s3')->temporaryUrl('generated_signatures/' . $paymentOrder->prepared_signed, now()->addMinutes(30));

                $approvedBySignature = $this->getElectronicSignature(
                    "{$approvedBy->personalInfo->first_name} {$approvedBy->personalInfo->middle_name} {$approvedBy->personalInfo->last_name}",
                    $approvedBy->empPosition->position ?? '',
                    $approvedByUrl,
                    $paymentOrder->order_number,
                    "7",
                    $paymentOrder->id,
                    $approvedBy->id,
                    null
                );

                $preparedBySignature = $this->getElectronicSignature(
                    "{$preparedBy->personalInfo->first_name} {$preparedBy->personalInfo->middle_name} {$preparedBy->personalInfo->last_name}",
                    $preparedBy->empPosition->position ?? '',
                    $preparedByUrl,
                    $paymentOrder->order_number,
                    "7",
                    $paymentOrder->id,
                    $preparedBy->id,
                    null
                );

                Pdf::view('pdfs.billing-statement-pdf', [
                    "ltp_application" => $paymentOrder->ltpApplication,
                    'payment_order'   => $paymentOrder,
                    'approvedByUrl'   => 'data:image/png;base64,' . base64_encode($approvedBySignature->body()),
                    'preparedByUrl'   => 'data:image/png;base64,' . base64_encode($preparedBySignature->body()),
                    "ltp_fee"         => $paymentOrder->ltpFee,
                    "prepared_by"     => $preparedBy,
                    "approved_by"     => $approvedBy
                ])
                ->waitUntilReady()
                ->disk('s3')
                ->save("billing_statemenet/billing_statement_" . $paymentOrder->id . ".pdf");

                DB::transaction(function () use ($paymentOrder, $signatureTitle) {
                    $paymentOrder->approved_signed = $signatureTitle;
                    $paymentOrder->save();
                });

                if ($serialNoEncoders->isNotEmpty()) {
                    Notification::send($serialNoEncoders, new SignedNotification(
                        route('paymentorder.show', ['id' => Crypt::encryptString($paymentOrder->id)]),
                        'An Assessment of Fees and Charges has been approved by the approver and is now ready for serial number encoding.'
                    ));
                }

                $processedCount++;
            }
        }

        $response = redirect()->route('for-signatures.index', ['type' => 'billing_statement']);

        if (!empty($errors)) {
            return $response->with('warning', "Signed {$processedCount} statements with warnings: " . implode(' ', $errors));
        }

        return $response->with('success', "Successfully signed {$processedCount} billing statements!");
    }
}
