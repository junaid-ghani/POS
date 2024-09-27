<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Validator;
use DataTables;
use Carbon\Carbon;
use App\Models\Setting;
use App\Models\SalesApprovalRequestUser;
use App\Models\Notification;
use App\Models\NotificationItem;
use App\Models\Location;
use Illuminate\Support\Facades\DB;

class AdminSettingController extends Controller
{
    public function setting(Request $request)
    {
        if ($request->has('general_setting')) {
            
            
            $setting = Setting::find($request->setting_id);
            $setting->allow_transfer = $request->has('allow_transfer') ? 1 : 0;
            $setting->allow_changes = $request->has('allow_changes') ? 1 : 0;
            $setting->allow_check = $request->has('allow_check') ? 1 : 0;
            $setting->allow_payment_app = $request->has('allow_payment_app') ? 1 : 0;
            $setting->allow_zella = $request->has('allow_zella') ? 1 : 0;            
            $setting->allow_paypal = $request->has('allow_paypal') ? 1 : 0;
            $setting->allow_venmo = $request->has('allow_venmo') ? 1 : 0;
            $setting->allow_cash_app = $request->has('allow_cash_app') ? 1 : 0;

            if (!empty($request->zella_file))
            {                    
                $zella_file_img = $request->zella_file->store('assets/admin/uploads/setting');                        
                $zella_file_img = explode('/',$zella_file_img);
                $zella_file_img = end($zella_file_img);
                $setting->zella_file = $zella_file_img;                   
            }
            if (!empty($request->paypal_file))
            {                    
                $paypal_file_img = $request->paypal_file->store('assets/admin/uploads/setting');                        
                $paypal_file_img = explode('/',$paypal_file_img);
                $paypal_file_img = end($paypal_file_img);
                $setting->paypal_file = $paypal_file_img;                   
            }
            if (!empty($request->venmo_file))
            {                    
                $venmo_file_img = $request->venmo_file->store('assets/admin/uploads/setting');                        
                $venmo_file_img = explode('/',$venmo_file_img);
                $venmo_file_img = end($venmo_file_img);
                $setting->venmo_file = $venmo_file_img;                   
            }
            if (!empty($request->cash_app_file))
            {                    
                $cash_app_file_img = $request->cash_app_file->store('assets/admin/uploads/setting');                        
                $cash_app_file_img = explode('/',$cash_app_file_img);
                $cash_app_file_img = end($cash_app_file_img);
                $setting->cash_app_file = $cash_app_file_img;                   
            }
            
            
            $setting->save();
            
            SalesApprovalRequestUser::where('setting_id', $setting->setting_id)->delete();
            $request_users = $request->sales_approval_request_user;            
            if ($request_users) {
                foreach ($request_users as $key => $value) {
                   
                        $user = new SalesApprovalRequestUser();
                        $user->setting_id = $setting->setting_id;
                        $user->user_id = $value;
                        $user->save();
                }
            }
          
            if ($setting) {                
                return redirect()->route('admin.setting')->with('success', 'Settings Updated Successfully');
            }
            else {
                return redirect()->route('admin.setting')->with('error', 'Settings not Found');
            }
        }

        if($request->has('business_information_setting'))
        {
            
            $rules = ['company_name' => 'required',
                      'company_email' => 'required|email',
                      'company_password' => 'required',
                      'contact_name' => 'required',                   
                      'contact_address' => 'required',       
                      'contact_phone' => 'required',       
                      'contact_email' => 'required',                   
                    ];

            $messages = ['company_name.required' => 'Please Enter Customer',
                         'company_email.required' => 'Please Enter Email',
                         'company_email.email' => 'Please Enter Valid Email',
                         'company_password.required' => 'Please Enter Password',
                         'contact_name.required' => 'Please Enter Contact Name',
                         'contact_address.required' => 'Please Enter Contact Address ',
                         'contact_phone.required' => 'Please Enter Contact Phone ',
                         'contact_email.required' => 'Please Enter Contact Email ',
                         'contact_email.email' => 'Please Enter Valid Email ',

                        ];

            $validator=Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {            
                $setting = Setting::find($request->setting_id);

                if ($setting) {
                    
                    $setting->company_name = $request->company_name;
                    $setting->company_email = $request->company_email;
                    $setting->company_password = $request->company_password;
                    $setting->contact_address = $request->contact_address;
                    $setting->contact_phone = $request->contact_phone;
                    $setting->contact_email = $request->contact_email;

                    // Save the changes
                    $setting->save();

                    return redirect()->route('admin.setting')->with('success', 'Settings Updated Successfully');
                } else {
                    return redirect()->route('admin.setting')->with('error', 'Settings not found');
                }
                
            }
        }       
        if ($request->has('receipt_setting')) {
           
            $setting = Setting::find($request->setting_id);
            
            $setting->show_address = $request->has('show_address') ? 1 : 0;
            $setting->show_email = $request->has('show_email') ? 1 : 0;
            $setting->show_phone = $request->has('show_phone') ? 1 : 0;            
            $setting->show_return_policy = $request->has('show_return_policy') ? 1 : 0;
            $setting->show_footer_message = $request->has('show_footer_message') ? 1 : 0;

            if ($request->has('show_return_policy') == 1) {
                $setting->show_return_policy_value = $request->returnPolicy;
            }
            if ($request->has('show_footer_message') == 1) {
                $setting->show_footer_message_value = $request->footerMessage;
            }

            $setting->save();
            if ($setting) {                
                return redirect()->route('admin.setting')->with('success', 'Settings Updated Successfully');
            }
        }
        $data['set'] = 'setting';        
        $data['setting'] = Setting::first();
        // $data['notification'] = Notification::all();
        // $data['notificationItem'] = NotificationItem::all();
        $data['notification'] = Notification::with('notificationItem')->orderBy('notification_id','asc')->get();
        $data['location'] = Location::Where('location_id',3)->first();
        return view('admin.setting.setting',$data);
    }

