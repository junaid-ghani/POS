<?php

namespace App\Http\Controllers\Location;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mail;
use Auth;
use Validator;

class LocationDashboardController extends Controller
{
    public function index(Request $request)
    {
        
        return redirect()->route('location.create_sale',Auth::guard('location')->id());
        
        $data['set'] = 'dashboard';        
        return view('location.dashboard.dashboard',$data);
    }
}