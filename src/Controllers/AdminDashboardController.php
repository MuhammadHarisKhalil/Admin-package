<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Dashboard 
     *
     * @return void
     */
    public function dasboard()
    {
        return view('backend_web.dashboard.dashboard');
    }
}
