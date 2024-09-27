<?php

namespace App\Http\Controllers\Location;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mail;
use Cart;
use Auth;
use Validator;
use DataTables;
use DB;
use Carbon\Carbon;
use App\Models\Location;
use App\Models\Customer;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Sale;
use App\Models\Role;
use App\Models\SaleItem;
use App\Models\EmployeeRequest;
use App\Models\Commission;
use App\Models\Bonus;
use App\Models\Setting;
use App\Models\EmployeeRequestSale;
use App\Models\EmployeeRequestSaleItem;
use App\Models\InventoryLog;
use App\Models\InventoryLogItem;
use App\Models\Notification;
use App\Mail\SendReceipt;
use App\Mail\SendReceiptCopy;
use App\Mail\AllowEmployee;
use App\Mail\FirstSale;
use App\Mail\LargeSale;
use App\Mail\TotalLocationSale;

class LocationSaleController extends Controller
{
    public function index()
    {        
        
        $data['locations'] = Location::where('location_status',1)->get();
        $data['today'] = date('Y-m-d');
        $data['set'] = 'sales';
        
        $commission = Commission::all();
        $where_seller['user_status'] = 1;        
        $data['sellers'] = User::where($where_seller)->where('user_role', '!=', 1)->orderby('user_name','asc')->get();
        
        $sessionUserId = session('verified_user_id');
        $data['user'] = User::where('user_role','!=', 1)->where('user_id',$sessionUserId)->first();

        return view('location.sale.sales',$data);
    }
    
