<?php

namespace App\Http\Controllers;

use App\Models\LtpApplicationAttachment;
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
            $fileName = 'requirement_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('requirements', $fileName, 'private'); // store('requirements');
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
        $attachment = LtpApplicationAttachment::find(Crypt::decryptString($id));

        Gate::authorize('view', $attachment);

        $path = storage_path('app/private/' . $attachment->file_path);
        return response()->file($path);
    }
}
