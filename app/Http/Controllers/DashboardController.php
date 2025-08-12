<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ApplicationHelper;
use App\Models\LtpApplication;
use App\Models\Permittee;
use App\Models\Specie;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $_helper = new ApplicationHelper;
        $_user = new User;
        $_specie = new Specie;
        $_permittee = new Permittee();

        // Fetching counts for the dashboard
        $applicationCounts = auth()->user()->usertype == 'permittee' ?  (object)[
            'created' => LtpApplication::where('permittee_id', auth()->user()->wcp()->id)->count(),
            'approved' => LtpApplication::where('permittee_id', auth()->user()->wcp()->id)
                ->whereIn('application_status', [...$_helper->identifyApplicationStatusesByCategory('approved')])->count(),
            'expired' => LtpApplication::where('permittee_id', auth()->user()->wcp()->id)
                ->whereIn('application_status', [...$_helper->identifyApplicationStatusesByCategory('expired')])->count(),
        ] : [];

        return view('dashboard', [
            'title' => 'Dashboard',
            '_user' => $_user,
            '_helper' => $_helper,
            '_specie' => $_specie,
            '_permittee' => $_permittee,
            'applicationCounts' => $applicationCounts,
        ]);
    }
}
