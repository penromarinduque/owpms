<?php

namespace App\Http\Controllers;

use App\Models\LtpApplication;
use App\Models\LtpApplicationProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class QrController extends Controller
{
    //
    public function index(Request $request) {
        $ltp_application = LtpApplication::find(Crypt::decryptString($request->id));
        $logs = LtpApplicationProgress::where("ltp_application_id", $ltp_application->id)->orderBy("created_at", "desc")->get();
        return view('qr.index', [
            'ltp_application' => $ltp_application,
            'logs' => $logs
        ]);
    }


}
