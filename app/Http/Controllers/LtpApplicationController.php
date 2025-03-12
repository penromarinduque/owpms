<?php

namespace App\Http\Controllers;

use App\Models\LtpApplication;
use Illuminate\Http\Request;

class LtpApplicationController extends Controller
{
    //
    public function index() {
        $status = $request->status ?? 'submitted';

        $ltp_applications = LtpApplication::where([
            'application_status' => $status
        ]);

        return view('admin.ltpapplications.index', [
            'title' => 'LTP Applications',
            "ltp_applications" => $ltp_applications->paginate(50)
        ]);
    }
}
