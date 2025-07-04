<?php

namespace App\Http\Controllers;

use App\Helpers\ApplicationHelper;
use App\Models\LtpApplication;
use App\Models\LtpApplicationProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use App\Models\GeottaggedImage;
use App\Models\InspectionReport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\InspectionVideo;
use App\Models\Position;
use App\Notifications\InspectionProofSubmitted;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Notifications\InspectionReportCreated;

class InspectionController extends Controller
{
    public function index(string $ltp_application_id) {
        $_helper = new ApplicationHelper();
        $ltp_application = LtpApplication::find(Crypt::decryptString($ltp_application_id));

        Gate::authorize('view', $ltp_application);

        $images = $ltp_application->application_status == LtpApplication::STATUS_PAID ? GeottaggedImage::where([
            'ltp_application_id'=> $ltp_application->id,
            'uploaded_by' => auth()->user()->usertype == "permittee" ? "permittee" : "internal"
            ])->get() : GeottaggedImage::where('ltp_application_id', $ltp_application->id)->get();

        $videos = $ltp_application->application_status == LtpApplication::STATUS_PAID ? InspectionVideo::where([
            'ltp_application_id' => $ltp_application->id,
            'uploaded_by' => auth()->user()->usertype == "permittee" ? "permittee" : "internal"
            ])->get() : InspectionVideo::where('ltp_application_id', $ltp_application->id)->get();
        $remarks = $ltp_application->logs()->where('status', LtpApplication::STATUS_INSPECTION_REJECTED)->orderBy('created_at', 'desc')->get();

       
        return view('inspection.index', [
            'ltp_application' => $ltp_application,
            'images' => $images,
            'videos' => $videos,
            'remarks' => $remarks,
            '_helper' => $_helper
        ]);
    }

    public function uploadPhotos(Request $request, string $ltp_application_id) {
        $ltp_application_id = Crypt::decryptString($ltp_application_id);
        $ltp_application = LtpApplication::find($ltp_application_id);

        $validator = Validator::make($request->all(), [
            'photos' => 'required|array',
            'photos.*' => 'required|mimes:jpg,jpeg,png|max:1024'
        ], [
            'photos.required' => 'Please upload at least one photo.',
            'photos.*.required' => 'Each photo is required.',
            'photos.*.file' => 'Each item must be a file.',
            'photos.*.mimes' => 'Only JPG, JPEG, and PNG files are allowed.',
            'photos.*.max' => 'Each photo must be 1MB or smaller.',
        ]);

        if($validator->fails()) {
            session(['forward_url' => url()->current()]);
            return redirect()->back()->withErrors($validator, 'uploadInspectionPhotos')->withInput()->with('error', 'Failed to upload photos');
        }

        Gate::authorize('uploadInspectionProof', $ltp_application);

        return DB::transaction(function () use ($request, $ltp_application, $ltp_application_id) {
            $imagesToInsert = [];
            foreach ($request->photos as $key => $value) {
                $path = $value->storeAs('inspection-photos', time() . "_" . $ltp_application_id . '.' . $value->getClientOriginalExtension(), 'private');
                $imagesToInsert[] = [
                    'ltp_application_id' => $ltp_application->id,
                    'image_url' => $path,
                    'uploaded_by' => auth()->user()->usertype == "permittee" ? "permittee" : "internal"
                ];
            }
            GeottaggedImage::insert($imagesToInsert);
            return redirect()->back()->with('success', 'Photos uploaded successfully!');
        });

    }

