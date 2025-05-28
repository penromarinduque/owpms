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
use App\Models\LtpRequirement;
use App\Models\LtpFee;
use App\Notifications\LtpApplicationAccepted;
use App\Notifications\LtpApplicationReturned;
use App\Notifications\LtpApplicationReviewed;
use Illuminate\Support\Facades\Notification;

class LtpApplicationController extends Controller
{
    //
    public function index(Request $request) { 
        $status = $request->status ?? 'submitted';
        $_helper = new ApplicationHelper;

        $ltp_applications = LtpApplication::where([
            'application_status' => $status
        ])
        ->orderBy('created_at', 'DESC');

        return view('admin.ltpapplications.index', [
            '_helper' => $_helper,
            '_ltp_application' => new LtpApplication,
            'title' => 'LTP Applications',
            "ltp_applications" => $ltp_applications->paginate(50)
        ]);
    }

    public function review(string $id){
        try {
            $_helper = new ApplicationHelper;
            $_ltp_requirement = new LtpRequirement;

            $application_id = Crypt::decryptString($id);
            $ltp_application = LtpApplication::query()->with(['attachments'])->find($application_id);
            $ltp_requirements = $_ltp_requirement->getActiveRequirements();

            if(in_array($ltp_application->application_status, [LtpApplication::STATUS_SUBMITTED, LtpApplication::STATUS_RESUBMITTED])) {
                $ltp_application->application_status = LtpApplication::STATUS_UNDER_REVIEW;
                $ltp_application->save();

                LtpApplicationProgress::create([
                    "ltp_application_id" => $ltp_application->id,
                    "user_id" => Auth::user()->id,
                    "status" => LtpApplicationProgress::STATUS_UNDER_REVIEW
                ]);

                Notification::send($ltp_application->permittee, new LtpApplicationReviewed($ltp_application));
            }

            $permittee = Permittee::find($ltp_application->permittee_id);

            return view('admin.ltpapplications.review', [
                '_helper' => $_helper,
                'title' => 'LTP Application',
                "ltp_application" => $ltp_application,
                "permittee" => $permittee,
                "ltp_requirements" => $ltp_requirements
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
        
    }

    public function return(Request $request) {

        $request->validate([
            'id' => 'required',
            'remarks' => 'required'
        ]);

        return DB::transaction(function () use ($request) {
            $id = $request->id;
            $remarks = $request->remarks;
    
            $ltp_application = LtpApplication::find($id)->update([
                "application_status" => LtpApplication::STATUS_RETURNED,
            ]);
    
            LtpApplicationProgress::create([
                "ltp_application_id" => $id,
                "user_id" => Auth::user()->id,
                "status" => LtpApplicationProgress::STATUS_RETURNED,
                "remarks" => $remarks
            ]);

            Notification::send(LtpApplication::find($id)->permittee, new LtpApplicationReturned($ltp_application));
    
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

    public function accept(Request $request,string $id) {
        return DB::transaction(function () use ($id, $request) {
            $_ltp_requirement = new LtpRequirement;

            $ltp_application_id = Crypt::decryptString($id);

            $ltp_application = LtpApplication::query()->with(["permittee.user"])->find($ltp_application_id);

            if(!$_ltp_requirement->checkIfMandatoryRequirementsExist($request->input('req') ?? [])) {
                return redirect()->back()->with('error', 'Error: You forgot to physically check mandatory requirements, make sure the all mandatory attachments are submitted physically before accepting the application.');
            }

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

            Notification::send($ltp_application->permittee->user, new LtpApplicationAccepted($ltp_application));

            return redirect()->route('ltpapplication.index', ['status' => LtpApplication::STATUS_ACCEPTED])->with('success', 'Successfully accepted application. You can visit the accepted tab to generate payment orders.');
        });
    }

    
}