    public function get_sales(Request $request)
    {      
        $where = 'sale_status = 1';
        
        $verifiedUserId = session('verified_user_id');
        $userRole = User::where('user_id', $verifiedUserId)->value('user_role');
        // $userRole = User::where('user_id', $verifiedUserId)->value('user_role');

        if (!empty($verifiedUserId) && $userRole == 4) 
        {
            $where .= ' AND JSON_CONTAINS(sale_sellers, \'["'.$verifiedUserId.'"]\')';            
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
        // $sellers = json_decode($data->sale_sellers, true);
        // $commission = Commission::where('sale_id', $data->$sellers)->with('seller')->get();
        
        
        return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('sale_date', function($row){

                    $sale_date = date('d M Y h:i A',strtotime($row->sale_added_on));
                    return $sale_date;

                })
                ->addColumn('sellers', function($row){

                        $sellers_id =[];
                        foreach ($row->commission_seller as $seller){

                            $sellers_id[] = $seller->seller_id;                            
                        }
                    
                        $users = User::whereIn('user_id', $sellers_id)->get();
                        
                        $userNames = array_column($users->toArray(), 'user_name', 'user_id');
            
                        $sessionUserId = session('verified_user_id');
                        
                        $userRole = User::where('user_id', $sessionUserId)->value('user_role');
                        
                        $sellersDisplay = [];
                        foreach ($users as $user) {
                            if (isset($userNames[$user->user_id])) {
                                if ($userRole != 4 || $user->user_id == $sessionUserId) {                                  
                                    $sellersDisplay[] = $userNames[$user->user_id];
                                } 
                            }
                        }
                        
                        return implode(', ', $sellersDisplay);
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
        return response()->json(['status'=>'success','location'=>$location,'sale'=> $sale,'saleItems' => $saleItems ,'commission' => $commission,'seller'=>$sellers,'customers'=>$customers]);
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
            
            return redirect()->route('location.sales', Auth::guard('location')->id())->with('success', 'Email Receipt Successfully');
        }


    }

    public function delete_sale(Request $request ,$id)
    {
          
       $id = $request->saleId;
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
            // return redirect()->route('location.sales', Auth::guard('location')->id())->with('success', 'Sale Deleted and Stock Updated Successfully');
            return response()->json(['status'=>'success','message'=>'Sale Deleted and Stock Updated Successfully']);

        }
        // return redirect()->back()->with('error', 'Sale Not Found');
        return response()->json(['status'=>'error','message'=>'Sale Not Found']);


       }else{
        
            $sale = Sale::find($id);
            if ($sale) {
                $sale->delete();
                // return redirect()->route('location.sales', Auth::guard('location')->id())->with('success', 'Sale Deleted Successfully');
                return response()->json(['status'=>'success2','message'=>'Sale Deleted Successfully']);

            }
            // return redirect()->back()->with('error', 'Sale Not Found');
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
            foreach ($saleSellers as $index => $seller_id)
            {
                $where['sale_id'] = $request->sale_id;
                $where['seller_id'] = $seller_id;
    
                $commission = Commission::where($where)->first();
                // $bonus = Bonus::where('seller_id',$seller_id)->where('commission_id',$commissions[$index])->first();
                // dd($bonus);

                // $bonus = new Bonus();            
                // $bonus->bonus = number_format((float)0, 2, '.', '');  
                // $bonus->seller_id = $sellerId;  
                // $bonus->commission_id = $commission->commission_id;  
                // $bonus->commission_date = $commission->commission_added_on; 
                // $bonus->bonus_added_on = date('Y-m-d H:i:s');
                // $bonus->bonus_updated_on = date('Y-m-d H:i:s');
                // $bonus->save();

    
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
                        
                    $bonus = new Bonus();            
                    $bonus->bonus = number_format((float)0, 2, '.', '');  
                    $bonus->seller_id = $seller_id;  
                    $bonus->commission_id = $commission->commission_id;  
                    $bonus->commission_date = $commission->commission_added_on; 
                    $bonus->bonus_added_on = date('Y-m-d H:i:s');
                    $bonus->bonus_updated_on = date('Y-m-d H:i:s');
                    $bonus->save();

                }
                $processedSellers[] = $seller_id;
            }


            Commission::where('sale_id', $request->sale_id)->whereNotIn('seller_id', $processedSellers)->delete();

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

    // public function search_location(Request $request)
    // {
    //     if($request->has('submit'))
    //     {
    //         $rules = ['location' => 'required'];
            
    //         $messages = ['location.required' => 'Please Select Location'];
                        
    //         $validator = Validator::make($request->all(),$rules,$messages);
            
    //         if($validator->fails())
    //         {
    //             return redirect()->back()->withErrors($validator->errors())->withInput();
    //         }
    //         else
    //         {
    //             Cart::clear();

    //             return redirect()->route('admin.create_sale',['id'=>$request->location]);
    //         }
    //     }

    //     $data['locations'] = Location::where('location_status',1)->get();

    //     $data['set'] = 'create_sale';
    //     return view('location.sale.search_location',$data);
    // }

    public function create_sale(Request $request)
    {  

        if ($request->cl == 1) {
            
            Cart::clear(); 
        }

        $data['location'] = Location::where('location_id',$request->segment(3))->first();        

        if(!isset($data['location']))
        {
            return redirect()->route('search_location');
        }

        $data['customers'] = Customer::where('customer_status',1)->get();

        $where_seller['user_status'] = 1;
        $data['sellers'] = User::where($where_seller)->where('user_role', '!=', 1)->orderby('user_name','asc')->get();

        $data['category_list'] = Category::where('category_status',1)->get();
        
        $where_product['stock_location'] = $request->segment(3);
        $where_product['product_status'] = 1;
        
        $products = $data['products'] = Stock::getProducts($where_product);        
        
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
        
        return view('location.sale.create_sale',$data);
    }

    public function getCustomers()
    {      
        $customers = Customer::where('customer_status',1)->get(); 
        return response()->json($customers);
    }

    public function add_sale(Request $request)
    {
        if($request->has('submit'))
        {      
                if(!$request->has('products'))
                {
                    return redirect()->back()->with('error','Please Select Products');
                }

                // --- Send First Sale Notification to managers ---

                $userIds = json_decode($request->sale_sellers,true);
                $users_for_firstSale_email = User::where('user_status', 1)->whereIn('user_id', $userIds)->pluck('user_id')->toArray();

                $today = Carbon::today();

                $sales = Sale::whereDate('sale_added_on', $today)->where('sale_location',$request->sale_location)->get();

                $allSellers = [];
            
                foreach($sales as $sale) {
                    
                    $sellers = json_decode($sale->sale_sellers, true);
                    $allSellers = array_merge($allSellers, $sellers);
                }

                $array_user_id = []; 

                foreach ($users_for_firstSale_email as $key => $user) {
                    if (!in_array($user, $allSellers)) {
                        $array_user_id[] = $user;
                    }
                }

                $firstSaleUser = User::whereIn('user_id',$array_user_id)->where('user_status', 1)->get();

                $notification = Notification::where('notification_id', '1')->with('notificationItem')->first();
                $user_id = $notification->notificationItem->user_ids;
                $formated_user_id = json_decode($user_id, true);      

                $admin_user = User::whereIn('user_id', $formated_user_id)
                                ->where('user_role', '!=', 1)
                                ->where('user_status', 1)
                                ->get();
                
                $emails = $admin_user->pluck('user_email');                                 


                foreach ($firstSaleUser as $key => $value) {
                    
                    $first_sale = [     
                        'sale_location' => $request->sale_location,
                        'user_name' => $value->user_name,
                        'sale' => $request->sale_grand_total
                    ];
            
                    foreach ($emails as $email) {

                        Mail::to($email)->send(new FirstSale($first_sale));
                    }
                }   
                
                // --- end First Sale Notification ---
                     

                $cart_items = Cart::getContent();
             
                // $total_sale = Sale::count() + 1;
       
                // $invoice_no = sprintf('%06d', $total_sale);                            

                $ins['sale_location']           = $request->sale_location;
                // $ins['sale_invoice_no']         = $invoice_no;
                $ins['sale_customer']           = $request->sale_customer;
                $ins['sale_sellers']            = $request->sale_sellers;
                $ins['sale_sub_total']          = $request->sale_sub_total;
                $ins['sale_discount_type']      = $request->sale_discount_type;
                $ins['sale_discount']           = $request->sale_discount;
                $ins['sale_discount_amount']    = $request->sale_discount_amount;
                $ins['sale_tax']                = $request->sale_tax;
                $ins['sale_tax_amount']         = $request->sale_tax_amount;
                $ins['sale_grand_total']        = $request->sale_grand_total;
                $ins['change_amount']           = $request->change_amount ?? 0; 
                $ins['sale_pay_cash']           = $request->method_cash;
                $ins['sale_pay_ccard']          = $request->method_credit;
                $ins['sale_pay_dcard']          = $request->method_debit;

                $ins['sale_pay_payment_app']    = $request->method_payment_app;
                $ins['sale_pay_check']          = $request->method_check;
    
                $timezone = $request->timezone;
                $currentDateTime = \Carbon\Carbon::now($timezone);
                $formattedDateTime = $currentDateTime->format('Y-m-d H:i:s');

                $ins['sale_added_on']           = $formattedDateTime;
                $ins['sale_added_by']           = Auth::guard('location')->id();
                $ins['sale_updated_on']         = $formattedDateTime;
                $ins['sale_updated_by']         = Auth::guard('location')->id();
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
                    $ins['sale_pay_cc_last_no'] = $request->last4;                    
                    $ins['sale_pay_ccamount'] = $request->pa_cradit_card;
                }
                else
                {
                    $ins['sale_pay_cc_transaction'] = NULL;
                    $ins['sale_pay_ccamount'] = NULL;
                }
                
                if($request->method_payment_app == 1)
                {
                    $ins['sale_pay_pa_transaction'] = $request->payment_app;    
                    $ins['sale_pay_pa_amount'] = $request->pa_payment_app;                
                }
                else
                {
                    $ins['sale_pay_pa_transaction'] = NULL;
                    $ins['sale_pay_pa_amount'] = NULL;
                }

                if($request->method_check == 1)
                {
                    $ins['sale_pay_check_transaction'] = $request->ck_name;
                    $ins['sale_pay_check_no'] = $request->ck_no;
                    $ins['sale_pay_ckamount'] = $request->pa_check;    
                }
                else
                {
                    $ins['sale_pay_check_transaction'] = NULL;
                    $ins['sale_pay_ckamount'] = NULL;    
                }


                $add_sale = Sale::create($ins);


                      
                if ($add_sale) {
                            

                    $generate_invoice = Sale::where('sale_id',$add_sale->sale_id)->first();;
                    $generate_invoice->sale_invoice_no = sprintf('%06d', $add_sale->sale_id);
                    $generate_invoice->update();

                    $saleSellers = json_decode($add_sale->sale_sellers, true);
                    $commissions = json_decode($request->commission, true); // Assuming this is an array
                    $amounts = json_decode($request->amount, true); // Assuming this is an array

                    $timezone = $request->timezone;
                    $currentDateTime = \Carbon\Carbon::now($timezone);
                    $formattedDateTime = $currentDateTime->format('Y-m-d H:i:s');

                    foreach ($saleSellers as $index => $sellerId) {

                        $commission = new Commission();

                        $commission->sale_id = $add_sale->sale_id;
                        $commission->seller_id = $sellerId;
                        $commission->commission = $commissions[$index]; 
                        $commission->amount = $amounts[$index];
                        $commission->commission_added_on = $formattedDateTime;
                        $commission->commission_updated_on = $formattedDateTime;
                        $commission->save();

                        $bonus = new Bonus();            
                        $bonus->bonus = number_format((float)0, 2, '.', '');  
                        $bonus->seller_id = $sellerId;  
                        $bonus->commission_id = $commission->commission_id;  
                        $bonus->commission_date = $commission->commission_added_on; 
                        $bonus->bonus_added_on = date('Y-m-d H:i:s');
                        $bonus->bonus_updated_on = date('Y-m-d H:i:s');
                        $bonus->save();

                    }
                    $cart_items = Cart::getContent();                  
                    $saleItems = [];
                    foreach ($cart_items as $item) {
                        
                        $ins_item = [
                            'sale_item_sale' => $add_sale->sale_id,
                            'sale_item_product' => $item->attributes->product_id,
                            'sale_item_price' => number_format($item->price, 2),
                            'sale_item_quantity' => $item->quantity,
                            'sale_item_total' => number_format($item->price * $item->quantity,2),
                            'sale_item_added_on' => date('Y-m-d H:i:s'),
                            'sale_item_added_by' => Auth::guard('location')->id(),
                            'sale_item_updated_on' => date('Y-m-d H:i:s'),
                            'sale_item_updated_by' => Auth::guard('location')->id(),
                            'sale_item_status' => 1,
                        ];
                            
                        $sale_item = SaleItem::create($ins_item);                                                                                                             
                        $saleItems[] = $sale_item;

                        $where_stock = [
                            'stock_location' => $request->sale_location,
                            'stock_product' => $item->attributes->product_id,
                        ];
        
                        $stock = Stock::where($where_stock)->first();
                        $upd['stock_quantity'] = $stock->stock_quantity - $item->quantity;
                        Stock::where($where_stock)->update($upd);
                    }
                    



                    $location = Location::where('location_status', 1)->where('location_id', $add_sale->sale_location)->first();
                  
                    $setting = Setting::first();

                    $userIds = json_decode($request->sale_sellers,true);
                    $users_for_email = User::where('user_status', 1)->whereIn('user_id', $userIds)->get();

                    if($request->input('submit') === 'email_receipt') {

                        $receipt_data = [
                            'company_name' =>  $setting->company_name,  
                            'sale_location' =>  $location->location_name,                                
                            'location_address' =>  $location->location_address,
                            'location_city' =>  $location->location_city,
                            'location_state' =>  $location->location_state,                                
                            'location_phone_number' =>  $location->location_phone_number,                                
                            'sale_invoice_no' =>  $generate_invoice->sale_invoice_no,                                
                            'sale_added_on' => $add_sale->sale_added_on,
                            'sale_discount' => $add_sale->sale_discount,
                            'sale_discount_amount' => $add_sale->sale_discount_amount,
                            'sale_sub_total' => $add_sale->sale_sub_total,
                            'sale_tax' => $add_sale->sale_tax_amount,
                            'sale_grand_total' => $add_sale->sale_grand_total,                            
                            'sale_pay_cash' => $add_sale->sale_pay_cash,
                            'sale_pay_ccard' => $add_sale->sale_pay_ccard,
                            'sale_pay_payment_app' => $add_sale->sale_pay_payment_app,
                            'sale_pay_check' => $add_sale->sale_pay_check,
                            'users_for_email' => $users_for_email,
                            'sale_items' => $saleItems,
                            ];
                        Mail::to($request->send_email)->send(new SendReceipt($receipt_data));
                    }  
                    
                    

                    //  --- Large Sale Notification to Manager---

                    $notification2 = Notification::where('notification_id','2')->with('notificationItem')->first();
                    $user_id2 = $notification2->notificationItem->user_ids;
                    $formated_user_id2 = json_decode($user_id2, true);      
                    $admin_user2=User::whereIn('user_id',$formated_user_id2)->where('user_role','!=',1)->where('user_status',1)->get();
            

                    if ($request->sale_grand_total > $notification2->trigger_value) {
                    
                        foreach ($users_for_email as $key => $value) {                        
                       
                            $large_sale = [
                                'sale_location' => $request->sale_location,
                                'user_name' => $value->user_name,
                                'sale' => $request->sale_grand_total
                            ];
                    
                            $emails2 = $admin_user2->pluck('user_email');
                    
                            foreach ($emails2 as $email) {
                                Mail::to($email)->send(new LargeSale($large_sale));
                            }
                        }
                    }


                // --- Total Location Sales Notification ---

                    $notification4 = Notification::where('notification_id','4')->with('notificationItem')->first();
                    $user_id4 = $notification4->notificationItem->user_ids;
                    $formated_user_id4 = json_decode($user_id4, true);      
                    $admin_user4=User::whereIn('user_id',$formated_user_id4)->where('user_role','!=',1)->where('user_status',1)->get();
            
                    $today = Carbon::today();
                    $sale4 = DB::table('sales')
                                ->whereDate('sale_added_on', $today) 
                                ->where('sale_location', $request->sale_location) 
                                ->select('sale_location', DB::raw('SUM(sale_grand_total) as total_sales')) 
                                ->groupBy('sale_location') 
                                ->first();        
                     
                    $total_location_sale_email = DB::table('total_location_sale_emails')
                    ->where('date', $today)
                    ->where('location_id', $request->sale_location)
                    ->exists();

                    if ($sale4->total_sales > $notification4->trigger_value) {

                    if (!$total_location_sale_email) {
                                            
                        
                            $location = Location::where('location_id',$request->sale_location)->first();

                                $totalLocationSale = [
                                    'sale_location' => $location->location_name,
                                    'exceed_sale' => $notification4->trigger_value
                                ];
                        
                                $emails4 = $admin_user4->pluck('user_email');
                        
                                foreach ($emails4 as $email) {
                                    Mail::to($email)->send(new TotalLocationSale($totalLocationSale));
                                }
        
                                DB::table('total_location_sale_emails')->insert([
                                    'date' => $today,
                                    'location_id' => $request->sale_location,
                                ]);                          
                        }
        
                    }

                // --- end Total Location Sales Notification ---                                        
        
                    Cart::clear();
        
                    return redirect()->route('location.create_sale', Auth::guard('location')->id())->with('success', 'Sale Created Successfully');
                } 
              
        }
    }

