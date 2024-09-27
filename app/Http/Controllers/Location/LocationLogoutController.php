<?php

namespace App\Http\Controllers\Location;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class LocationLogoutController extends Controller
{
    public function index(Request $request)
    {
        Auth::guard('location')->logout();

        return redirect()->route('login');
    }
}