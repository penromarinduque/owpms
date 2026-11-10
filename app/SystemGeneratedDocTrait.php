<?php

namespace App;

use Illuminate\Support\Facades\Storage;
use Spatie\LaravelPdf\Facades\Pdf;
use Spatie\LaravelPdf\Enums\Unit;

trait SystemGeneratedDocTrait
{
    //
    public function sgdGenerateLtp($ltp){
        $ltpPermit = $ltp;
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

    public function sgdGenerateInspectionReport($inspection_report){
        $permitteeSignUrl = null;
        $inspectorSignUrl = null;
        $approverSignUrl = null;

        $geoTaggedImages = $inspection_report->ltpApplication->geoTaggedImages;
        $imageUrls = $geoTaggedImages->isNotEmpty() ? $geoTaggedImages->map(function ($image) {
            return Storage::disk('s3')->temporaryUrl($image->image_url, now()->addMinutes(30));
        })->toArray() : [];


        if($inspection_report->permittee_signed) {
            $permitteeSignUrl = $this->getElectronicSignature(
                $inspection_report->permittee->personalInfo->first_name . " " . $inspection_report->permittee->personalInfo->middle_name . " " . $inspection_report->permittee->personalInfo->last_name,
                "Permittee",
                Storage::disk('s3')->temporaryUrl("signatures/" . $inspection_report->permittee->getSignature()->signature, now()->addMinutes(30)),
                "Inspection Report for LTP Application #" . $inspection_report->ltpApplication->application_no,
                "8",
                $inspection_report->id,
                $inspection_report->permittee->id,
                null
            );
        }
        if($inspection_report->inspector_signed) {
            $inspectorSignUrl = $this->getElectronicSignature(
                $inspection_report->inspector->personalInfo->first_name . " " . $inspection_report->inspector->personalInfo->middle_name . " " . $inspection_report->inspector->personalInfo->last_name,
                $inspection_report->inspector->empPosition->position,
                Storage::disk('s3')->temporaryUrl("signatures/" . $inspection_report->inspector->getSignature()->signature, now()->addMinutes(30)),
                "Inspection Report for LTP Application #" . $inspection_report->ltpApplication->application_no,
                "8",
                $inspection_report->id,
                $inspection_report->inspector->id,  
                null
            );
        }
        if($inspection_report->approver_signed) {
            $approverSignUrl = $this->getElectronicSignature(
                $inspection_report->approver->personalInfo->first_name . " " . $inspection_report->approver->personalInfo->middle_name . " " . $inspection_report->approver->personalInfo->last_name,
                $inspection_report->approver->empPosition->position,
                Storage::disk('s3')->temporaryUrl("signatures/" . $inspection_report->approver->getSignature()->signature, now()->addMinutes(30)),
                "Inspection Report for LTP Application #" . $inspection_report->ltpApplication->application_no,
                "8",
                $inspection_report->id,
                $inspection_report->approver->id,
                null
            );
        }
        $pdf = Pdf::view('pdfs.inspection-report', [
                "ltp_application" => $inspection_report->ltpApplication,
                "inspection_report" => $inspection_report,
                "permitteeUrl" => $permitteeSignUrl ? "data:image/png;base64," . base64_encode($permitteeSignUrl->body()) : null,
                "inspectorUrl" => $inspectorSignUrl ? "data:image/png;base64," . base64_encode($inspectorSignUrl->body()) : null,
                "approverUrl" =>  $approverSignUrl ? "data:image/png;base64," . base64_encode($approverSignUrl->body()) : null,
                "imageUrls" => $imageUrls
            ])
            ->waitUntilReady()
            ->paperSize(8.5, 13, 'in')
            // ->header
            ->headerView('pdfs.header')
            ->footerView('pdfs.footer')
            ->margins(1.3, 0.5, 1.3, 0.5, Unit::Inch);
 
        $pdf->disk("s3")
            ->save("inspection_report/" . $inspection_report->id . ".pdf");

        return $pdf;
    }

    public function convertToCompatiblePdf(string $inputPath): string
    {
        $outputPath = tempnam(sys_get_temp_dir(), 'pdf_compat_') . '.pdf';

        // Using Ghostscript (gs)
        $cmd = sprintf(
            'gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=/default -dNOPAUSE -dQUIET -dBATCH -sOutputFile=%s %s',
            escapeshellarg($outputPath),
            escapeshellarg($inputPath)
        );

        exec($cmd, $output, $returnCode);

        if ($returnCode === 0 && file_exists($outputPath)) {
            return $outputPath;
        }

        // Fallback to original path if conversion fails
        return $inputPath;
    }

}
