<?php

namespace App\Http\Controllers;

use App\Helpers\ApplicationHelper;
use App\Models\LtpApplication;
use App\Models\LtpApplicationProgress;
use App\Models\LtpPermit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class QrController extends Controller
{
    //
    public function index(Request $request) {
        $_helper = new ApplicationHelper;
        $request->validate([
            'id' => 'required|string',
            'document_type' => 'required|string|in:ltp'
        ]);
        $id = $_helper->decodeHashId($request->id);

        $permitUrl = Storage::disk('s3')->temporaryUrl("ltps/" . LtpPermit::find($id)->ltpApplication->permit->permit_number . ".pdf", now()->addMinutes(30));
        // return
        if($request->document_type == 'ltp') {
            $permit = LtpPermit::find($id);
            $ltp_application = $permit->ltpApplication;
            $logs = LtpApplicationProgress::where("ltp_application_id", $ltp_application->id)->orderBy("created_at", "desc")->get();
            return view('qr.index', [
                'ltp_application' => $ltp_application,
                'permit' => $permit,
                'logs' => $logs,
                'permitUrl' => $permitUrl
            ]);
        }
    }


}
