<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function renterDashboard()
    {
        return view('dashboards.renter');
    }

    public function ownerDashboard()
    {
        return view('dashboards.owner');
    }
}