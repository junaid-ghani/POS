<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Validator;
use DataTables;
use App\Models\User;
use App\Models\Location;

class AdminLocationController extends Controller
{
    public function index()
    {
        
        $data['set'] = 'locations';
        $data['user'] = User::where('user_status', '=', 1)->where('user_id',Auth::user()->user_id)->first();        
        return view('admin.location.locations',$data);
    }

    public function get_locations(Request $request)
    {
        
        $data = Location::orderby('location_id','desc')->get();
        $user = User::where('user_status', '=', 1)->where('user_id',Auth::user()->user_id)->first();        
        $disabledClass = ($user->user_role != 1) ? 'style=" display: none " ' : '';
        return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('City, State', function($row){

                    if ($row->location_city || $row->location_state) {
                        
                        return $row->location_city.', '.$row->location_state;
                    }

                })
                ->addColumn('action', function($row) use ($disabledClass){

                    $btn = '<div class="hstack gap-2 fs-15">';

                    $btn .= '<a href="javascript:void(0)" onclick="edit_location('.$row->location_id.')" class="btn btn-icon btn-sm btn-info" title="Edit"><i class="fa fa-edit"></i></a> ';

                    if($row->location_status == 1)
                    {
                        $btn .= '<a href="'.route('admin.location_status',['id'=>$row->location_id,'status'=>$row->location_status]).'" class="btn btn-icon btn-sm btn-danger " '.$disabledClass.' title="Click to disable" onclick="confirm_msg(event)" ><i class="fa fa-ban"></i></a>';
                    }
                    else
                    {
                        $btn .= '<a href="'.route('admin.location_status',['id'=>$row->location_id,'status'=>$row->location_status]).'" class="btn btn-icon btn-sm btn-dark" '.$disabledClass.'  title="Click to enable" onclick="confirm_msg(event)"><i class="fa fa-check"></i></a> ';
                    }
                    
                    $btn .= '</div>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
    }

    public function add_location(Request $request)
    {
        if($request->has('submit'))
        {
            
            // dd($request->all());
            $rules = ['location_name' => 'required',
                      'location_tax' => 'required',
                    //   'location_password' => 'required'
                    ];
            if ($request->has('location_phone_number') && !empty($request->location_phone_number)) {
                $rules['location_phone_number'] = ['required', 'regex:/^\(\d{3}\) \d{3}-\d{4}$/'];
            }
            $messages = ['location_name.required' => 'Please Enter Name',
                         'location_tax.required' => 'Please Enter Tax',
                         'location_phone_number.regex' => 'Please Enter a Valid Phone Number in the Format (123) 456-7890',

                        //  'location_password.required' => 'Please Enter Password'
                        ];

            $validator=Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $where_check['location_name'] = $request->location_name;
                $check_location = Location::where($where_check)->count();

                if($check_location > 0)
                {
                    return redirect()->back()->with('error','Location Already Exists')->withInput();
                }
                else
                {
                    $ins['location_name']       = $request->location_name;
                    $ins['location_tax']        = $request->location_tax;
                    
                    $ins['location_address']       = $request->location_address;
                    $ins['location_city']          = $request->location_city;
                    $ins['location_state']         = $request->location_state;
                    $ins['location_zip']           = $request->location_zip;
                    $ins['location_phone_number']  = $request->location_phone_number;

                    // $ins['location_password']   = bcrypt($request->location_password);
                    $ins['location_vpassword']  = base64_encode($request->location_password);
                    $ins['location_added_on']   = date('Y-m-d H:i:s');
                    $ins['location_added_by']   = Auth::user()->user_id;
                    $ins['location_updated_on'] = date('Y-m-d H:i:s');
                    $ins['location_updated_by'] = Auth::user()->user_id;
                    $ins['location_status']     = 1;

                    // if($request->hasFile('location_image'))
                    // {
                    //     $location_image = $request->location_image->store('assets/admin/uploads/location');
                        
                    //     $location_image = explode('/',$location_image);
                    //     $location_image = end($location_image);
                    //     $ins['location_image'] = $location_image;
                    // }

                    $add = Location::create($ins);
                 
                    if($add)
                    {
                        // return redirect()->back()->with('success','Location Added Successfully');
                        return redirect()->route('admin.locations')->with('success','Location Added Successfully');
                    }
                }
            }
        }

        $data['set'] = 'locations';
        return view('admin.location.add_location',$data);
    }
    public function view_edit_location(Request $request)
    {
        $where['location_id'] = $request->location_id;
        $data['location'] = Location::where($where)->first();
        
        if(!isset($data['location']))
        {
            return redirect()->route('admin.locations');
        }

        // $data['set'] = 'locations';
        return view('admin.location.edit_location',$data);
    }

    public function edit_location(Request $request)
    {
        if($request->has('submit'))
        {
            $rules = ['location_name' => 'required',
                      'location_tax' => 'required',
                    //   'location_password' => 'required'
                    ];
            if ($request->has('location_phone_number') && !empty($request->location_phone_number)) {
                $rules['location_phone_number'] = ['required', 'regex:/^\(\d{3}\) \d{3}-\d{4}$/'];
            }
            $messages = ['location_name.required' => 'Please Enter Name',
                         'location_tax.required' => 'Please Enter Tax',
                        //  'location_password.required' => 'Please Enter Password'
                        'location_phone_number.regex' => 'Please Enter a Valid Phone Number in the Format (123) 456-7890',

                        ];

            $validator=Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $where_check['location_name'] = $request->location_name;
                $check_location = Location::where($where_check)->where('location_id','!=',$request->location_id)->count();

                if($check_location > 0)
                {
                    return redirect()->back()->with('error','Location Already Exists')->withInput();
                }
                else
                {
                    $upd['location_name']       = $request->location_name;
                    $upd['location_address']       = $request->location_address;
                    $upd['location_city']          = $request->location_city;
                    $upd['location_state']         = $request->location_state;
                    $upd['location_zip']           = $request->location_zip;
                    $upd['location_phone_number']  = $request->location_phone_number;
                    $upd['location_tax']        = $request->location_tax;
                    // $upd['location_password']   = bcrypt($request->location_password);
                    // $upd['location_vpassword']  = base64_encode($request->location_password);
                    $upd['location_updated_on'] = date('Y-m-d H:i:s');
                    $upd['location_updated_by'] = Auth::user()->user_id;
                    
                    // if($request->hasFile('location_image'))
                    // {
                    //     $location_image = $request->location_image->store('assets/admin/uploads/location');
                        
                    //     $location_image = explode('/',$location_image);
                    //     $location_image = end($location_image);
                    //     $upd['location_image'] = $location_image;
                    // }

                    $add = Location::where('location_id',$request->location_id)->update($upd);

                    return redirect()->route('admin.locations')->with('success','Location Updated Successfully');
                }
            }
        }

        // $where['location_id'] = $request->segment(3);
        // $data['location'] = Location::where($where)->first();
        
        // if(!isset($data['location']))
        // {
        //     return redirect()->route('admin.locations');
        // }

        // $data['set'] = 'locations';
        // return view('admin.location.edit_location',$data);
    }

    public function location_status(Request $request)
    {
        $status = $request->segment(4);

        if($status == 1)
        {
            $upd['location_status'] = 0;
        }
        else
        {
            $upd['location_status'] = 1;
        }

        $where['location_id'] = $request->segment(3);
        Location::where($where)->update($upd);
        
        return redirect()->back()->with('success','Status Changed Successfully!');
    }
}
