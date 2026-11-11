<?php

namespace App\Http\Controllers;

use App\ElectronicSigratureTrait;
use App\Helpers\ApplicationHelper;
use App\Http\Controllers\Controller;
use App\Models\LtpPermit;
use App\SystemGeneratedDocTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Nette\FileNotFoundException;
use setasign\Fpdi\Fpdi;
use Spatie\LaravelPdf\Enums\Unit;
use Spatie\LaravelPdf\Facades\Pdf;

class LtpController extends Controller
{
    use ElectronicSigratureTrait, SystemGeneratedDocTrait;
    //
    public function index(Request $request)
    {
        $ltps_query = LtpPermit::query()->with('ltpApplication.permittee');

        if ($request->filled('search')) {
            $search = $request->search;
            $ltps_query->where(function ($query) use ($search) {
                $query->where('permit_number', 'like', "%$search%")
                      ->orWhereHas('ltpApplication', function ($subquery) use ($search) {
                          $subquery->where('application_no', 'like', "%$search%");
                      });
            });
        }

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
        return $this->sgdGenerateLtp($ltpPermit);
        // $ltpApplication = $ltpPermit->ltpApplication;
        // $approverSignatureUrl = null;
        // if($ltpPermit->penro_signed) {
        //     $approverSignature = $this->getElectronicSignature(
        //         $ltpPermit->approver->personalInfo->first_name . " " . $ltpPermit->approver->personalInfo->middle_name . " " . $ltpPermit->approver->personalInfo->last_name,
        //         $ltpPermit->approver->empPosition->position,
        //         Storage::disk('s3')->temporaryUrl("signatures/" . $ltpPermit->approver->getSignature()->signature, now()->addMinutes(30)),
        //         "LTP Application #" . $ltpPermit->permit_number,
        //         "9",
        //         $ltpPermit->id,
        //         $ltpPermit->approver->id,  
        //         null
        //     );
        //     $approverSignatureUrl = 'data:image/png;base64,' . base64_encode($approverSignature->body());
        // }
        // $res = Pdf::view('pdfs.permit', [
        //         "permit" => $ltpPermit,
        //         "ltp_application" => $ltpApplication,
        //         "signatureUrl" => $approverSignatureUrl
        //     ])
        //     ->waitUntilReady()
        //     ->headerView('pdfs.header')
        //     ->footerView('pdfs.footer')
        //     ->margins(1.3, 0.5, 1.3, 0.5, Unit::Inch);

        // $res->disk("s3")
        //     ->save("ltps/" . $ltpPermit->permit_number . ".pdf");

        // return $res;
    }

    public function ajaxGetAttachments(Request $request, $id)
    {
        Gate::authorize('viewPermit', LtpPermit::find(Crypt::decryptString($id)));
        $ltpPermit = LtpPermit::find(Crypt::decryptString($id));
        $ltpApplication = $ltpPermit->ltpApplication;

        if (!$ltpPermit) {
            throw new FileNotFoundException("LTP Permit not found.");
        }

        $files = [];

        /** START GET LTP */
        if (!Storage::disk('s3')->exists("ltps/" . $ltpPermit->permit_number . ".pdf")) {
            $this->sgdGenerateLtp($ltpPermit);
        }
        $files[] = Storage::disk('s3')->temporaryUrl("ltps/" . $ltpPermit->permit_number . ".pdf", now()->addMinutes(30));
        /** END GET LTP */

        /** START GET INSPECTION REPORT */
        if ($ltpApplication->inspectionReport) {
            $this->sgdGenerateInspectionReport($ltpApplication->inspectionReport);
            // if (!Storage::disk('s3')->exists("inspection_report/" . $ltpApplication->inspectionReport->id . ".pdf")) {
            // }
            $files[] = Storage::disk('s3')->temporaryUrl("inspection_report/" . $ltpApplication->inspectionReport->id . ".pdf", now()->addMinutes(30));
        }
        /** END GET INSPECTION REPORT */

        /** START GET REQUEST LETTER */
        $files[] = Storage::disk('s3')->temporaryUrl("request_letters/" . $ltpApplication->id . ".pdf", now()->addMinutes(30));
        /** END GET REQUEST LETTER */

        /** START OTHER REQUIREMENTS */
        foreach ($ltpApplication->attachments as $attachment) {
            $s3Path = $attachment->file_path;
            $files[] = Storage::disk('s3')->temporaryUrl($s3Path, now()->addMinutes(30));
        }
        /** END OTHER REQUIREMENTS */

        /** START WFP AND WCP */
        $wfp = $ltpApplication->permittee->user->wfp();
        $files[] = Storage::disk('s3')->temporaryUrl($wfp->document, now()->addMinutes(30));

        $wcp = $ltpApplication->permittee->user->wcp();
        $files[] = Storage::disk('s3')->temporaryUrl($wcp->document, now()->addMinutes(30));
        /** END WFP AND WCP */

        /** START RECEIPT */
        if ($ltpApplication->paymentOrder && $ltpApplication->paymentOrder->receipt_url) {
            $receiptPath = $ltpApplication->paymentOrder->receipt_url;
            $files[] = Storage::disk('s3')->temporaryUrl($receiptPath, now()->addMinutes(30));
        }
        /** END RECEIPT */

        return response()->json(['attachments' => $files]);
    }