    public function uploadVideo(Request $request, string $ltp_application_id) {
        $ltp_application_id = Crypt::decryptString($ltp_application_id);
        $ltp_application = LtpApplication::find($ltp_application_id);

        $validator = Validator::make($request->all(), [
            'video' => 'required|mimes:mp4|max:51200'
        ], [
            'photos.required' => 'Please upload at least one video.',
            'video.required' => 'Each video is required.',
            'video.file' => 'Each item must be a file.',
            'photos.mimes' => 'Only MP4 files are allowed.',
            'video.max' => 'Each video must be 50MB or smaller.',
        ]);

        if($validator->fails()) {
            session(['forward_url' => url()->current()]);
            return redirect()->back()->withErrors($validator, 'uploadInspectionVideo')->withInput()->with('error', 'Failed to upload video.');
        }

        Gate::authorize('uploadInspectionProof', $ltp_application);

        return DB::transaction(function () use ($request, $ltp_application, $ltp_application_id) {
            $path = $request->video->storeAs('inspection-videos', "_" . $ltp_application_id . '.' . $request->video->getClientOriginalExtension(), 'private');
            $inspection = InspectionVideo::where('ltp_application_id', $ltp_application->id)->where('uploaded_by', auth()->user()->usertype == "permittee" ? "permittee" : "internal")->first();
            if ($inspection) {
                $inspection->update([
                    'file_size' => $request->video->getSize(),
                ]);
            }
            else {
                InspectionVideo::create([
                    'ltp_application_id' => $ltp_application->id,
                    'video_url' => $path,
                    'file_size' => $request->video->getSize(),
                    'uploaded_by' => auth()->user()->usertype == "permittee" ? "permittee" : "internal"
                ]);
            }
            
            return redirect()->back()->with('success', 'Video uploaded successfully!');
        });

    }

    public function viewPhoto(string $ltp_application_id, string $id)
    {
        $imageId = Crypt::decryptString($id);
        $image = GeottaggedImage::find($imageId);

        if (!$image) {
            abort(404, 'Image not found');
        }

        $ltp_application_id = Crypt::decryptString($ltp_application_id);
        $ltp_application = LtpApplication::find($ltp_application_id);
        if (!$ltp_application) {
            abort(404, 'Application not found');
        }

        Gate::authorize('inspect', $ltp_application);

        $path = $image->image_url;

        if (!Storage::disk('private')->exists($path)) {
            abort(404, 'File not found in storage');
        }

        return Storage::disk('private')->response($path); // Or ->download($path)
    }

    public function viewVideo(string $ltp_application_id, string $id)
    {
        $videoId = Crypt::decryptString($id);
        $video = InspectionVideo::find($videoId);
        

        if (!$video) {
            abort(404, 'Video not found');
        }

        $ltp_application_id = Crypt::decryptString($ltp_application_id);
        $ltp_application = LtpApplication::find($ltp_application_id);
        if (!$ltp_application) {
            abort(404, 'Application not found');
        }

        Gate::authorize('inspect', $ltp_application);

        $path = $video->video_url;

        if (!Storage::disk('private')->exists($path)) {
            abort(404, 'File not found in storage');
        }

        return Storage::disk('private')->response($path); // Or ->download($path)
    }

    public function downloadPhoto(string $ltp_application_id, string $id) {
        $imageId = Crypt::decryptString($id);
        $image = GeottaggedImage::find($imageId);

        if (!$image) {
            abort(404, 'Image not found');
        }

        $ltp_application_id = Crypt::decryptString($ltp_application_id);
        $ltp_application = LtpApplication::find($ltp_application_id);
        if (!$ltp_application) {
            abort(404, 'Application not found');
        }

        Gate::authorize('inspect', $ltp_application);

        $path = $image->image_url;

        if (!Storage::disk('private')->exists($path)) {
            abort(404, 'File not found in storage');
        }

        return Storage::disk('private')->download($path);
    }

    public function deletePhoto(string $ltp_application_id, string $id) {
        $imageId = Crypt::decryptString($id);
        $image = GeottaggedImage::find($imageId);

        if (!$image) {
            abort(404, 'Image not found');
        }

        $ltp_application_id = Crypt::decryptString($ltp_application_id);
        $ltp_application = LtpApplication::find($ltp_application_id);
        if (!$ltp_application) {
            abort(404, 'Application not found');
        }

        Gate::authorize('inspect', $ltp_application);

        if (Storage::disk('private')->exists($image->image_url)) {
            Storage::disk('private')->delete($image->image_url);
        }

        GeottaggedImage::where('id', $image->id)->delete();
        return redirect()->back()->with('success', 'Image deleted successfully!');
    }

