<?php

namespace App\Http\Controllers\Admin;

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


class AdminInventoryController extends Controller
{

    public function index(Request $request)
    {        

        //manage inventry
        $data['locations'] = Location::where('location_status',1)->get();    
    
        //transfer
        $where['stock_location'] = $request->from_location;
        $where['product_status'] = 1;
        $data['products'] = Product::getStockDetails($where);

        // transfer
        $data['locations'] = Location::where('location_id','!=',$request->from_location)->where('location_status',1)->get();
        // $data['locations'] = Location::where('location_status',1)->get();
        
        // $data['startOfMonth'] = Carbon::now()->startOfMonth();
        $data['today'] = Carbon::now();

        $data['set'] = 'inventory';
        return view('admin.inventory.inventory',$data);
    }

}