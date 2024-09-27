<?php

namespace App\Http\Controllers\Location;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mail;
use Auth;
use Validator;
use App\Models\Location;

class LocationLoginController extends Controller
{
    public function index(Request $request)
    {

        $data['locations'] = Location::where('location_status',1)->orderby('location_name','asc')->get();

        $data['set'] = 'login';
        return view('location.login.login',$data);
    }
    // --- prev Login Code with Password--- 
    // public function get_index(Request $request){

        
    //         $rules = ['location_name' => 'required',
    //                   'location_password' => 'required'];
            
    //         $messages = ['location_name.required' => 'Please Select Location',
    //                      'location_password.required' => 'Please Enter Password'];
            
    //         $validator = Validator::make($request->all(),$rules,$messages);
            
    //         if($validator->fails())
    //         {
    //             return redirect()->back()->withErrors($validator->errors())->withInput();
    //         }
    //         else
    //         {
    //             $where['location_id'] = $request->location_name;
    //             $where['password'] = $request->location_password;
                
    //             if(Auth::guard('location')->attempt($where))
    //             {
    //                 if(Auth::guard('location')->user()->location_status == 1)
    //                 {
    //                     $location_id = Auth::guard('location')->id();
    //                     return redirect()->route('location.create_sale', ['id' => $location_id]);

    //                     // return redirect()->route('location.dashboard');
    //                 }
    //                 else
    //                 {
    //                     Auth::guard('location')->logout();

    //                     return redirect()->route('login')->with('error','Invalid Password!')->withInput();
    //                 }
    //             }
    //             else
    //             {
    //                 return redirect()->route('login')->with('error','Invalid Password!')->withInput();
    //             }
    //         }
        
    // }

    public function get_index(Request $request){

        
        $rules = ['location_name' => 'required',
                ];
        
        $messages = ['location_name.required' => 'Please Select Location'];
        
        $validator = Validator::make($request->all(),$rules,$messages);
        
        if($validator->fails())
        {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else
        {
            $where['location_id'] = $request->location_name;
            
            $remember_me = $request->has('remember') ? true : false; 

            $location = Location::where($where)->first();
            
            
                if($location && $location->location_status == 1 )
                {
                    Auth::guard('location')->login($location,$remember_me);
                    $location_id = Auth::guard('location')->id();
                    return redirect()->route('location.create_sale', ['id' => $location_id]);                  
                }
                else
                {
                    return redirect()->route('login')->with('error','Invalid Location or Status!')->withInput();
                }
        }
    
    }
}