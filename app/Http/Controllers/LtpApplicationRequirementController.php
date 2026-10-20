<?php

namespace App\Http\Controllers;

use App\Models\LtpApplicationAttachment;
use App\Models\LtpRequirement;
use BaconQrCode\Encoder\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Fpdi;

class LtpApplicationRequirementController extends Controller
{
    //
    public function store(Request $request)
    {
        $request->validate([
            'application_id' => 'required',
            'requirement_id' => 'required',
        ]);

        if($request->hasFile('document_file')) {
            /**
            

            /**
             * en pdf qr affixingfrom gpt
            * */ 


            $file = $request->file('document_file');
            $fileName = 'requirement_' . time() . "_" . $request->application_id . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('requirements', $fileName); // store('requirements');
            $attachment = LtpApplicationAttachment::where('ltp_application_id', $request->application_id)->where('ltp_requirement_id', $request->requirement_id)->first();
            if($attachment) {
                $attachment->file_path = $path;
                $attachment->save();
                return redirect()->back()->with('success', 'Successfully saved!');
            }
            else {
                LtpApplicationAttachment::create([
                    "ltp_application_id" => $request->application_id,
                    "ltp_requirement_id" => $request->requirement_id,
                    "file_path" => $path
                ]);
            }
        }
        return redirect()->back()->with('success', 'Successfully saved!');
    }

    public function view(Request $request, string $id) {
        $requirement = LtpRequirement::find(Crypt::decryptString($id));
        $attachment = LtpApplicationAttachment::find(Crypt::decryptString($id));
        Gate::authorize('view', $attachment);

        if (!Storage::disk('s3')->exists($attachment->file_path)) {
            abort(404);
        }

        $stream = Storage::disk('s3')->readStream($attachment->file_path);
        $mimeType = Storage::disk('s3')->mimeType($attachment->file_path);

        return response()->stream(function () use ($stream) {
            fpassthru($stream);
        }, 200, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
        ]);
    }
}
