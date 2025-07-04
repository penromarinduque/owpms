<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ApplicationHelper;
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

        return view('dashboard', [
            'title' => 'Dashboard',
            '_user' => $_user,
            '_helper' => $_helper,
            '_specie' => $_specie,
            '_permittee' => $_permittee
        ]);
    }
}
