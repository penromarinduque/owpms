<?php

namespace App\Http\Controllers;

use App\ElectronicSigratureTrait;
use App\Helpers\ApplicationHelper;
use App\Http\Controllers\Controller;
use App\Models\LtpPermit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelPdf\Enums\Unit;
use Spatie\LaravelPdf\Facades\Pdf;

class LtpController extends Controller
{
    use ElectronicSigratureTrait;
    //
    public function index()
    {
        $ltps_query = LtpPermit::query()->with('ltpApplication.permittee');

        return view('admin.data_entry.ltps', [
            'ltps' => $ltps_query->paginate(20)
        ]);
    }

    public function viewPermit($id) {
        // return Crypt::decryptString($id);
        $ltpPermit = LtpPermit::find(Crypt::decryptString($id));

        Gate::authorize('viewPermit', $ltpPermit);
        $file = Storage::disk('s3')->get('ltps/' . $ltpPermit->ltpApplication->permit->permit_number . '.pdf');
        return response($file)->header('Content-Type', 'application/pdf');
    }

    public function viewSignedPermit(Request $request, $id){
        // return "trdt";
        $ltpPermit = LtpPermit::find(Crypt::decryptString($id));
        $ltpApplication = $ltpPermit->ltpApplication;
        $approverSignatureUrl = null;
        if($ltpPermit->penro_signed) {
            $approverSignature = $this->getElectronicSignature(
                $ltpPermit->approver->personalInfo->first_name . " " . $ltpPermit->approver->personalInfo->middle_name . " " . $ltpPermit->approver->personalInfo->last_name,
                $ltpPermit->approver->empPosition->position,
                Storage::disk('s3')->temporaryUrl("signatures/" . $ltpPermit->approver->getSignature()->signature, now()->addMinutes(30)),
                "LTP Application #" . $ltpPermit->permit_number,
                "9",
                $ltpPermit->id,
                $ltpPermit->approver->id,  
                null
            );
            $approverSignatureUrl = 'data:image/png;base64,' . base64_encode($approverSignature->body());
        }
        // return view('pdfs.permit', [
        //         "permit" => $ltpPermit,
        //         "ltp_application" => $ltpApplication,
        //         "signatureUrl" => $approverSignatureUrl
        //     ]);
        $res = Pdf::view('pdfs.permit', [
                "permit" => $ltpPermit,
                "ltp_application" => $ltpApplication,
                "signatureUrl" => $approverSignatureUrl
            ])
            ->waitUntilReady()
            ->headerView('pdfs.header')
            ->footerView('pdfs.footer')
            ->margins(1.3, 0.5, 1.3, 0.5, Unit::Inch);

        $res->disk("s3")
            ->save("ltps/" . $ltpPermit->permit_number . ".pdf");

        return $res;
    }
}
