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
use App\Models\LtpPermit;
use App\Models\User;
use App\Notifications\LtpApplicationAccepted;
use App\Notifications\LtpApplicationReleased;
use App\Notifications\LtpApplicationReturned;
use App\Notifications\LtpApplicationReviewDone;
use App\Notifications\LtpApplicationReviewed;
use App\Notifications\LtpPermitCreated;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class LtpApplicationController extends Controller
{
    //
    public function index(Request $request) { 
        $status = $request->status ?? 'all';
        $category = $request->category ?? "submitted";
        
        $_helper = new ApplicationHelper;
        $_ltp_requirement = new LtpRequirement;

        $ltp_application_query = LtpApplication::query();

        $this->checkIndexAuthorization($category);

        if($status == 'all') {
            $ltp_applications = $ltp_application_query->whereIn('application_status', $_helper->identifyApplicationStatusesByCategory($category));
        }
        else {
            $ltp_applications = $ltp_application_query->where([
                'application_status' => $status
            ]);
        }

        $ltp_applications = $ltp_application_query;
        
        return view('admin.ltpapplications.index', [
            '_helper' => $_helper,
            '_ltp_application' => new LtpApplication,
            'title' => 'LTP Applications',
            "ltp_applications" => $ltp_applications->orderBy('created_at', 'DESC')->paginate(50),
            "_ltp_requirement" => $_ltp_requirement
        ]);
    }

    public function review(string $id){
        try {
            $_helper = new ApplicationHelper;
            $_ltp_requirement = new LtpRequirement;

            $application_id = Crypt::decryptString($id);
            $ltp_application = LtpApplication::query()->with(['attachments'])->find($application_id);
            $ltp_requirements = $_ltp_requirement->getActiveRequirements();

            Gate::authorize('review', $ltp_application);

            if(in_array($ltp_application->application_status, [LtpApplication::STATUS_SUBMITTED, LtpApplication::STATUS_RESUBMITTED])) {
                $ltp_application->application_status = LtpApplication::STATUS_UNDER_REVIEW;
                $ltp_application->save();

                LtpApplicationProgress::create([
                    "ltp_application_id" => $ltp_application->id,
                    "user_id" => Auth::user()->id,
                    "status" => LtpApplicationProgress::STATUS_UNDER_REVIEW
                ]);

                Notification::send($ltp_application->permittee->user, new LtpApplicationReviewed($ltp_application));
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
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'remarks' => 'required'
        ]);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'return')->withInput();
        }

        return DB::transaction(function () use ($request) {
            $id = $request->id;
            $remarks = $request->remarks;
    
            $ltp_application = LtpApplication::find($id);

            Gate::authorize('return', $ltp_application);

            $ltp_application->application_status = LtpApplication::STATUS_RETURNED;
            $ltp_application->save();
    
            LtpApplicationProgress::create([
                "ltp_application_id" => $id,
                "user_id" => Auth::user()->id,
                "status" => LtpApplicationProgress::STATUS_RETURNED,
                "remarks" => $remarks
            ]);

            Notification::send($ltp_application->permittee->user, new LtpApplicationReturned($ltp_application));
    
            return redirect()->back()->with([
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

    public function reviewed(Request $request,string $id) {
        return DB::transaction(function () use ($id, $request) {
            $_ltp_requirement = new LtpRequirement;

            $ltp_application_id = Crypt::decryptString($id);

            $ltp_application = LtpApplication::query()->with(["permittee.user"])->find($ltp_application_id);

            Gate::authorize('review', $ltp_application);

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
                "application_status" => LtpApplication::STATUS_REVIEWED,
            ]);

            LtpApplicationProgress::create([
                "ltp_application_id" => $ltp_application_id,
                "user_id" => Auth::user()->id,
                "status" => LtpApplicationProgress::STATUS_REVIEWED
            ]);

            Notification::send($ltp_application->permittee->user, new LtpApplicationReviewDone($ltp_application));

            return redirect()->route('ltpapplication.index', ['status' => LtpApplication::STATUS_REVIEWED, 'category' => 'reviewed'])->with('success', 'Successfully reviewed application.');
        });
    }

    public function accept(Request $request,string $id) {
        return DB::transaction(function () use ($id, $request) {
            $_ltp_requirement = new LtpRequirement;

            $ltp_application_id = Crypt::decryptString($id);

            $ltp_application = LtpApplication::query()->with(["permittee.user"])->find($ltp_application_id);

            Gate::authorize('accept', $ltp_application);

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

            return redirect()->route('ltpapplication.index', ['status' => LtpApplication::STATUS_ACCEPTED, 'category' => 'accepted'])->with('success', 'Successfully accepted application. You can visit the accepted tab to generate payment orders.');
        });
    }

    public function permit(Request $request, string $id) {
        $ltp_application = LtpApplication::find(Crypt::decryptString($id));
        $_user = new User();

        $permit = LtpPermit::where('ltp_application_id', $ltp_application->id)->first();

        Gate::authorize('generateLtp', $ltp_application);

        if($permit) {
            return redirect()->route('ltpapplication.editPermit', ['id' => Crypt::encryptString($ltp_application->id)]);
        }

        return view('admin.ltpapplications.permit', [
            'ltp_application' => $ltp_application,
            '_user' => $_user
        ]);
    }

    public function createPermit(Request $request, string $id) {
        $ltp_application = LtpApplication::find(Crypt::decryptString($id)); 
        $request->validate([
            'approver' => ['required', Rule::notIn([$request->input('chief_rps'), $request->input('chief_tsd')])],
            'permit_no' => 'required',
            'approver_designation' => 'required',
            'chief_tsd' => ['required', Rule::notIn([$request->input('approver'),$request->input('chief_rps')])],
            'chief_rps' => ['required', Rule::notIn([$request->input('approver'), $request->input('chief_tsd')])],
            'transport_date' => 'required',
        ], [
            'approver.not_in' => 'Approver must be different from Chief RPS and Chief TSD.',
            'chief_tsd.not_in' => 'Chief TSD must be different from Approver and Chief RPS.',
            'chief_rps.not_in' => 'Chief RPS must be different from Approver and Chief TSD.',
        ]);

        Gate::authorize('generateLtp', $ltp_application);
        
        return DB::transaction(function () use ($ltp_application, $request) {
            
            LtpPermit::create([
                'ltp_application_id' => $ltp_application->id,
                'permit_number' => $request->input('permit_no'),
                'penro' => $request->input('approver'),
                'chief_tsd' => $request->input('chief_tsd'),
                'chief_rps' => $request->input('chief_rps'),
                'approver_designation' => $request->input('approver_designation')
            ]);

            $ltp_application->transport_date = $request->input('transport_date');
            $ltp_application->save();
    
            Notification::send($ltp_application->permittee->user, new LtpPermitCreated($ltp_application));

            return redirect()->back()->with('success', 'Successfully created permit.');
        });
    }

    public function editPermit(Request $request, string $id) {
        $ltp_application = LtpApplication::find(Crypt::decryptString($id));
        $permit = $ltp_application->permit;

        Gate::authorize('generateLtp', $ltp_application);

        return view('admin.ltpapplications.edit-permit', [
            'ltp_application' => $ltp_application,
            'permit' => $permit,
            '_user' => new User
        ]);
    }

    public function updatePermit(Request $request, string $id) {
        $ltp_application = LtpApplication::find(Crypt::decryptString($id)); 
        $request->validate([
            'approver' => ['required', Rule::notIn([$request->input('chief_rps'), $request->input('chief_tsd')])],
            'permit_no' => 'required',
            'approver_designation' => 'required',
            'chief_tsd' => ['required', Rule::notIn([$request->input('approver'),$request->input('chief_rps')])],
            'chief_rps' => ['required', Rule::notIn([$request->input('approver'), $request->input('chief_tsd')])],
            'transport_date' => 'required',
        ], [
            'approver.not_in' => 'Approver must be different from Chief RPS and Chief TSD.',
            'chief_tsd.not_in' => 'Chief TSD must be different from Approver and Chief RPS.',
            'chief_rps.not_in' => 'Chief RPS must be different from Approver and Chief TSD.',
        ]);

        Gate::authorize('updateLtp', $ltp_application);
        
        return DB::transaction(function () use ($ltp_application, $request) {
            
            LtpPermit::where([
                'ltp_application_id' => $ltp_application->id
            ])->update([
                'permit_number' => $request->input('permit_no'),
                'penro' => $request->input('approver'),
                'chief_tsd' => $request->input('chief_tsd'),
                'chief_rps' => $request->input('chief_rps'),
                'approver_designation' => $request->input('approver_designation')
            ]);

            $ltp_application->transport_date = $request->input('transport_date');
            $ltp_application->save();   
    
            // Notification::send($ltp_application->permittee->user, new LtpPermitCreated($ltp_application));

            return redirect()->back()->with('success', 'Successfully updated permit.');
        });
    }

    public function releaseLtp(Request $request, string $id) {
        $ltp_application = LtpApplication::find(Crypt::decryptString($id));

        $validator = Validator::make($request->all() + $request->allFiles(), [
            'ltp' => 'required|mimetypes:application/pdf'
        ]);

        if($validator->fails()) {
            session(['forward_url' => url()->current()]);
            return redirect()->back()->withErrors($validator, 'releaseLtp')->withInput()->with('error', 'Failed to release LTP.');
        }

        Gate::authorize('releaseLtp', $ltp_application);

        return DB::transaction(function () use ($ltp_application, $request) {
            // update application status and progress
            $ltp_application->application_status = LtpApplication::STATUS_RELEASED;
            $ltp_application->save();
            LtpApplicationProgress::create([
                'ltp_application_id' => $ltp_application->id,
                'user_id' => Auth::user()->id,
                'status' => LtpApplication::STATUS_RELEASED,
                'remarks' => 'LTP has been released by ' . Auth::user()->personalInfo->getFullNameAttribute()
            ]);

            $permit = $ltp_application->permit;

            $request->file('ltp')->storeAs('ltps', $permit->permit_number . '.pdf');

            Notification::send($ltp_application->permittee->user, new LtpApplicationReleased($ltp_application));

            return redirect()->back()->with('success', 'Successfully released LTP.');
        });

    }

    private function checkIndexAuthorization($category) {
        if($category == 'submitted') {
            Gate::authorize('viewSubmittedTab', LtpApplication::class);
        }
        if($category == 'reviewed') {
            Gate::authorize('viewReviewedTab', LtpApplication::class);
        }
        // else if($category == 'approved') {
        //     Gate::authorize('viewAnyApprovedLtp', LtpApplication::class);
        // }
        // else if($category == 'expired') {
        //     Gate::authorize('viewAnyExpiredLtp', LtpApplication::class);
        // }
        // else if($category == 'returned') {
        //     Gate::authorize('viewAnyReturnedLtp', LtpApplication::class);
        // }
    }
}