    // public function viewCompletePermit(Request $request, $id)
    // {
    //     $ltpPermit = LtpPermit::find(Crypt::decryptString($id));

    //     if (!$ltpPermit) {
    //         throw new FileNotFoundException("LTP Permit not found.");
    //     }

    //     $ltpApplication = $ltpPermit->ltpApplication;
    //     $files = [];
    //     $tempFilesToClean = [];

    //     // Helper closure to safely download S3 files locally for FPDI processing
    //     $getLocalPath = function ($s3Path) use (&$tempFilesToClean) {
    //         if (!Storage::disk('s3')->exists($s3Path)) {
    //             return null;
    //         }

    //         $extension = strtolower(pathinfo($s3Path, PATHINFO_EXTENSION));
    //         $tempFile = tempnam(sys_get_temp_dir(), 'pdf_merge_') . ($extension ? '.' . $extension : '');
    //         file_put_contents($tempFile, Storage::disk('s3')->get($s3Path));
    //         $tempFilesToClean[] = $tempFile;

    //         // If it's a PDF, sanitize/downgrade to PDF 1.4 for FPDI compatibility
    //         if ($extension === 'pdf') {
    //             $compatFile = $this->convertToCompatiblePdf($tempFile);
    //             if ($compatFile !== $tempFile) {
    //                 $tempFilesToClean[] = $compatFile;
    //                 return $compatFile;
    //             }
    //         }

    //         return $tempFile;
    //     };

    //     /** START GET LTP */
    //     if (!Storage::disk('s3')->exists("ltps/" . $ltpPermit->permit_number . ".pdf")) {
    //         $this->sgdGenerateLtp($ltpPermit);
    //     }
    //     if ($path = $getLocalPath("ltps/" . $ltpPermit->permit_number . ".pdf")) {
    //         $files[] = $path;
    //     }
    //     /** END GET LTP */

    //     /** START GET INSPECTION REPORT */
    //     if ($ltpApplication->inspectionReport) {
    //         if (!Storage::disk('s3')->exists("inspection-reports/" . $ltpApplication->inspectionReport->id . ".pdf")) {
    //             $this->sgdGenerateInspectionReport($ltpApplication->inspectionReport);
    //         }
    //         if ($path = $getLocalPath("inspection-reports/" . $ltpApplication->inspectionReport->id . ".pdf")) {
    //             $files[] = $path;
    //         }
    //     }
    //     /** END GET INSPECTION REPORT */

    //     /** START GET REQUEST LETTER */
    //     if ($path = $getLocalPath("request-letters/" . $ltpApplication->id . ".pdf")) {
    //         $files[] = $path;
    //     }
    //     /** END GET REQUEST LETTER */

    //     /** START OTHER REQUIREMENTS */
    //     foreach ($ltpApplication->attachments as $attachment) {
    //         $s3Path = "ltp-attachments/" . $attachment->file_path;
    //         $localPath = $getLocalPath($s3Path);

    //         if ($localPath) {
    //             $extension = strtolower(pathinfo($localPath, PATHINFO_EXTENSION));

