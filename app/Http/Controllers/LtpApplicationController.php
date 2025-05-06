<?php

namespace App\Http\Controllers;

use App\Models\LtpApplication;
use App\Models\Permittee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Helpers\ApplicationHelper;
use App\Models\LtpApplicationProgress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        if(in_array($ltp_application->application_status, [LtpApplication::STATUS_SUBMITTED, LtpApplication::STATUS_RESUBMITTED])) {
            $ltp_application->application_status = LtpApplication::STATUS_UNDER_REVIEW;
            $ltp_application->save();

            LtpApplicationProgress::create([
                "ltp_application_id" => $ltp_application->id,
                "user_id" => Auth::user()->id,
                "status" => LtpApplicationProgress::STATUS_UNDER_REVIEW
            ]);
        }

        $permittee = Permittee::find($ltp_application->permittee_id);

        return view('admin.ltpapplications.review', [
            '_helper' => $_helper,
            'title' => 'LTP Application',
            "ltp_application" => $ltp_application,
            "permittee" => $permittee
        ]);
    }

    public function return(Request $request) {

        $request->validate([
            'id' => 'required',
            'remarks' => 'required'
        ]);

        return DB::transaction(function () use ($request) {
            $id = $request->id;
            $remarks = $request->remarks;
    
            LtpApplication::find($id)->update([
                "application_status" => LtpApplication::STATUS_RETURNED,
            ]);
    
            LtpApplicationProgress::create([
                "ltp_application_id" => $id,
                "user_id" => Auth::user()->id,
                "status" => LtpApplicationProgress::STATUS_RETURNED,
                "remarks" => $remarks
            ]);
    
            return redirect()
                    ->back()
                    ->with([
                        'success' => 'Successfully returned application.'
                    ]);
        });

    }

    public function renderLogs(Request $request) {
        $_helper = new ApplicationHelper;

        $logs = LtpApplicationProgress::where('ltp_application_id', $request->application_id)
        ->with('user.personalInfo')
        ->orderBy('created_at', 'DESC')
        ->get();

        return view('admin.ltpapplications.logs', [
            '_helper' => $_helper,
            'title' => 'LTP Application Logs',
            "logs" => $logs
        ]);
    }

    public function accept(string $id) {
        return DB::transaction(function () use ($id) {
            $ltp_application_id = Crypt::decryptString($id);
            $ltp_application = LtpApplication::query()->with(["permittee.user"])->find($ltp_application_id);

            if(!Permittee::validatePermit(Permittee::PERMIT_TYPE_WCP , $ltp_application->permittee->user->id) || !Permittee::validatePermit(Permittee::PERMIT_TYPE_WFP , $ltp_application->permittee->user->id)) {
                return redirect()->back()->with('error', 'The clients WCP and/or WFP permit has expired or is not valid. Please renew the permit before submitting the application.');
            }

            if(!LtpApplication::validateRequirements($ltp_application->id)) {
                return redirect()->back()->with('error', 'Application does not have all required attachments!');
            }

            LtpApplication::find($ltp_application_id)->update([
                "application_status" => LtpApplication::STATUS_ACCEPTED,
            ]);

            LtpApplicationProgress::create([
                "ltp_application_id" => $ltp_application_id,
                "user_id" => Auth::user()->id,
                "status" => LtpApplicationProgress::STATUS_ACCEPTED
            ]);

            // send notification

            return redirect()
                    ->route('ltpapplication.index')
                    ->with([
                        'success' => 'Successfully accepted application.'
                    ]);
        });
    }
}
