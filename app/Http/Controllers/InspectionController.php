<?php

namespace App\Http\Controllers;

use App\Models\LtpApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Gate;

class InspectionController extends Controller
{
    public function index(string $ltp_application_id) {
        $ltp_application = LtpApplication::find(Crypt::decryptString($ltp_application_id));

        Gate::authorize('view', $ltp_application);

        return view('inspection.index', [
            'ltp_application' => $ltp_application,
        ]);
    }
}