    //             if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
    //                 $pdfPath = $this->convertImageToPdf($localPath, $extension);
    //                 $tempFilesToClean[] = $pdfPath;
    //                 $files[] = $pdfPath;
    //             } else {
    //                 $files[] = $localPath;
    //             }
    //         }
    //     }
    //     /** END OTHER REQUIREMENTS */

    //     /** START RECEIPT */
    //     if ($ltpApplication->paymentOrder && $ltpApplication->paymentOrder->receipt_url) {
    //         $receiptPath = $ltpApplication->paymentOrder->receipt_url;
    //         $localPath = $getLocalPath($receiptPath);

    //         if ($localPath) {
    //             $extension = strtolower(pathinfo($localPath, PATHINFO_EXTENSION));
    //             if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
    //                 $pdfPath = $this->convertImageToPdf($localPath, $extension);
    //                 $tempFilesToClean[] = $pdfPath;
    //                 $files[] = $pdfPath;
    //             } else {
    //                 $files[] = $localPath;
    //             }
    //         }
    //     }
    //     /** END RECEIPT */

    //     /** START WFP AND WCP */
    //     $wfp = $ltpApplication->permittee->user->wfp();
    //     if ($wfp && ($path = $getLocalPath($wfp->document))) {
    //         $files[] = $path;
    //     }

    //     $wcp = $ltpApplication->permittee->user->wcp();
    //     if ($wcp && ($path = $getLocalPath($wcp->document))) {
    //         $files[] = $path;
    //     }
    //     /** END WFP AND WCP */

    //     $outputDirectory = storage_path('app/temp-combined');
    //     if (!file_exists($outputDirectory)) {
    //         mkdir($outputDirectory, 0755, true);
    //     }
    //     $outputFile = $outputDirectory . '/' . $ltpPermit->permit_number . '.pdf';

    //     $pdf = new Fpdi();

    //     foreach ($files as $file) {
    //         if (!file_exists($file)) {
    //             continue;
    //         }
    //         // Log
    //         $pageCount = $pdf->setSourceFile($file);

    //         for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
    //             $templateId = $pdf->importPage($pageNo);
    //             $size = $pdf->getTemplateSize($templateId);

    //             $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
    //             $pdf->useTemplate($templateId);
    //         }
    //     }

    //     $pdf->Output('F', $outputFile);

    //     // Sync combined PDF back to S3
    //     Storage::disk('s3')->put("ltps-combined/" . $ltpPermit->permit_number . ".pdf", file_get_contents($outputFile));

    //     // Cleanup temporary files
    //     foreach ($tempFilesToClean as $tempFile) {
    //         if (file_exists($tempFile)) {
    //             @unlink($tempFile);
    //         }
    //     }

    //     return response()->file($outputFile, [
    //         'Content-Type' => 'application/pdf',
    //         'Content-Disposition' => 'inline; filename="' . $ltpPermit->permit_number . '-complete.pdf"'
    //     ])->deleteFileAfterSend(true);
    // }

    /**
     * Helper function to convert an image to a temporary PDF so FPDI can read it.
     */
    // private function convertImageToPdf($imagePath, $extension)
    // {
    //     // Create a temporary PDF file path
    //     $pdfTempPath = sys_get_temp_dir() . '/' . uniqid('img_pdf_', true) . '.pdf';

    //     // Initialize FPDF/FPDI to create a single-page PDF containing the image
    //     $pdf = new Fpdi();
        
    //     // Get image dimensions
    //     list($width, $height) = getimagesize($imagePath);
        
    //     // Determine orientation based on dimensions
    //     $orientation = ($width > $height) ? 'L' : 'P';

    //     // Add a page matching the image proportions or standard sizes
    //     $pdf->AddPage($orientation);
        
    //     // Fit the image onto the page (adjust coordinates/sizes as needed)
    //     // Here we place it at x=10, y=10 with a width of 190mm (standard A4 margins)
    //     if ($orientation === 'P') {
    //         $pdf->Image($imagePath, 10, 10, 190);
    //     } else {
    //         $pdf->Image($imagePath, 10, 10, 277); // Landscape width fit
    //     }

    //     $pdf->Output('F', $pdfTempPath);

    //     return $pdfTempPath;
    // }
}