    public function submitProofs(Request $request, string $ltp_application_id) {
        $ltp_application_id = Crypt::decryptString($ltp_application_id);
        $ltp_application = LtpApplication::find($ltp_application_id);
        if (!$ltp_application) {
            abort(404, 'Application not found');
        }

        Gate::authorize('owned', $ltp_application);

        $imageCount = GeottaggedImage::where(['ltp_application_id' => $ltp_application_id, 'uploaded_by' => 'permittee'])->count();

        if ($imageCount <= 0) {
            return redirect()->back()->with('error', 'Please upload at least one image.');
        }

        return DB::transaction(function () use ($request, $ltp_application, $ltp_application_id) {
            $ltp_application->update([
                'application_status' => LtpApplication::STATUS_FOR_INSPECTION
            ]);

            LtpApplicationProgress::create([
                'ltp_application_id' => $ltp_application_id,
                'user_id' => auth()->user()->id,
                'status' => LtpApplication::STATUS_FOR_INSPECTION,
                'description' => "Inspection proof has been submitted by the permittee.",
            ]);

            Notification::send(User::whereIn('usertype', ['admin', 'internal'])->get(), new InspectionProofSubmitted($ltp_application));

            return redirect()->back()->with('success', 'Proofs submitted successfully!');
            
        });

    }

    public function rejectInspection(Request $request, string $ltp_application_id){
        $ltp_application_id = Crypt::decryptString($ltp_application_id);
        $ltp_application = LtpApplication::find($ltp_application_id);

        if (!$ltp_application) {
            abort(404, 'Application not found');
        }

        $request->validate([
            'remarks' => 'required'
        ]);

        Gate::authorize('inspect', $ltp_application);

        $ltp_application->update([
            'application_status' => LtpApplication::STATUS_INSPECTION_REJECTED
        ]); 

        LtpApplicationProgress::create([
            'ltp_application_id' => $ltp_application_id,
            'user_id' => auth()->user()->id,
            'status' => LtpApplication::STATUS_INSPECTION_REJECTED,
            'description' => "LTP Application has been rejected by " . auth()->user()->personalInfo->getFullNameAttribute(),
            'remarks' => $request->remarks,
        ]);
        
        return redirect()->back()->with('success', 'Inspection rejected successfully!');
        // response
    }

    public function approveInspection(Request $request, string $ltp_application_id){
        $ltp_application_id = Crypt::decryptString($ltp_application_id);

        $ltp_application = LtpApplication::find($ltp_application_id);

        if (!$ltp_application) {
            abort(404, 'Application not found');
        }

        Gate::authorize('inspect', $ltp_application);

        $hasImage = GeottaggedImage::where(['ltp_application_id' => $ltp_application_id])->exists();

        if (!$hasImage) {
            return redirect()->back()->with('error', 'Please upload at least one image.');
        }

        return DB::transaction(function () use ($ltp_application, $ltp_application_id) {
            
            $ltp_application->update([
                'application_status' => LtpApplication::STATUS_INSPECTED
            ]); 
    
            LtpApplicationProgress::create([
                'ltp_application_id' => $ltp_application_id,
                'user_id' => auth()->user()->id,
                'status' => LtpApplication::STATUS_INSPECTED,
                'remarks' => "Inspection has been approved by the " . auth()->user()->personalInfo->getFullNameAttribute()
            ]);
    
            return redirect(route('inspection.createReport', Crypt::encryptString($ltp_application_id)))->with('success', 'Inspection approved successfully!');
        });
    }

    public function createReport(Request $request, string $ltp_application_id) {
        $_helper = new ApplicationHelper;
        $_user = new User;
        $_position = new Position;
        
        $ltp_application_id = Crypt::decryptString($ltp_application_id);
        $ltp_application = LtpApplication::find($ltp_application_id);

        if (!$ltp_application) {
            abort(404, 'Application not found');
        }

        Gate::authorize('inspect', $ltp_application);

        $inspection_report = InspectionReport::where(['ltp_application_id' => $ltp_application_id])->first();

        if ($inspection_report) {
            return view('inspection.edit-report', [
                'ltp_application' => $ltp_application,
                'inspection_report' => $inspection_report,
                '_helper' => $_helper,
                "_user" => $_user,
                "_position" => $_position
            ]);
        }

        return view('inspection.create-report', [
            'ltp_application' => $ltp_application,
            'inspection_report' => $inspection_report,
            '_helper' => $_helper,
            "_user" => $_user,
            "_position" => $_position
        ]);
    }

