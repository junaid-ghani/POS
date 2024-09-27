<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mail;
use Cart;
use Auth;
use Validator;
use DataTables;
use App\Models\Location;
use App\Models\Customer;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Sale;
use App\Models\Role;
use App\Models\SaleItem;
use App\Models\Commission;
use App\Models\Setting;
use App\Models\Bonus;
use App\Models\EmployeeRequest;
use App\Mail\SendReceiptCopy;
use App\Models\EmployeeRequestSale;
use App\Models\EmployeeRequestSaleItem;
use App\Models\CustomerSeller;

class AdminSaleController extends Controller
{
    public function index()
    {
        $data['locations'] = Location::where('location_status',1)->get();
        $data['today'] = date('Y-m-d');
        $data['set'] = 'sales';
        $where_seller['user_status'] = 1;
        $data['sellers'] = User::where($where_seller)->where('user_role', '!=', 1)->orderby('user_name','asc')->get();
        return view('admin.sale.sales',$data);
    }
    public function approve_emp_view(Request $request)
    {
        $data['set'] = 'Approve Sale';
        $emp_request_id = $request->segment(2);
        $data['id']  = $emp_request_id;
        $data['employee_request']  = EmployeeRequest::where('emp_request_id',$emp_request_id)->first();
        $data['employee_request_sale'] = EmployeeRequestSale::where('emp_request_id',$emp_request_id)->with('requestSale.getRequestProduct')->with('requestUser')->first();
        
        return view('admin.sale.accept_sale',$data);
    }
    public function approve_emp(Request $request,$id)
    {
        $action = $request->query('action');
        $employee_request = EmployeeRequest::find($id);
        if ($employee_request) {

            if ($action === 'approved') {

                $employee_request->status = 1;
                $employee_request->save();
                // return response()->json(['message' => 'Sale has been Approved']);
                return redirect()->route('request_sale_messgae',$id)->with('success', 'Sales Approved Successfully');

            } elseif ($action === 'rejected') {

                $employee_request->status = 2;
                $employee_request->save();
                // return response()->json(['message' => 'Sale has been rejected']);
                return redirect()->route('request_sale_messgae',$id)->with('success', 'Sales Rejected Successfully');
            }
            
        } else {
            return response()->json(['status' => 'error', 'message' => 'Request not found']);
        }
    }
    public function request_sale_messgae($id)
    {
        $data['employee_request'] = EmployeeRequest::find($id);
   
        return view('admin.sale.request_sale_messgae',$data);
    }
    public function get_sales(Request $request)
    {
        $where = 'sale_status = 1';
        if(auth()->user()->user_role == '4'){
            $where .= ' AND sale_added_by = "'.auth()->user()->user_id.'"';
        }

        if(!empty($request->location_id))
        {
            $where .= ' AND sale_location = "'.$request->location_id.'"';
        }

        if(!empty($request->start_date))
        {
            $where .= ' AND sale_added_on >= "'.date('Y-m-d 00:00:00',strtotime($request->start_date)).'"';
        }

        if(!empty($request->end_date))
        {
            $where .= ' AND sale_added_on <= "'.date('Y-m-d 23:59:59',strtotime($request->end_date)).'"';
        }

        $data = Sale::getDetails3($where);

        return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('sale_date', function($row){

                    $sale_date = date('d M Y h:i A',strtotime($row->sale_added_on));
                    return $sale_date;

                })
                ->addColumn('sale_sub_total',function($row){
                
                    return '$'.$row->sale_sub_total ;

                })
                ->addColumn('sale_tax_amount',function($row){
                
                    return '$'.$row->sale_tax_amount ;

                })
                ->addColumn('sale_grand_total',function($row){
                
                    return '$'.$row->sale_grand_total ;

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

                    $btn = '<div class="hstack gap-2 fs-15">';

                    $btn .= '<a href="javascript:void(0)" data-saleid='.$row->sale_id.' data-bs-toggle="modal" data-bs-target="#Receipt_Information" class="btn btn-icon btn-sm btn-dark sale_action" title="Open"><i class="fa-solid fa-box "></i></a>';
                    
                    $btn .= '</div>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
    }

    public function saleGetCustomers()
    {
        $customers = Customer::where('customer_status',1)->get(); 
        return response()->json($customers);
    }

    public function sale_add_customer(Request $request)
    {

        if ($request->ajax()) {
               
                $where_check['customer_email'] = $request->customer_email;
                $check_customer = Customer::where($where_check)->count();

                if($check_customer > 0)
                {
                    return response()->json(['status'=>'error','message'=>'Customer Already Exists']);
                }
                else
                {
                    $location_id =  Auth::guard('location')->id();
                    
                    $ins['customer_name']       = $request->customer_name;
                    $ins['customer_email']      = $request->customer_email;
                    $ins['customer_phone']      = $request->customer_phone;
     
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
                        if ($request->customer_seller) {
                            
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

        }      
    }


    public function update_get_sales(Request $request){

        $where = 'sale_status = 1';
        $where = 'sale_id ='.$request->sale_id;
        
        $sale = Sale::getDetails2($where);        
        
        $location = Location::where('location_status', 1)->where('location_id', $sale->sale_location)->first();

        $commission = Commission::where('sale_id', $request->sale_id)->with('seller')->get();
        $saleItems = SaleItem::where('sale_item_sale', $request->sale_id)->with('getProduct')->get(); 
        
        $where_seller['user_status'] = 1;        
        $sellers = User::where($where_seller)->where('user_role', '!=', 1)->orderby('user_name','asc')->get();
        $customers = Customer::where('customer_status',1)->get(); 
        return response()->json(['status'=>'success','location'=>$location,'sale'=> $sale,'saleItems' => $saleItems ,'commission' => $commission,'seller'=>$sellers ,'customers'=>$customers]);
    }
    public function SendReceiptCopy(Request $request)
    {
           
        if($request->input('submit') === 'email_receipt') {

            $where = 'sale_status = 1';
            $where = 'sale_id ='.$request->sale_id;
            
            $sale = Sale::getDetails2($where);        
            $location = Location::where('location_status', 1)->where('location_id', $sale->sale_location)->first();
            
            $saleItems = SaleItem::where('sale_item_sale', $request->sale_id)->with('getProduct')->get(); 
            $setting = Setting::first();
            $userIds = json_decode($sale->sale_sellers,true);
            $users_for_email = User::where('user_status', 1)->whereIn('user_id', $userIds)->get();

            $receipt_data_copy = [
                'company_name' =>  $setting->company_name,
                'sale_location' =>  $location->location_name,   
                'location_city' =>  $location->location_city,
                'location_state' =>  $location->location_state,                              
                'location_address' =>  $location->location_address,                                
                'location_phone_number' =>  $location->location_phone_number,                               
                'sale_invoice_no' =>  $sale->sale_invoice_no,                                
                'sale_added_on' => $sale->sale_added_on,
                'sale_discount' => $sale->sale_discount,
                'sale_discount_amount' => $sale->sale_discount_amount,
                'sale_sub_total' => $sale->sale_sub_total,
                'sale_tax' => $sale->sale_tax_amount,
                'sale_grand_total' => $sale->sale_grand_total,                            
                'sale_pay_cash' => $sale->sale_pay_cash,
                'sale_pay_ccard' => $sale->sale_pay_ccard,
                'sale_pay_payment_app' => $sale->sale_pay_payment_app,
                'sale_pay_check' => $sale->sale_pay_check,
                'users_for_email' => $users_for_email,
                'sale_items' => $saleItems,
                ];
            Mail::to($request->send_email)->send(new SendReceiptCopy($receipt_data_copy));
            
            return redirect()->route('admin.sales', Auth::guard('location')->id())->with('success', 'Email Receipt Successfully');
        }


    }

    public function delete_sale(Request $request,$id)
    {
        
        $id = $request->saleId;
        // dd($id);
       if ($request->deleteAndUpdate == 'Yes') {       

            $where = 'sale_status = 1';
            $where = 'sale_id ='.$id;
            
            $sale = Sale::getDetails2($where);        
            
            $saleItems = SaleItem::where('sale_item_sale', $id)->with('getProduct')->get(); 
            
            foreach ($saleItems as $key => $value) {

                $where_stock = [
                    'stock_location' => $sale->location_id,
                    'stock_product' => $value->getProduct->product_id,
                ];
                $stock = Stock::where($where_stock)->first();    
                $upd['stock_quantity'] = $stock->stock_quantity + $value->sale_item_quantity;
                Stock::where($where_stock)->update($upd);
            }

            $sale = Sale::find($id);
            if ($sale) {
                $sale->delete();

                return response()->json(['status'=>'success','message'=>'Sale Deleted and Stock Updated Successfully']);
            }
            return response()->json(['status'=>'error','message'=>'Sale Not Found']);
       }else{
        
            $sale = Sale::find($id);
            if ($sale) {
                $sale->delete();
                return response()->json(['status'=>'success2','message'=>'Sale Deleted Successfully']);
            }
            return response()->json(['status'=>'error','message'=>'Sale Not Found']);
       }
       
    }

    
    public function update_commission(Request $request)
    {
        if(!empty($request->sale_id))
        {            
            $saleSellers = json_decode($request->seller, true);
            $commissions = json_decode($request->commission, true); 
            $amounts = json_decode($request->amount, true); 

            $sale = Sale::where('sale_id',$request->sale_id)->first();
            $sale->sale_sellers = $saleSellers;      
            $sale->update();
            
            $processedSellers = [];
            $processedCommissionIds = [];

            foreach ($saleSellers as $index => $seller_id)
            {
                $where['sale_id'] = $request->sale_id;
                $where['seller_id'] = $seller_id;
    
                $commission = Commission::where($where)->first();
    
                if ($commission)
                {
                    $commission->update([
                        'commission' => $commissions[$index],
                        'amount' => $amounts[$index],
                    ]);
                }
                else
                {                   
                    $commission = new Commission();
                    $commission->sale_id = $request->sale_id;
                    $commission->seller_id = $seller_id;
                    $commission->commission = $commissions[$index];
                    $commission->amount =$amounts[$index];
                    $commission->commission_added_on = date('Y-m-d H:i:s');
                    $commission->commission_updated_on = date('Y-m-d H:i:s');
                    $commission->save();

                    if ($commission) {
                    
                        $bonus = new Bonus();            
                        $bonus->bonus = number_format((float)0, 2, '.', '');  
                        $bonus->seller_id = $seller_id;  
                        $bonus->commission_id = $commission->commission_id;  
                        $bonus->commission_date = $commission->commission_added_on; 
                        $bonus->bonus_added_on = date('Y-m-d H:i:s');
                        $bonus->bonus_updated_on = date('Y-m-d H:i:s');
                        $bonus->save();
                    }
                    
                }
                $processedSellers[] = $seller_id;
                $processedCommissionIds[] = $commission->commission_id;

            }


            Commission::where('sale_id', $request->sale_id)
                  ->whereNotIn('seller_id', $processedSellers)
                  ->delete();

            Bonus::whereIn('commission_id', $processedCommissionIds)
            ->whereNotIn('seller_id', $processedSellers)
            ->delete(); 

            return response()->json(['status' => 'success']);
        }
    
        return response()->json(['status' => 'error', 'message' => 'Invalid data']);
    }

    public function update_sale_customer(Request $request){

        $sale = Sale::where('sale_id',$request->sale_id)->first();
        $sale->sale_customer = $request->customer_id;
        $sale->update();

        $customer = Customer::where('customer_id',$request->customer_id)->where('customer_status',1)->first(); 

        return response()->json(['status'=>'success','customer_id'=>$customer->customer_id,'customer_name'=>$customer->customer_name]);
    }


    public function search_location(Request $request)
    {
        if($request->has('submit'))
        {
            $rules = ['location' => 'required'];
            
            $messages = ['location.required' => 'Please Select Location'];
                        
            $validator = Validator::make($request->all(),$rules,$messages);
            
            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                Cart::clear();

                return redirect()->route('admin.create_sale',['id'=>$request->location]);
            }
        }

        $data['locations'] = Location::where('location_status',1)->get();

        $data['set'] = 'create_sale';
        return view('admin.sale.search_location',$data);
    }

    public function create_sale(Request $request)
    {
        
        $data['location'] = Location::where('location_id',$request->segment(3))->first();

        if(!isset($data['location']))
        {
            return redirect()->route('search_location');
        }

        $data['customers'] = Customer::where('customer_status',1)->get();

        $where_seller['user_role'] = 4;
        $where_seller['user_status'] = 1;
        $data['sellers'] = User::where($where_seller)->orderby('user_name','asc')->get();

        $data['category_list'] = Category::where('category_status',1)->get();
        
        $where_product['stock_location'] = $request->segment(3);
        $where_product['product_status'] = 1;
        
        $products = $data['products'] = Stock::getProducts($where_product);
        // dd($data['products']);
        
        if($products->count() > 0)
        {
            foreach($products as $product)
            {
                $category_products[$product->product_category][] = array('id' => $product->product_id,'name' => $product->product_name,'category' => $product->category_name,'price' => $product->product_retail_price,'image' => $product->product_image,'stock_qty' => $product->stock_quantity);
            }

            $data['category_products'] = $category_products;           
        }
        
        $data['cart_items'] = Cart::getContent();
                
        $data['set'] = 'create_sale';
        return view('admin.sale.create_sale',$data);
    }

    public function add_sale(Request $request)
    {
        if($request->has('submit'))
        {
            $rules = ['sale_customer' => 'required'];
            
            $messages = ['sale_customer.required' => 'Please Select Customer'];
                        
            $validator = Validator::make($request->all(),$rules,$messages);
            
            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                if(!$request->has('products'))
                {
                    return redirect()->back()->with('error','Please Select Products');
                }

                $total_sale = Sale::count() + 1;

                $invoice_no = sprintf('%06d', $total_sale);

                $ins['sale_location']           = $request->sale_location;
                $ins['sale_invoice_no']         = $invoice_no;
                $ins['sale_customer']           = $request->sale_customer;
                $ins['sale_sellers']            = json_encode($request->sale_sellers);
                $ins['sale_sub_total']          = $request->sale_sub_total;
                $ins['sale_discount_type']      = $request->sale_discount_type;
                $ins['sale_discount']           = $request->sale_discount;
                $ins['sale_discount_amount']    = $request->sale_discount_amount;
                $ins['sale_tax']                = $request->sale_tax;
                $ins['sale_tax_amount']         = $request->sale_tax_amount;
                $ins['sale_grand_total']        = $request->sale_grand_total;
                $ins['sale_pay_cash']           = $request->method_cash;
                $ins['sale_pay_ccard']          = $request->method_credit;
                $ins['sale_pay_dcard']          = $request->method_debit;
                $ins['sale_added_on']           = date('Y-m-d H:i:s');
                $ins['sale_added_by']           = Auth::user()->user_id;
                $ins['sale_updated_on']         = date('Y-m-d H:i:s');
                $ins['sale_updated_by']         = Auth::user()->user_id;
                $ins['sale_status']             = 1;

                if($request->method_cash == 1)
                {
                    $ins['sale_pay_camount'] = $request->pcash;
                }
                else
                {
                    $ins['sale_pay_camount'] = NULL;
                }

                if($request->method_credit == 1)
                {
                    $ins['sale_pay_cc_transaction'] = $request->pcredit;
                }
                else
                {
                    $ins['sale_pay_cc_transaction'] = NULL;
                }

                if($request->method_debit == 1)
                {
                    $ins['sale_pay_dc_transaction'] = $request->pdebit;
                }
                else
                {
                    $ins['sale_pay_dc_transaction'] = NULL;
                }

                $add_sale = Sale::create($ins);

                if($add_sale)
                {
                    $products = $request->products;

                    foreach($products as $product)
                    {
                        $ins_item['sale_item_sale']       = $add_sale->sale_id;
                        $ins_item['sale_item_product']    = $product;
                        $ins_item['sale_item_price']      = $request->product_price[$product];
                        $ins_item['sale_item_quantity']   = $request->product_qty[$product];
                        $ins_item['sale_item_total']      = $request->product_price[$product] * $request->product_qty[$product];
                        $ins_item['sale_item_added_on']   = date('Y-m-d H:i:s');
                        $ins_item['sale_item_added_by']   = Auth::user()->user_id;
                        $ins_item['sale_item_updated_on'] = date('Y-m-d H:i:s');
                        $ins_item['sale_item_updated_by'] = Auth::user()->user_id;
                        $ins_item['sale_item_status']     = 1;

                        SaleItem::create($ins_item);

                        $where_stock['stock_location'] = $request->sale_location;
                        $where_stock['stock_product']  = $product;
                        $stock = Stock::where($where_stock)->first();

                        $upd['stock_quantity'] = $stock->stock_quantity - $request->product_qty[$product];
                        
                        Stock::where($where_stock)->update($upd);
                    }

                    Cart::clear();

                    return redirect()->route('admin.search_location')->with('success','Sale Created Successfully');
                }
            }            
        }
    }

    public function add_to_cart(Request $request)
    {
        $where['product_id'] = $request->product_id;
        $product = Product::getDetail($where);

        Cart::add([
            'id' => $request->product_id,
            'name' => $product->product_name,
            'price' => $product->product_retail_price,
            'quantity' => $request->qty,
            'attributes' => array(
                'min_price' => $product->product_min_price,
                'code' => $product->product_code,
                'category' => $product->category_name,
                'image' => $product->product_image,
            )
        ]);
        


        $data['cart_items'] = Cart::getContent();
        

        return view('admin.sale.cart_products',$data);
    }

    public function update_cart(Request $request)
    {
        // dd($request->edit_price);

        if ($request->edit_price >= 0) {
            Cart::update(
                $request->product_id,
                [
                    'quantity' => [
                        'relative' => false,
                        'value' => $request->qty
                    ],
                    'price' => $request->edit_price
                    
                ]
            );
        }else{

            Cart::update(
                $request->product_id,
                [
                    'quantity' => [
                        'relative' => false,
                        'value' => $request->qty
                    ]                                    
                ]
            );

        }
      
    }

    public function remove_cart(Request $request)
    {
        Cart::remove($request->product_id);

        $data['cart_items'] = Cart::getContent();

        return view('admin.sale.cart_products',$data);
    }

    public function clear_cart(Request $request)
    {
        Cart::clear();

        return redirect()->back();
    }

    public function reset_sale(Request $request)
    {
        Cart::clear();

        return redirect()->back();
    }
    public function verify_pin(Request $request)
    {
        $pin = $request->pin;
        $role = Role::find(auth()->user()->user_role);
        $url = route('admin.sales');
        if($pin == auth()->user()->pin){
            if($role->role_name == 'Employee')
            {
                return response()->json(['status'=>'success','url'=>$url]);
            }
            else{
                return response()->json(['status'=>'success','url'=>$url]);
            }
        }else{
            return response()->json(['status'=>'error','message'=>'PIN is invalid']);
        }
    }
}