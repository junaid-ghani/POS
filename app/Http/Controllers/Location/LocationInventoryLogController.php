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
use App\Models\User;
use App\Models\Sale;
use App\Models\InventoryLog;
use App\Models\InventoryLogItem;

class LocationInventoryLogController extends Controller
{

    public function index(Request $request)
    {        
        $timezone = $request->timezone;
        $currentDateTime = \Carbon\Carbon::now($timezone);
        $formattedDateTime = $currentDateTime->format('Y-m-d H:i:s');

        $stock_quantity = $request->product_stock_quantity;
        $stock_count = $request->product_quantity;

        $inventory_log = new InventoryLog();
        $inventory_log->location_id = $request->stock_location;
        $inventory_log->user_id = $request->user_id;
        $check_difference = array_diff($stock_count,$stock_quantity);            

        if ($check_difference) {
            $inventory_log->status = 1;
        }else{
            $inventory_log->status = 0;
        }

        $inventory_log->inventory_log_added_on = $formattedDateTime;
        $inventory_log->inventory_log_updated_on = $formattedDateTime;
        $inventory_log->save();

        if ($inventory_log) {
        
            foreach ($request->product_name as $index => $item) 
            {
                $ins_log_item = new InventoryLogItem();
                $ins_log_item->inventory_log_id = $inventory_log->inventory_log_id;
                $ins_log_item->product_id = $item;                          
                $ins_log_item->stock = $request->product_stock_quantity[$index];
                $ins_log_item->count = $request->product_quantity[$index];
                $ins_log_item->inventory_log_item_added_on = $formattedDateTime;
                $ins_log_item->inventory_log_item_updated_on = $formattedDateTime;                                    
                $ins_log_item->save();
            }
        }

        return redirect()->route('location.stocks', Auth::guard('location')->id())->with('success', 'Inventory Log Created Successfully');

    }

}