    public function store (Request $request, string $ltp_application_id) {
        $ltp_application_id = Crypt::decryptString($ltp_application_id);
        $ltp_application = LtpApplication::find($ltp_application_id);

        $request->validate([
            'approver' => 'required',
            'approver_position' => 'required'
        ]);

        Gate::authorize('inspect', $ltp_application);

        if(!$request->has('inspection_date')) {
            return redirect()->back()->with('error', 'Inspection date is required.');
        }

        return DB::transaction(function () use ($request, $ltp_application, $ltp_application_id) {
            $inspectionReport = InspectionReport::create([
                'ltp_application_id' => $ltp_application_id,
                'user_id' => $ltp_application->permittee->user_id,
                'inspector_id' => auth()->user()->id,
                'approver_id' => $request->input('approver'),
                'inspection_date' => $request->input('inspection_date'),
                'approver_position' => $request->input('approver_position')
            ]);
    
            $ntf_rcvrs = User::whereIn('id', [$ltp_application->permittee->user_id, $inspectionReport->inspectorId, $inspectionReport->approverId])->get();
    
            Notification::send($ntf_rcvrs, new InspectionReportCreated($ltp_application));
    
            return redirect(route('ltpapplication.index', ['status' => LtpApplication::STATUS_INSPECTED, 'category' => 'accepted']))->with('success', 'Inspection report created successfully!');
        });
    }

    public function update (Request $request, string $ltp_application_id, string $id) {
        $ltp_application_id = Crypt::decryptString($ltp_application_id);
        $ltp_application = LtpApplication::find($ltp_application_id);

        Gate::authorize('inspect', $ltp_application);

        $inspection_report = InspectionReport::find(Crypt::decryptString($id));

        $request->validate([
            'approver' => 'required',
            'approver_position' => 'required'
        ]);

        if(!$request->has('inspection_date')) {
            return redirect()->back()->with('error', 'Inspection date is required.');
        }

        $inspection_report->update([
            'approver_id' => $request->input('approver'),
            'inspection_date' => $request->input('inspection_date'),
            'approver_position' => $request->input('approver_position')
        ]);

        return redirect(route('ltpapplication.index', ['status' => LtpApplication::STATUS_INSPECTED, 'category' => 'accepted']))->with('success', 'Inspection report updated successfully!');
    }

    public function print (Request $request, string $ltp_application_id, string $id) {
        $ltp_application_id = Crypt::decryptString($ltp_application_id);
        $ltp_application = LtpApplication::find($ltp_application_id);

        $_helper = new ApplicationHelper;
        $_user = new User;
        $_position = new Position;

        Gate::authorize('inspect', $ltp_application);

        $inspection_report = InspectionReport::find(Crypt::decryptString($id));

        return view('inspection.print-report', [
            'ltp_application' => $ltp_application,
            'inspection_report' => $inspection_report,
            '_helper' => $_helper,
            "_user" => $_user,
            "_position" => $_position
        ]);
    }

    public function uploadDocument(Request $request, string $ltp_application_id, string $id) {
        $ltp_application_id = Crypt::decryptString($ltp_application_id);
        $id = Crypt::decryptString($id);
        $ltp_application = LtpApplication::find($ltp_application_id);
        $inspectionReport = InspectionReport::find($id);

        $validator = Validator::make($request->all(), [
            'document' => 'required|mimes:pdf|max:10485760'
        ]);

        Gate::authorize('uploadDocument', $inspectionReport);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator, 'uploadDocument')->withInput()->with('error', 'Failed to upload document.');
        }

        return DB::transaction(function () use ($request, $ltp_application, $id) {
            $request->file('document')->storeAs('inspection_report', $id . '.' . $request->file('document')->getClientOriginalExtension(), 'private');

            return redirect()->back()->with('success', 'Document uploaded successfully!');
        });
    }

    public function downloadDocument(Request $request, string $ltp_application_id, string $id) {
        $ltp_application_id = Crypt::decryptString($ltp_application_id);
        $id = Crypt::decryptString($id);
        $ltp_application = LtpApplication::find($ltp_application_id);
        $inspectionReport = InspectionReport::find($id);

        Gate::authorize('downloadDocument', $inspectionReport);

        return Storage::disk('private')->response('inspection_report/' . $id . '.' . 'pdf');
    }
}
