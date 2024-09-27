<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mail;
use Auth;
use Validator;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $data['set'] = 'dashboard';
        return view('admin.dashboard.dashboard',$data);
    }
}