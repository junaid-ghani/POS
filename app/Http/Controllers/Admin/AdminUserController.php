<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Validator;
use DataTables;
use App\Models\Role;
use App\Models\User;

class AdminUserController extends Controller
{
    public function index()
    {
        $data['set'] = 'users';
        $data['roles'] = Role::where('role_status',1)->where('role_id','!=',1)->get();
        return view('admin.user.users',$data);

    }

    public function get_users(Request $request)
    {
        $data = User::getDetails();

        return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('pay_structure',function ($row){
                    
                    if ($row->user_salary_type == 1){
                        return 'None';
                    }
                    if ($row->user_salary_type == 2) {
                        return 'Fixed Commission';
                    }
                    if ($row->user_salary_type == 3){
                        return 'Commission Steps';
                    }
                    
                })
                ->addColumn('action', function($row){

                    $btn = '<div class="hstack gap-2 fs-15">';

                    // $btn .= '<a href="'.route('admin.edit_user',['id'=>$row->user_id]).'" class="btn btn-icon btn-sm btn-info" title="Edit"><i class="fa fa-edit"></i></a> ';

                    $btn .= '<a href="javascript:void(0)" onclick="edit_user('.$row->user_id.')"  data-bs-toggle="modal" data-bs-target="#edit_category_modal" class="btn btn-icon btn-sm btn-info editUser" title="Edit"><i class="fa fa-edit"></i></a> ';

                    if($row->user_status == 1)
                    {
                        $btn .= '<a href="'.route('admin.user_status',['id'=>$row->user_id,'status'=>$row->user_status]).'" class="btn btn-icon btn-sm btn-danger" title="Click to disable" onclick="confirm_msg(event)" ><i class="fa fa-ban"></i></a>';
                    }
                    else
                    {
                        $btn .= '<a href="'.route('admin.user_status',['id'=>$row->user_id,'status'=>$row->user_status]).'" class="btn btn-icon btn-sm btn-dark" title="Click to enable" onclick="confirm_msg(event)"><i class="fa fa-check"></i></a> ';
                    }
                    
                    $btn .= '</div>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
    }

    public function add_user(Request $request)
    {        
        
        if($request->has('submit'))
        {          

           
            $rules = ['user_name'        => 'required|unique:users',
                      'user_role'        => 'required',
                      'user_email'       => 'nullable|email',
                      //'user_password'    => 'required',
                      //'user_phone'     => 'required',
                      'user_salary_type' => 'required',
                      'pin' => 'required|numeric|digits_between:4,255|unique:users,pin',
                     ];

            $messages = ['user_name.required'        => 'Please Enter Name',
                         'user_name.unique'        => 'This Name Already Taken',
                         'user_role.required'        => 'Please Select Role',
                        //  'user_email.required'       => 'Please Enter Email',
                         'user_email.email'          => 'Please Enter Valid Email',
                        //  'user_password.required'    => 'Please Enter Password',
                        //  'user_phone.required'    => 'Please Enter Phone',
                         'user_salary_type.required' => 'Please Select Salary Type',
                         'pin.required'              => 'Please Enter Pin',
                         'pin.numeric'               => 'Please Enter Only Digits',
                         'pin.digits_between'        => 'Pin Should Have Atleast 4 Digits',
                        ];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $where_check['user_email'] = $request->user_email;
                $check_user = User::where($where_check)->whereNotNull('user_email')->count();                
                if($check_user > 0)
                {
                    return redirect()->back()->with('error','User Already Exists')->withInput();
                }
                else
                {
                    
                    $ins['user_name']        = $request->user_name;
                    $ins['user_role']        = $request->user_role;
                    $ins['user_email']       = $request->user_email;
                    $ins['user_password']    = bcrypt($request->user_password);
                    $ins['user_vpassword']   = base64_encode($request->user_password);
                    // $ins['user_phone']       = $request->user_phone;                    
                    $ins['user_salary_type'] = $request->user_salary_type;
                    $ins['user_added_on']    = date('Y-m-d H:i:s');
                    $ins['user_added_by']    = Auth::user()->user_id;
                    $ins['user_updated_on']  = date('Y-m-d H:i:s');
                    $ins['user_updated_by']  = Auth::user()->user_id;
                    $ins['user_status']      = 1;
                    $ins['pin']              = $request->pin;
                    // dd("pass");
                    if($request->user_salary_type == 1)
                    {
                        $ins['user_commission'] = 0;
                    }
                    elseif($request->user_salary_type == 2)
                    {
                        $ins['user_commission'] = $request->user_commission;
                    }
                    elseif($request->user_salary_type == 3)
                    {
                        $ins['user_commission'] = json_encode($request->commissions);
                    }                    
                    // if($request->hasFile('user_image'))
                    // {
                    //     $user_image = $request->user_image->store('assets/admin/uploads/profile');
                        
                    //     $user_image = explode('/',$user_image);
                    //     $user_image = end($user_image);
                    //     $ins['user_image'] = $user_image;
                    // }

                    $add = User::create($ins);

                    if($add)
                    {
                        // return redirect()->back()->with('success','User Added Successfully');
                        return redirect()->route('admin.users')->with('success','User Added Successfully');
                    }
                }
            }
        }

        // $data['roles'] = Role::where('role_status',1)->where('role_id','!=',1)->get();

        $data['set'] = 'users';
        return view('admin.user.add_user',$data);
    }

    public function edit_user(Request $request)
    {
        if($request->has('submit'))
        {
            // dd($request->all());
            
            $rules = ['user_name'        => 'required',
                      'user_role'        => 'required',
                      'user_email'       => 'nullable|email',
                    //'user_password'    => 'required',
                    //'user_phone'       => 'required',
                      'user_salary_type' => 'required',
                      'pin'              => 'required|numeric|digits_between:4,255',
                     ];

            $messages = ['user_name.required'        => 'Please Enter Name',
                         'user_role.required'        => 'Please Select Role',
                        //  'user_email.required'    => 'Please Enter Email',
                         'user_email.email'          => 'Please Enter Valid Email',
                        //  'user_password.required' => 'Please Enter Password',
                        //'user_phone.required'      => 'Please Enter Phone',
                         'user_salary_type.required' => 'Please Select Salary Type',
                         'pin.required'              => 'Please Enter Pin',
                         'pin.numeric'               => 'Please Enter Only Digits',
                         'pin.digits_between'        => 'Pin Should Have Atleast 4 Digits',
                        ];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $where_check['user_email'] = $request->user_email; 
                $check_user = User::where($where_check)->where('user_id','!=',$request->user_id)->whereNotNull('user_email')->count();
                $check_pin = User::where('pin',$request->pin)->where('user_id','!=',$request->user_id)->count();
                $check_user_name = User::where('user_name',$request->user_name)->where('user_id','!=',$request->user_id)->count();
                if($check_user_name > 0) {
                    return redirect()->back()->with('error','Name Already Exists')->withInput();
                }
                if($check_pin) {
                    return redirect()->back()->with('error','Pin Already Exists')->withInput();
                }
                if($check_user > 0)
                {
                    return redirect()->back()->with('error','User Already Exists')->withInput();
                }
                else
                {
                    $upd['user_name']        = $request->user_name;
                    $upd['user_role']        = $request->user_role;
                    $upd['user_email']       = $request->user_email;
                    $upd['user_password']    = bcrypt($request->user_password);
                    $upd['user_vpassword']   = base64_encode($request->user_password);
                    // $upd['user_phone']       = $request->user_phone;
                    $upd['user_salary_type'] = $request->user_salary_type;
                    $upd['user_updated_on']  = date('Y-m-d H:i:s');
                    $upd['user_updated_by']  = Auth::user()->user_id;
                    $upd['pin']              = $request->pin;
                    
                    if($request->user_salary_type == 1)
                    {
                        $upd['user_commission'] = 0;
                       
                    }
                    elseif($request->user_salary_type == 2)
                    {
                        $upd['user_commission'] = $request->user_commission;
                        
                    }
                    elseif($request->user_salary_type == 3)
                    {
                        $upd['user_commission'] = json_encode($request->edit_commissions);
                    }

                    // if($request->hasFile('user_image'))
                    // {
                    //     $user_image = $request->user_image->store('assets/admin/uploads/profile');
                        
                    //     $user_image = explode('/',$user_image);
                    //     $user_image = end($user_image);
                    //     $upd['user_image'] = $user_image;
                    // }

                    $add = User::where('user_id',$request->user_id)->update($upd);

                    // return redirect()->back()->with('success','User Updated Successfully');
                    return redirect()->route('admin.users')->with('success','User Updated Successfully');
                }
            }
        }

        // $where['user_id'] = $request->segment(3);
        // $data['user'] = User::where($where)->first();
        
        // if(!isset($data['user']))
        // {
        //     return redirect()->route('admin.users');
        // }

        // $data['roles'] = Role::where('role_status',1)->where('role_id','!=',1)->get();

        // $data['set'] = 'users';
        // return view('admin.user.edit_user',$data);
    }


    public function view_edit_user(Request $request)
    {
        $where['user_id'] = $request->user_id;
        $data['user'] = User::where($where)->first();
        
        if(!isset($data['user']))
        {
            return redirect()->route('admin.users');
        }

        $data['roles'] = Role::where('role_status',1)->where('role_id','!=',1)->get();

        $data['set'] = 'users';
        return view('admin.user.edit_user',$data);
    }

    public function user_status(Request $request)
    {
        $status = $request->segment(4);

        if($status == 1)
        {
            $upd['user_status'] = 0;
        }
        else
        {
            $upd['user_status'] = 1;
        }

        $where['user_id'] = $request->segment(3);
        User::where($where)->update($upd);
        
        return redirect()->back()->with('success','Status Changed Successfully!');
    }
}
