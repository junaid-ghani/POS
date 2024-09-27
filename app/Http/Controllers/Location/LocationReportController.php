<?php

namespace App\Http\Controllers\Location;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DataTables;
use Mail;
use DB;
use Auth;
use Carbon\Carbon;
use App\Models\Category;
use App\Models\Product;
use App\Models\Location;
use App\Models\Stock;
use App\Models\Setting;
use App\Models\User;
use App\Models\Sale;
use App\Models\Notification;
use App\Mail\EndOfDaySummery;


class LocationReportController extends Controller
{

    public function index()
    {        
        $data['set'] = 'summery';
        return view('location.report.summery',$data);
    }

    public function summery_requert(Request $request)    
    {        
        $verified_user_id = session('verified_user_id');

        $location_id = Auth::guard('location')->id();
        $location = Location::where('location_status','!=',0)->where('location_id',$location_id)->first();

        $where_seller['user_status'] = 1;
        $where_seller['user_id'] = $verified_user_id;
        $user = User::where($where_seller)->where('user_role','!=',1)->first();       

        $sales = DB::table('sales')
        ->whereDate('sale_added_on', Carbon::today())
        ->select('sales.sale_location',DB::raw('SUM(sale_sub_total) as total_sales, SUM(sale_tax_amount) as total_tax , SUM(sale_pay_camount) as total_cash_amount , SUM(sale_pay_ccamount) as total_craditCard_amount , SUM(sale_pay_pa_amount) as total_payment_amount , SUM(sale_pay_ckamount) as total_check_amount'))
        ->where('sale_location',$location_id)
        ->first();

        $totalSales = $sales->total_sales;
        $totalTax = $sales->total_tax;        
        $gross_amount = $totalSales + $totalTax;

        $totalCashAmount = $sales->total_cash_amount;
        $totalCreditAmount = $sales->total_craditCard_amount;        
        $totalPaymentAmount = $sales->total_payment_amount;
        $totalCheckAmount = $sales->total_check_amount;        

        $notification = Notification::where('notification_id','5')->with('notificationItem')->first();
        $user_id = $notification->notificationItem->user_ids;
        $formated_user_id = json_decode($user_id, true);      
        $admin_user=User::whereIn('user_id',$formated_user_id)->where('user_role','!=',1)->where('user_status',1)->get();

        $summery_data = [
            'location_name' => $location->location_name,
            'user_name' => $user->user_name,
            'totalSales' =>  $totalSales,
            'totalTax' =>  $totalTax,
            'gross_amount' =>$gross_amount,
            'totalCashAmount' =>  $totalCashAmount,
            'totalCreditAmount' =>  $totalCreditAmount,
            'totalPaymentAmount' =>  $totalPaymentAmount,
            'totalCheckAmount' =>  $totalCheckAmount,
            'currentDateTime' => $request->currentDateTime
        ];

        $emails = $admin_user->pluck('user_email');
 
        foreach ($emails as $email) {
            Mail::to($email)->send(new EndOfDaySummery($summery_data));
        }

        return response()->json(['status'=>'success','user'=>$user,'sales'=>$sales,'location'=>$location ,'gross_amount'=>$gross_amount]);

    }
}