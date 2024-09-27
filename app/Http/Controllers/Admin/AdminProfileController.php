<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Validator;
use App\Models\User;

class AdminProfileController extends Controller
{
    public function index(Request $request)
    {
        if($request->has('submit'))
        {
            $rules = ['user_name' => 'required',
                      'user_email' => 'required|email',
                      'user_phone' => 'required',
                      'user_password' => 'required'
                      ];
                
            $messages = ['user_name.required' => 'Please Enter Name',
                         'user_email.required' => 'Please Enter Email Address',
                         'user_email.email' => 'Please Enter Valid Email Address',
                         'user_phone.required' => 'Please Enter Phone',
                         'user_password.required' => 'Please Enter Password'
                        ];
            
            $validator = Validator::make($request->all(),$rules,$messages);
            
            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $where_email['user_email'] = $request->user_email;
                $check_email = User::where($where_email)->where('user_id','!=',Auth::user()->user_id)->count();
                
                if($check_email > 0)
                {
                    return redirect()->back()->with('error','Email Address Already Exists')->withInput();
                }
                                
                $upd['user_name']       = $request->user_name;
                $upd['user_email']      = $request->user_email;
                $upd['user_phone']      = $request->user_phone;
                $upd['user_password']   = bcrypt($request->user_password);
                $upd['user_vpassword']  = base64_encode($request->user_password);
                $upd['user_updated_on'] = date('Y-m-d H:i:s');
                $upd['user_updated_by'] = Auth::user()->user_id;

                if($request->hasFile('user_image'))
                {
                    $user_image = $request->user_image->store('assets/admin/uploads/profile');
                    
                    $user_image = explode('/',$user_image);
                    $user_image = end($user_image);
                    $upd['user_image'] = $user_image;
                }
                
                $user = User::where('user_id',Auth::user()->user_id)->update($upd);

                return redirect()->back()->with('success','Details Updated Successfully');
            }
        }
        
        $data['set'] = 'profile';
        return view('admin.profile.profile',$data);
    }
}