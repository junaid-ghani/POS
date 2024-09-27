<?php

namespace App\Http\Controllers\Location;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Validator;
use DataTables;
use App\Models\Customer;
use App\Models\User;
use App\Models\CustomerSeller;
class LocationCustomerController extends Controller
{
    public function index()
    {
        $data['set'] = 'customers';
        
        $where_seller['user_role'] = 4;
        $where_seller['user_status'] = 1;
        $data['sellers'] = User::where($where_seller)->orderby('user_name','asc')->get();

        return view('location.customer.customers',$data);
    }

    public function get_customers(Request $request)
    {

        $location_id =  Auth::guard('location')->id();
        
        if ($location_id) {
            
            $data = Customer::with('get_Customer_Seller.get_User')->orderby('customer_id','desc')->where('location_added_by',$location_id)->where('customer_added_by',null)->get();
        }        

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
                // ->addColumn('action', function($row){
                   
                //     $btn = '<div class="hstack gap-2 fs-15">';

                    // $btn .= '<a href="javascript:void(0)" onclick="edit_customer('.$row->customer_id.')" class="btn btn-icon btn-sm btn-info" title="Edit"><i class="fa fa-edit"></i></a> ';
                    // $btn .= '<a href="'.route('location.delete_customer',['id'=>$row->customer_id]).'" class="btn btn-icon btn-sm btn-danger" title="Delete"><i class="fa fa-remove"></i></a> ';                  
                    
                //     $btn .= '</div>';

                //     return $btn;
                // })
                // ->rawColumns(['action'])
                ->make(true);
    }

    public function add_customer(Request $request)
    {

        if ($request->ajax()) {

               
                $where_check['customer_email'] = $request->customer_email;
                $check_customer = Customer::where($where_check)->count();

                if($check_customer > 0)
                {
                    // return redirect()->back()->with('error','Customer Already Exists')->withInput();
                    return response()->json(['status'=>'error','message'=>'Customer Already Exists']);
                }
                else
                {
                    $location_id =  Auth::guard('location')->id();
                    
                    $ins['customer_name']       = $request->customer_name;
                    $ins['customer_email']      = $request->customer_email;
                    $ins['customer_phone']      = $request->customer_phone;
                    // $ins['customer_gender']     = $request->customer_gender;
                    // $ins['customer_dob']        = $request->customer_dob;
                    // $ins['customer_address']    = $request->customer_address;
                    
                    $ins['customer_added_on']   = date('Y-m-d H:i:s');
                    $ins['customer_updated_on'] = date('Y-m-d H:i:s');

                    $ins['customer_added_by']   = null;
                    $ins['customer_updated_by'] = null;

                    $ins['location_added_by']   = $location_id;
                    $ins['location_updated_by'] = $location_id;
                    
                    $ins['customer_status']     = 1;
                    $add = Customer::create($ins);    
                    // dd($add);
                    if($add)
                    {
                        if ($request->customer_seller) {
                            //  dd($request->all());
                                    
                            foreach ($request->customer_seller as $seller) {

                                $customer_seller = new CustomerSeller();
                                $customer_seller->seller_id = $seller;
                                $customer_seller->customer_id = $add->customer_id;
                                $customer_seller->save();

                            }
                        }
                        return response()->json(['status'=>'success','customer_id'=>$add->customer_id,'customer_name'=>$add->customer_name]);
                    }else{
                        return response()->json(['status'=>'error']);
                    }
                }

        }else{

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
                            $location_id =  Auth::guard('location')->id();
                            
                            $ins['customer_name']       = $request->customer_name;
                            $ins['customer_email']      = $request->customer_email;
                            $ins['customer_phone']      = $request->customer_phone;
                            // $ins['customer_gender']     = $request->customer_gender;
                            // $ins['customer_dob']        = $request->customer_dob;
                            // $ins['customer_address']    = $request->customer_address;
                            
                            $ins['customer_added_on']   = date('Y-m-d H:i:s');
                            $ins['customer_updated_on'] = date('Y-m-d H:i:s');

                            $ins['customer_added_by']   = null;
                            $ins['customer_updated_by'] = null;

                            $ins['location_added_by']   = $location_id;
                            $ins['location_updated_by'] = $location_id;
                            
                            $ins['customer_status']     = 1;
                            $add = Customer::create($ins);
                            
                            
                            if($add)
                            {
                                // dd($request->sale_sellers);
                                if ($request->sale_sellers) {
                                    
                                    foreach ($request->sale_sellers as $seller) {
                                        // dd($seller);
                                         
                                        $customer_seller = new CustomerSeller();
                                        $customer_seller->seller_id = $seller;
                                        $customer_seller->customer_id = $add->customer_id;
                                        $customer_seller->save();
                                    }
                                }
                                // return redirect()->back()->with('success','Customer Added Successfully');
                                return redirect()->route('location.customers')->with('success','Customer Added Successfully');
                            }
                        }
                    }
                }
                
                $where_seller['user_role'] = 4;
                $where_seller['user_status'] = 1;
                $data['sellers'] = User::where($where_seller)->orderby('user_name','asc')->get();
        
                $data['set'] = 'customers';
                return view('location.customer.add_customer',$data);
        }
    }

    public function view_edit_customer(Request $request){

        $where['customer_id'] = $request->customer_id;
        $data['customer'] = Customer::where($where)->first();
        
        if(!isset($data['customer']))
        {
            return redirect()->route('location.customers');
        }

        $where_seller['user_role'] = 4;
        $where_seller['user_status'] = 1;
        $data['sellers'] = User::where($where_seller)->orderby('user_name','asc')->get();
         
        return view('location.customer.edit_customer',$data);
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
                $check_customer = Customer::where($where_check)->where('customer_id','!=',$request->customer_id)->count();

                if($check_customer > 0)
                {
                    return redirect()->back()->with('error','Customer Already Exists')->withInput();
                }
                else
                {
                    $location_id =  Auth::guard('location')->id();
                    $upd['customer_name']       = $request->customer_name;
                    $upd['customer_email']      = $request->customer_email;
                    $upd['customer_phone']      = $request->customer_phone;
                    // $upd['customer_gender']     = $request->customer_gender;
                    // $upd['customer_dob']        = $request->customer_dob;
                    // $upd['customer_address']    = $request->customer_address;
                    $ins['customer_added_by']   = null;
                    $ins['customer_updated_by'] = null;

                    $ins['location_added_by']   = $location_id;
                    $ins['location_updated_by'] = $location_id;
                    
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
                    return redirect()->route('location.customers')->with('success','Customer Updated Successfully');
                }
            }
        }

        // $where['customer_id'] = $request->segment(3);
        // $data['customer'] = Customer::where($where)->first();
        
        // if(!isset($data['customer']))
        // {
        //     return redirect()->route('location.customers');
        // }

        // $where_seller['user_role'] = 4;
        // $where_seller['user_status'] = 1;
        // $data['sellers'] = User::where($where_seller)->orderby('user_name','asc')->get();

        // $data['set'] = 'customers';
        // return view('location.customer.edit_customer',$data);
    }
    public function delete_customer($id)
    {
        $delete_customer  = Customer::find($id);
        $delete_customer->delete();
        return redirect()->route('location.customers')->with('success','Delete User Successfully!');
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
}