    public function getUser(Request $request){

        if ($request->ajax()) {
            
            $user = DB::table('users as user')
            ->select('user.*')
            ->where('user.user_role', '!=', '1')
            ->where('user.user_role', '!=', '4')
            ->where('user.user_status', '=', '1')            
            ->orderBy('user.user_name', 'asc')
            ->get();

            $request_user = SalesApprovalRequestUser::where('setting_id',$request->setting_id)->get();
            // $notificationItem = NotificationItem::all();
            // $notification = Notification::all();
            return response()->json(['status'=>'success','users'=>$user,'request_user'=>$request_user]);
        }    
    }
    public function getNotificationUser(Request $request){
                
        if ($request->ajax()) {
            
            $user = DB::table('users as user')
            ->select('user.*')
            ->where('user.user_role', '!=', '1')
            ->where('user.user_role', '!=', '4')
            ->where('user.user_status', '=', '1')            
            ->orderBy('user.user_name', 'asc')
            ->get();

            $notificationItem = NotificationItem::all();
            $notification = Notification::all();

            return response()->json(['status'=>'success','users'=>$user,'notification'=>$notification,'notificationItem'=>$notificationItem]);
        }  
    }

    public function add_notification_item(Request $request){
        
        if ($request->has('notification_setting')) { 
                       
            // dd($request->all());
            
            $notification_ids = $request->notification_id;
            $formated_notification_id = json_decode($notification_ids,true);

            if (!empty($formated_notification_id)){
                
                // NotificationItem::whereNotIn('notification_id', $formated_notification_id)->delete();
                
                foreach ($request->status as $key => $value) {

                    $notification_id = $request->notification_id_for_status[$key];                                
                    $notification = Notification::find($notification_id);                    
                    if ($notification) {                        
                        $notification->status = $value;                
                        $notification->trigger_value = $request->trigger_value[$key];                
                        $notification->save(); 
                    }
                }
                
                foreach ($formated_notification_id as $key => $value) {
                
                    $notificationItem = NotificationItem::where('notification_id', $value)->first();
                
                    if (!$notificationItem) {
                        $notificationItem = new NotificationItem();
                    }

                    $notificationItem->notification_id = $value;          

                    $userIdField = 'user_id_' . $value;
                    $userIds = $request->input($userIdField,[]);                 
                    $notificationItem->user_ids = json_encode($userIds);               
                    $notificationItem->save();
                }
            }
            else{
                NotificationItem::query()->delete();                
            }
            return redirect()->route('admin.setting')->with('success', 'Notification Updated Successfully');

        }
    }

}