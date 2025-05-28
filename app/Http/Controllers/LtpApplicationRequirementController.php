<?php

namespace App\Http\Controllers;

use App\Models\LtpApplicationAttachment;
use Illuminate\Http\Request;

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
            $file = $request->file('document_file');
            $fileName = 'requirement_' . time() . $file->getClientOriginalExtension();
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
}
