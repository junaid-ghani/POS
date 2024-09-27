<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Validator;
use DataTables;
use App\Models\Customer;
use App\Models\CustomerSeller;
use App\Models\User;
use App\Models\Sale;

class AdminCustomerController extends Controller
{
    public function index()
    {
        $data['set'] = 'customers';
        $where_seller['user_role'] = 4;
        $where_seller['user_status'] = 1;
        $data['sellers'] = User::where($where_seller)->orderby('user_name','asc')->get();

        return view('admin.customer.customers',$data);
    }

    public function get_customers(Request $request)
    {
        $data = Customer::with('get_Customer_Seller.get_User')->orderby('customer_id','desc')->get();
        // CustomerSeller::all();

        // dd($data);
        return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('seller', function($row) {
                    $sellerNameHtml = "";
            
                    foreach ($row->get_Customer_Seller as $customerSeller) {
                        
                        if ($customerSeller->get_User) {
                          
                            $sellerNameHtml .=  $customerSeller->get_User->user_name.',';
                        }
                    }
            
                    return rtrim($sellerNameHtml, ',');
                })
                ->addColumn('action', function($row){

                    $btn = '<div class="hstack gap-2 fs-15">';
                    $btn .= '<button type="button" class="btn btn-sm btn-dark customer_trans" data-id="'. $row->customer_id .'"><i class="fa-solid fa-box me-2"></i>Open</button>';
                    $btn .= '<a href="javascript:void(0)" onclick="edit_customer('.$row->customer_id.')"  class="btn btn-icon btn-sm btn-info" title="Edit"><i class="fa fa-edit"></i></a> ';
                    $btn .= '<a href="'.route('admin.delete_customer',['id'=>$row->customer_id]).'" class="btn btn-icon btn-sm btn-danger" title="Delete"><i class="fa fa-remove"></i></a> ';
                    // if($row->customer_status == 1)
                    // {
                    //     $btn .= '<a href="'.route('admin.customer_status',['id'=>$row->customer_id,'status'=>$row->customer_status]).'" class="btn btn-icon btn-sm btn-danger" title="Click to disable" onclick="confirm_msg(event)" ><i class="fa fa-ban"></i></a>';
                    // }
                    // else
                    // {
                    //     $btn .= '<a href="'.route('admin.customer_status',['id'=>$row->customer_id,'status'=>$row->customer_status]).'" class="btn btn-icon btn-sm btn-dark" title="Click to enable" onclick="confirm_msg(event)"><i class="fa fa-check"></i></a> ';
                    // }
                    
                    $btn .= '</div>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->addColumn('total_spend', function($row) {
                    $total_spend = Sale::where('sale_customer',$row->customer_id)->sum('sale_grand_total');
                    
                    return '$'.number_format($total_spend,2);
                })
                ->make(true);
    }

