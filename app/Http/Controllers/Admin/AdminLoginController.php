<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mail;
use Auth;
use Validator;
use App\Models\User;

class AdminLoginController extends Controller
{
    public function index(Request $request)
    {
        if($request->has('submit'))
        {
            $rules = ['user_email' => 'required|email',
                      'user_password' => 'required'];
            
            $messages = ['user_email.required' => 'Please Enter Email Address',
                         'user_email.email' => 'Please Enter Valid Email Address',
                         'user_password.required' => 'Please Enter Password'];
            
            $validator = Validator::make($request->all(),$rules,$messages);
            
            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $where['user_email'] = $request->user_email;
                $where['password'] = $request->user_password;
                
                if(Auth::attempt($where))
                {
                    if(Auth::user()->user_status == 1)
                    {
                        return redirect()->route('admin.dashboard');
                    }
                    else
                    {
                        Auth::logout();

                        return redirect()->route('admin')->with('error','Invalid Email or Password!')->withInput();
                    }
                }
                else
                {
                    return redirect()->route('admin')->with('error','Invalid Email or Password!')->withInput();
                }
            }
        }

        $data['set'] = 'login';
        return view('admin.login.login',$data);
    }

    public function forgot_password(Request $request)
    {
        if($request->has('submit'))
        {
            $rules = ['user_email' => 'required|email'];
            
            $messages = ['user_email.required' => 'Please Enter Email Address',
                         'user_email.email' => 'Please Enter Valid Email Address',
                        ];
                        
            $validator = Validator::make($request->all(),$rules,$messages);
            
            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $user = User::where('user_email',$request->user_email)->first();
                
                if(isset($user))
                {
                    $mail  = $request->user_email;

                    $uname = $data['name'] = $user->user_name;
                    $data['password'] = base64_decode($user->user_vpassword);
                    
                    $send = Mail::send('admin.mail.forgot_password', $data, function($message) use ($mail, $uname){
                         $message->to($mail, $uname)->subject(config('constants.site_title').' - Forgot Password');
                         $message->from(config('constants.mail_from'),config('constants.site_title'));
                      });
                      
                    return redirect()->route('admin')->with('success','Please Check Your Email');
                }
                else
                {
                    return redirect()->back()->with('error','Invalid Email Address')->withInput();
                }
            }
        }

        return view('admin.login.forgot_password');
    }
}