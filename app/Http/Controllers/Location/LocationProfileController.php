<?php

namespace App\Http\Controllers\Location;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Validator;
use App\Models\Location;

class LocationProfileController extends Controller
{
    public function index(Request $request)
    {
        if($request->has('submit'))
        {
            $rules = ['location_name' => 'required',
                      'location_tax' => 'required',
                      'location_password' => 'required'
                      ];
                
            $messages = ['location_name.required' => 'Please Enter Location',
                         'location_tax.required' => 'Please Enter Tax',
                         'location_password.required' => 'Please Enter Password'
                        ];
            
            $validator = Validator::make($request->all(),$rules,$messages);
            
            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $where_location['location_name'] = $request->location_name;
                $check_location = Location::where($where_location)->where('location_id','!=',Auth::guard('location')->user()->location_id)->count();
                
                if($check_location > 0)
                {
                    return redirect()->back()->with('error','Location Already Exists')->withInput();
                }
                                
                $upd['location_name']       = $request->location_name;
                $upd['location_tax']        = $request->location_tax;
                $upd['location_password']   = bcrypt($request->location_password);
                $upd['location_vpassword']  = base64_encode($request->location_password);
                $upd['location_updated_on'] = date('Y-m-d H:i:s');
                $upd['location_updated_by'] = Auth::guard('location')->user()->location_id;

                if($request->hasFile('location_image'))
                {
                    $location_image = $request->location_image->store('assets/admin/uploads/location');
                    
                    $location_image = explode('/',$location_image);
                    $location_image = end($location_image);
                    $upd['location_image'] = $location_image;
                }
                
                $user = Location::where('location_id',Auth::guard('location')->user()->location_id)->update($upd);

                return redirect()->back()->with('success','Details Updated Successfully');
            }
        }
        
        $data['set'] = 'profile';
        return view('location.profile.profile',$data);
    }
}