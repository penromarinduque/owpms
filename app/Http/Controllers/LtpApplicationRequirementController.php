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
            $path = $file->store('requirements');
            LtpApplicationAttachment::create([
                "ltp_application_id" => $request->application_id,
                "ltp_requirement_id" => $request->requirement_id,
                "file_path" => $path
            ]);
        }
        return $request;
    }
}
