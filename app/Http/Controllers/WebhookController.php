<?php

namespace App\Http\Controllers;

use App\Helpers\ApplicationHelper;
use App\Models\LtpApplication;
use App\Models\LtpApplicationProgress;
use App\Models\Permittee;
use App\Models\User;
use App\Notifications\LtpApplicationSubmitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Fpdi;
use Spatie\LaravelPdf\Enums\Format;
use Spatie\LaravelPdf\Enums\Unit;
use Spatie\LaravelPdf\Facades\Pdf;

class WebhookController extends Controller
{
    //
    public function requestLetterSigned(Request $request) {
        // return $request;
        $request->validate([
            "id" => "required",
            "signatureInput" => "required|file",
            "xCoordinate" => "required",
            "yCoordinate" => "required",
            "signatureWidth" => "required",
            "signatureHeight" => "required",
            "page" => "required"
        ]);

        // return $request;

        // return $request;

        return DB::transaction(function () use ($request) {
            $_user = new User;
            $ltp_application = LtpApplication::find($request->id);

            $pdfFile = Storage::disk('s3')->get('request_letters/'.$ltp_application->id.'.pdf');
    
            $ltp_application->application_status = LtpApplication::STATUS_SUBMITTED;
            $ltp_application->save();

            $this->attachSignature(
                $request->signatureInput, 
                $pdfFile, 
                (float) $request->xCoordinate, 
                (float) $request->yCoordinate, 
                (float) $request->signatureWidth, 
                (float) $request->signatureHeight, 
                $request->page, "request_letters/" . 
                $ltp_application->id . ".pdf");

            LtpApplicationProgress::create([                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  
                    "ltp_application_id" => $ltp_application->id,
                    "user_id" => Auth::user()->id,
                    "status" => LtpApplicationProgress::STATUS_SUBMITTED,
                    "description" => "Application has been submitted."
                ]);
    
            Notification::send($_user->getAllInternals(), new LtpApplicationSubmitted($ltp_application));
    
            return Redirect::route('myapplication.index')->with('success', 'Application successfully submitted!');
        });
    }

    private function attachSignature($signatureInput, $pdfFile, float $xPercent, float $yPercent, float $wPercent, float $hPercent, int $page, string $savePath) {
        $tempPdf = tempnam(sys_get_temp_dir(), 'pdf_');
        $tempSignature = tempnam(sys_get_temp_dir(), 'sig_') . '.' . $signatureInput->getClientOriginalExtension();
        $outputPath = tempnam(sys_get_temp_dir(), 'out_') . '.pdf';

        // download from S3
        file_put_contents($tempPdf, $pdfFile);

        // save signature
        file_put_contents($tempSignature, $signatureInput->get());

        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile($tempPdf);

        for ($i = 1; $i <= $pageCount; $i++) {

            $template = $pdf->importPage($i);
            $size = $pdf->getTemplateSize($template);
            // return var_dump($request->xCoordinate);
            // return var_dump($size['width']);

            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($template);

            if ($i == $page) {

                $x = (float)$xPercent;
                $y = (float)$yPercent;
                $w = (float)$wPercent;
                $h = (float)$hPercent;
                
                // 🔥 Let FPDI calculate size automatically
                $pdf->Image(
                    $tempSignature,
                    ($size['width']) * $x,
                    ($size['height']) * $y,
                    ($size['width']) * $w, // width = auto
                    ($size['height']) * $h  // height = auto
                );
            }
        }

        $pdf->Output('F', $outputPath);

        Storage::disk('s3')->put(
                $savePath,
                file_get_contents($outputPath)
            );

        // cleanup
        @unlink($tempPdf);
        @unlink($tempSignature);
        @unlink($outputPath);
    }
}
