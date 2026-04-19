<?php

namespace App\Http\Controllers;

use App\Helpers\ApplicationHelper;
use App\Models\LtpApplication;
use App\Models\LtpApplicationProgress;
use App\Models\Permittee;
use App\Models\User;
use App\Notifications\LtpApplicationSubmitted;
use Illuminate\Container\Attributes\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redirect;
use Spatie\LaravelPdf\Enums\Format;
use Spatie\LaravelPdf\Enums\Unit;
use Spatie\LaravelPdf\Facades\Pdf;

class WebhookController extends Controller
{
    //
    public function requestLetterSigned(Request $request) {
        return DB::transaction(function () use ($request) {
            $_user = new User;
            $_helper = new ApplicationHelper;
            $_permittee = new Permittee;
            $ltp_application = LtpApplication::find($request->id);
            
            $wfp = $_permittee->getPermitteeWFP($ltp_application->permittee->user_id, "wfp");
            $wcp = $_permittee->getPermitteeWCP($ltp_application->permittee->user_id, "wcp");
            // $signatureFilename = auth()->user()->id.$request->file('signatureInput')->getClientOriginalName();
            // $request->file('signatureInput')->storeAs('temp_signatures', $signatureFilename, 's3');
            // Pdf::view("pdfs.request-letter", [
            //     "_helper" => $_helper,
            //     "application" => $ltp_application,
            //     "wfp" => $wfp,
            //     "wcp" => $wcp,
            //     "signature" =>  (object)[
            //         "url" => Storage::disk('s3')->url('temp_signatures/'.$signatureFilename),
            //         "xCoordinate" => $request->xCoordinate,
            //         "yCoordinate" => $request->yCoordinate,
            //     ]
            // ])
            // ->format(Format::A4)
            // ->scale(0.9)
            // ->margins(0.2, 0.5, 0.5, 0.3, Unit::Inch)
            // ->disk("s3")
            // ->save('request_letters/'.$ltp_application->id.'.pdf');
    
            $ltp_application->application_status = LtpApplication::STATUS_SUBMITTED;
            $ltp_application->save();
            
            LtpApplicationProgress::create([
                    "ltp_application_id" => $ltp_application->id,
                    "user_id" => Auth::user()->id,
                    "status" => LtpApplicationProgress::STATUS_SUBMITTED,
                    "description" => "Application has been submitted."
                ]);
    
            Notification::send($_user->getAllInternals(), new LtpApplicationSubmitted($ltp_application));
    
            return Redirect::route('myapplication.index')->with('success', 'Application successfully submitted!');
        })
    }
}