    public function add_to_cart(Request $request)
    {        
        // dd($request->all());
        $where['product_id'] = $request->product_id;
        $product = Product::getDetail($where);

        $uniqueId = uniqid();

        Cart::add([
            'id' => $uniqueId,
            'name' => $product->product_name,
            'price' => $product->product_retail_price,
            'quantity' => $request->qty,
            'attributes' => array(
                'product_id' => $request->product_id, // Keep the original product ID as an attribute
                'min_price' => $product->product_min_price,
                'is_readonly' => 0,
                'code' => $product->product_code,
                'category' => $product->category_name,
                'image' => $product->product_image,
            )
        ]);

        $data['cart_items'] = Cart::getContent();

        return view('location.sale.cart_products', $data);
    }

    // public function add_to_cart(Request $request)
    // {
    //     $where['product_id'] = $request->product_id;
    //     $product = Product::getDetail($where);

    //     Cart::add([
    //         'id' => $request->product_id,
    //         'name' => $product->product_name,
    //         'price' => $product->product_retail_price,
    //         'quantity' => $request->qty,
    //         'attributes' => array(
    //             'min_price' => $product->product_min_price,
    //             'is_readonly' => 0,
    //             'code' => $product->product_code,
    //             'category' => $product->category_name,
    //             'image' => $product->product_image,
    //         )
    //     ]);
        
