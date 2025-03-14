<?php

namespace App\Http\Controllers;

use App\Models\LtpApplication;
use App\Models\Permittee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class LtpApplicationController extends Controller
{
    //
    public function index(Request $request){ 
        $status = $request->status ?? 'submitted';

        $ltp_applications = LtpApplication::where([
            'application_status' => $status
        ])
        ->orderBy('created_at', 'DESC');

        return view('admin.ltpapplications.index', [
            'title' => 'LTP Applications',
            "ltp_applications" => $ltp_applications->paginate(50)
        ]);
    }

    public function review(string $id){
        $application_id = Crypt::decryptString($id);

        $ltp_application = LtpApplication::find($application_id);

        if($ltp_application->application_status == LtpApplication::STATUS_SUBMITTED) {
            $ltp_application->application_status = LtpApplication::STATUS_UNDER_REVIEW;
            $ltp_application->save();
        }

        $permittee = Permittee::find($ltp_application->permittee_id);


        return view('admin.ltpapplications.review', [
            'title' => 'LTP Application',
            "ltp_application" => $ltp_application,
            "permittee" => $permittee
        ]);
    }
}
