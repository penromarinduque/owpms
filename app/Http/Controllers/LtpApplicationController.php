<?php

namespace App\Http\Controllers;

use App\Models\LtpApplication;
use App\Models\Permittee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Helpers\ApplicationHelper;

class LtpApplicationController extends Controller
{
    //
    public function index(Request $request){ 
        $status = $request->status ?? 'submitted';
        $_helper = new ApplicationHelper;

        $ltp_applications = LtpApplication::where([
            'application_status' => $status
        ])
        ->orderBy('created_at', 'DESC');

        return view('admin.ltpapplications.index', [
            '_helper' => $_helper,
            'title' => 'LTP Applications',
            "ltp_applications" => $ltp_applications->paginate(50)
        ]);
    }

    public function review(string $id){
        $_helper = new ApplicationHelper;
        $application_id = Crypt::decryptString($id);

        $ltp_application = LtpApplication::query()->with(['attachments'])->find($application_id);

        if($ltp_application->application_status == LtpApplication::STATUS_SUBMITTED) {
            $ltp_application->application_status = LtpApplication::STATUS_UNDER_REVIEW;
            $ltp_application->save();
        }

        $permittee = Permittee::find($ltp_application->permittee_id);

        return view('admin.ltpapplications.review', [
            '_helper' => $_helper,
            'title' => 'LTP Application',
            "ltp_application" => $ltp_application,
            "permittee" => $permittee
        ]);
    }
}