    public function add_customer(Request $request)
    {
        
        if($request->has('submit'))
        {
            $rules = ['customer_name' => 'required',
                      'customer_email' => 'required|email',
                    //   'customer_phone' => 'required',
                    //   'customer_gender' => 'required',
                    //   'customer_dob' => 'required',
                    //   'customer_address' => 'required'
                    ];
                    
            if ($request->has('customer_phone') && !empty($request->customer_phone)) {
                $rules['customer_phone'] = ['required', 'regex:/^\(\d{3}\) \d{3}-\d{4}$/'];
            }
            $messages = ['customer_name.required' => 'Please Enter Customer',
                         'customer_email.required' => 'Please Enter Email',
                         'customer_email.email' => 'Please Enter Valid Email',
                         'customer_phone.regex' => 'Please Enter a Valid Phone Number in the Format (123) 456-7890',
                        //  'customer_gender.required' => 'Please Select Gender',
                        //  'customer_dob.required' => 'Please Select Date of Birth',
                        //  'customer_address.required' => 'Please Enter Address'
                        ];

            $validator=Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $where_check['customer_email'] = $request->customer_email;
                $check_customer = Customer::where($where_check)->count();

                if($check_customer > 0)
                {
                    return redirect()->back()->with('error','Customer Already Exists')->withInput();
                }
                else
                {
                    $ins['customer_name']       = $request->customer_name;
                    $ins['customer_email']      = $request->customer_email;
                    $ins['customer_phone']      = $request->customer_phone;
                    // $ins['customer_gender']     = $request->customer_gender;
                    // $ins['customer_dob']        = $request->customer_dob;
                    // $ins['customer_address']    = $request->customer_address;
                    $ins['customer_added_on']   = date('Y-m-d H:i:s');
                    $ins['customer_added_by']   = Auth::user()->user_id;
                    $ins['customer_updated_on'] = date('Y-m-d H:i:s');
                    $ins['customer_updated_by'] = Auth::user()->user_id;
                    $ins['customer_status']     = 1;

                    $add = Customer::create($ins);

                    if($add)
                    {                       
                        if ($request->sale_sellers) {
                            
                            foreach ($request->sale_sellers as $seller) {
                                    
                                $customer_seller = new CustomerSeller();
                                $customer_seller->seller_id = $seller;
                                $customer_seller->customer_id = $add->customer_id;
                                $customer_seller->save();
                            }
                        }                    
                        // return redirect()->back()->with('success','Customer Added Successfully');
                        return redirect()->route('admin.customers')->with('success','Customer Added Successfully');
                    }
                }
            }
        }
        // $where_seller['user_role'] = 4;
        // $where_seller['user_status'] = 1;
        // $data['sellers'] = User::where($where_seller)->orderby('user_name','asc')->get();

