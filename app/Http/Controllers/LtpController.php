<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LtpPermit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class LtpController extends Controller
{
    //
    public function index()
    {
        $ltps_query = LtpPermit::query()->with('ltpApplication.permittee');

        return view('admin.data_entry.ltps', [
            'ltps' => $ltps_query->paginate(20)
        ]);
    }

    public function viewPermit($id) {
        $ltpPermit = LtpPermit::find(Crypt::decryptString($id));

        Gate::authorize('viewPermit', $ltpPermit);
        $file = Storage::disk('private')->get('ltps/' . $ltpPermit->ltpApplication->permit->permit_number . '.pdf');
        return response($file)->header('Content-Type', 'application/pdf');
    }
}
