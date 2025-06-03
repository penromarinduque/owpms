<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ApplicationHelper;

class DashboardController extends Controller
{
    public function index()
    {
        $_helper = new ApplicationHelper;
        return view('dashboard', [
            'title' => 'Dashboard',
            '_helper' => $_helper
        ]);
    }
}