        // $data['set'] = 'customers';
        return view('admin.customer.add_customer',$data);
    }

    public function edit_customer(Request $request)
    {
        if($request->has('submit'))
        {
          
            $rules = ['customer_name' => 'required',
                      'customer_email' => 'required|email',
                    //   'customer_phone' => 'required',
                    //   'customer_gender' => 'required',
                    //   'customer_dob' => 'required',
                    //   'customer_address' => 'required'
                    ];
            if ($request->has('customer_phone') && !empty($request->customer_phone)) {
                $rules['customer_phone'] = ['required', 'regex:/^\(\d{3}\) \d{3}-\d{4}$/'];
            }

            $messages = ['customer_name.required' => 'Please Enter Customer',
                         'customer_email.required' => 'Please Enter Email',
                         'customer_email.email' => 'Please Enter Valid Email',
                         'customer_phone.regex' => 'Please Enter a Valid Phone Number in the Format (123) 456-7890',
                        ];

            $validator=Validator::make($request->all(),$rules,$messages);
            
            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $where_check['customer_email'] = $request->customer_email;
                $check_customer = Customer::where($where_check)->where('customer_id','!=',$request->customer_id)->count();
                
                if($check_customer > 0)
                {
                    
                    return redirect()->back()->with('error','Customer Already Exists')->withInput();
                }
                else
                {
                   
                    $upd['customer_name']       = $request->customer_name;
                    $upd['customer_email']      = $request->customer_email;
                    $upd['customer_phone']      = $request->customer_phone;
                    // $upd['customer_gender']     = $request->customer_gender;
                    // $upd['customer_dob']        = $request->customer_dob;
                    // $upd['customer_address']    = $request->customer_address;
                    $upd['customer_updated_on'] = date('Y-m-d H:i:s');
                    $upd['customer_updated_by'] = Auth::user()->user_id;
                    
                    $add = Customer::where('customer_id',$request->customer_id)->update($upd);
                    
                    CustomerSeller::where('customer_id', $request->customer_id)->delete();
                    
                    if ($request->sale_sellers) {
                        
                        foreach ($request->sale_sellers as $seller) {
                            $customer_seller = new CustomerSeller();
                            $customer_seller->seller_id = $seller;
                            $customer_seller->customer_id = $request->customer_id;
                            $customer_seller->save();  
                        }
                    }
                    
                    // return redirect()->back()->with('success','Customer Updated Successfully');
                    return redirect()->route('admin.customers')->with('success','Customer Updated Successfully');
                }
            }
        }

        // $where['customer_id'] = $request->segment(3);
        // $data['customer'] = Customer::where($where)->first();
        
        // if(!isset($data['customer']))
        // {
        //     return redirect()->route('admin.customers');
        // }

        // $where_seller['user_role'] = 4;
        // $where_seller['user_status'] = 1;
        // $data['sellers'] = User::where($where_seller)->orderby('user_name','asc')->get();
        
        // $data['set'] = 'customers';
        // return view('admin.customer.edit_customer',$data);
    }

    public function view_edit_customer(Request $request){

        $where['customer_id'] = $request->customer_id;
        $data['customer'] = Customer::where($where)->first();
        
        if(!isset($data['customer']))
        {
            return redirect()->route('admin.customers');
        }

        $where_seller['user_role'] = 4;
        $where_seller['user_status'] = 1;
        $data['sellers'] = User::where($where_seller)->orderby('user_name','asc')->get();
        
        // $data['set'] = 'customers';
        return view('admin.customer.edit_customer',$data);
    }
    
    public function delete_customer($id)
    {
        $delete_customer  = Customer::find($id);
        $delete_customer->delete();
        return redirect()->route('admin.customers')->with('success','Delete User Successfully!');
    }

    public function customer_status(Request $request)
    {
        $status = $request->segment(4);

        if($status == 1)
        {
            $upd['customer_status'] = 0;
        }
        else
        {
            $upd['customer_status'] = 1;
        }

        $where['customer_id'] = $request->segment(3);
        Customer::where($where)->update($upd);
        
        return redirect()->back()->with('success','Status Changed Successfully!');
    }
    public function customer_transactions($id)
    {
        $transactions = Sale::where('sale_customer', $id)->join('locations', 'locations.location_id','=', 'sales.sale_location')->get();

        return Datatables::of($transactions)
            ->addIndexColumn()
            ->addColumn('sale_date', function($row){
                return date('d M Y h:i A', strtotime($row->sale_added_on));
            })
            ->addColumn('sale_sub_total', function($row){
                return '$' . number_format($row->sale_sub_total, 2);
            })
            ->addColumn('location_name', function($row){
                return $row->location_name;
            })
            ->addColumn('sale_grand_total', function($row){
                return '$' . number_format($row->sale_grand_total, 2);
            })
            ->addColumn('status', function($row){
                return $row->status ? 'Completed' : 'Pending';
            })
            
            ->addColumn('sellers', function($row){


                $sellers_id =[];
                foreach ($row->commission_seller as $seller){

                    $sellers_id[] = $seller->seller_id;                            
                }
            
                $users = User::whereIn('user_id', $sellers_id)->get();
                
                $userNames = array_column($users->toArray(), 'user_name', 'user_id');
    
                // $sessionUserId = session('verified_user_id');
                
                // $userRole = User::where('user_id', $sessionUserId)->value('user_role');
                
                $sellers = implode(", ",$userNames);

                // $sellersDisplay = [];
                // foreach ($users as $user) {
                //     if (isset($userNames[$user->user_id])) {
                //         if ($user->user_id == $sessionUserId) {                                  
                //             $sellersDisplay[] = $userNames[$user->user_id];
                //         } 
                //     }
                // }
                
                // return implode(', ', $sellersDisplay);


                // $sellers = json_decode($row->sale_sellers,true);

                // $users = User::whereIn('user_id',$sellers)->get();

                // $sellers = array_column($users->toArray(),'user_name');
                // $sellers = implode(", ",$sellers);

                return $sellers;

            })
            ->addColumn('action', function($row){
                    return '<button type="button" class="btn btn-sm btn-dark sale_action" data-id="'. $row->sale_id .'"><i class="fa-solid fa-box me-2"></i>Open</button>';
            })
            ->rawColumns(['action']) // To render HTML in action column
            ->make(true); 
    }
    
}