    //     $data['cart_items'] = Cart::getContent();
        
    //     return view('location.sale.cart_products',$data);
    // }


    public function update_cart(Request $request)
    {
        if (isset($request->edit_price) && $request->edit_price >= 0) {
            
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

    public function used_update_cart(Request $request)
    {   

        if (isset($request->edit_price) && $request->edit_price >= 0) {
            
            Cart::update(
                $request->product_id,
                [
                    'quantity' => [
                        'relative' => false,
                        'value' => $request->qty
                    ],
                    'price' => $request->edit_price,
                    'is_readonly' => $request->is_readonly
                ]
            );           
        }else{

            Cart::update(
                $request->product_id,
                [
                    'quantity' => [
                        'relative' => false,
                        'value' => $request->qty
                    ],              
                    'is_readonly' => $request->is_readonly                    
                ]
            );
        }

        $data['cart_items'] = Cart::getContent();
        return view('location.sale.cart_products',$data);
    }

    public function new_update_cart(Request $request)
    {   

        if (isset($request->edit_price) && $request->edit_price >= 0) {
            
            Cart::update(
                $request->product_id,
                [
                    'quantity' => [
                        'relative' => false,
                        'value' => $request->qty
                    ],
                    'price' => $request->edit_price,
                    'is_readonly' => $request->is_readonly                    
                ]
            );           
        }else{

            Cart::update(
                $request->product_id,
                [
                    'quantity' => [
                        'relative' => false,
                        'value' => $request->qty
                    ],
                    'is_readonly' => $request->is_readonly                                    
                ]
            );

        }

        $data['cart_items'] = Cart::getContent();
        return view('location.sale.cart_products',$data);
    }

    public function remove_cart(Request $request)
    {
        Cart::remove($request->product_id);

        $data['cart_items'] = Cart::getContent();

        return view('location.sale.cart_products',$data);
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

    public function location_verify_pin(Request $request)
    {
        // dd($request->all());
        $pin = $request->pin;
        $where_seller['user_status'] = 1;
        $where_seller['pin'] = $pin;
        $user = User::where($where_seller)->where('user_role','!=',1)->first();
       
        if($user){
            session(['verified_user_id' => $user->user_id]); 
            $url = $request->current_route;
            return response()->json(['status'=>'success','url'=>$url]);
        }
        else{
            return response()->json(['status'=>'error','message'=>'PIN is invalid']);
        }
    }

    public function pin_authenticate(Request $request)
    {
        $cart_items = Cart::getContent(); 
        $pin = $request->check_user_pin;
        $where_seller['user_status'] = 1;
        $where_seller['pin'] = $pin;
        $user = User::where($where_seller)->where('user_role','!=',1)->first();
        if ($user) {
            
            if ($user->user_role != 4) {
                return response()->json(['status'=>'success']);
            }
            else{

                $employee_request = new EmployeeRequest();
                $employee_request->save();
                $approvalUrl = route('approve_emp_view',['id'=>$employee_request->emp_request_id ]);

                $emp_request_sale = new EmployeeRequestSale();
                $emp_request_sale->emp_request_id = $employee_request->emp_request_id;
                $emp_request_sale->user_id = $user->user_id;                 
                $emp_request_sale->location_id = Auth::guard('location')->id();
                $emp_request_sale->loss_amount = $request->loss_amount;
                $emp_request_sale->discount = $request->discount;
                $emp_request_sale->sub_total = $request->sub_total;
                $emp_request_sale->tax = $request->tax;
                $emp_request_sale->total = $request->total;
                $timezone = $request->timezone;
                $currentDateTime = \Carbon\Carbon::now($timezone);
                $formattedDateTime = $currentDateTime->format('Y-m-d H:i:s');
                $emp_request_sale->request_sale_added_on = $formattedDateTime;
                $emp_request_sale->request_sale_updated_on = $formattedDateTime;
                $emp_request_sale->save();

                if ($emp_request_sale) {
                    
                    $cart_items = Cart::getContent();                          
                    foreach ($cart_items as $item) {
                        
                        $ins_item = [
                            'employee_request_sale_id' => $emp_request_sale->employee_request_sale_id,
                            'product_id' => $item->attributes->product_id,
                            'price' => number_format($item->price, 2),
                            'quantity' => $item->quantity,
                            'total' => number_format($item->price * $item->quantity,2),
                            'request_sale_item_added_on' => $formattedDateTime,
                            'request_sale_item_updated_on' => $formattedDateTime,
                        ];
                            
                        $sale_item = EmployeeRequestSaleItem::create($ins_item); 
                    }                
                }
           
                $approve_emp = [
                    'employee' =>  $user->user_name,   
                    'total' =>  $request->total,   
                    'approve_Url' =>$approvalUrl
                ];
               
                $setting = Setting::with('request_user')->first();

                $user_ids = [];
                foreach($setting->request_user as $key => $value) {
                    $user_ids[] = $value->user_id;
                }
                
                $request_user = User::whereIn('user_id',$user_ids)->where('user_role','!=',1)->get();
            
                $emails = $request_user->pluck('user_email');

                foreach ($emails as $email) {
                    Mail::to($email)->send(new AllowEmployee($approve_emp));
                }
               
                return response()->json(['status'=>'success2','message'=>'A Manager has been notified and approval is pending','emp_request_id'=>$employee_request->emp_request_id]);
            
            }
        } 
         
        else{
            return response()->json(['status'=>'error','message'=>'PIN is invalid']);
        }
    }

    // public function approve_emp($id)
    // {
    //     $employee_request = EmployeeRequest::find($id);
    //     if ($employee_request) {

    //         $employee_request->status = 1;
    //         $employee_request->save();

    //         return response()->json(['status' => 'success', 'message' => 'Request approved successfully']);
            
    //     } else {
    //         return response()->json(['status' => 'error', 'message' => 'Request not found']);
    //     }
    // }

    public function get_approve_emp(Request $request){
        
        $employee_request = EmployeeRequest::find($request->request_approve_id);
        if ($employee_request) {
            return response()->json(['status' => 'success' ,'request_status'=>$employee_request->status]);
        }
        else{
            return response()->json(['status' => 'error']);
        }

    }
}