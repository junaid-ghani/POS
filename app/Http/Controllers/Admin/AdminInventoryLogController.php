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
use App\Models\User;
use App\Models\Sale;
use App\Models\InventoryLog;
use App\Models\InventoryLogItem;

class AdminInventoryLogController extends Controller
{

    public function get_inventory_log(Request $request)
    {        
        // dd($request->all());
        $data = InventoryLog::whereBetween('inventory_log_added_on', [
            date('Y-m-d 00:00:00', strtotime($request->start_date)),
            date('Y-m-d 23:59:59', strtotime($request->end_date))
        ])->orderBy('inventory_log_id','desc')->get();
        return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('timestamp', function($row){

                    $timestamp = date('d M Y h:i A',strtotime($row->inventory_log_added_on));
                    return $timestamp;
                })
                ->addColumn('location', function($row){

                    $location = Location::where('location_id',$row->location_id)->first();
                    return $location->location_name;
                    
                })            
                ->addColumn('submitted_by', function($row){
                 
                        $user = User::where('user_id', $row->user_id)->first();                        
                        return $user->user_name;                    
                })
                ->addColumn('status', function($row){
                    
                    if ($row->status == '0') {
                        return 'OK';    
                    }
                    if ($row->status == '1') {
                        return 'Errors';    
                    }
                    if ($row->status == '2') {
                        return 'Fixed';    
                    }
                    
                })
                ->addColumn('action', function($row){

                    $location=Location::where('location_id',$row->location_id)->first();
                
                    $btn = '<div class="hstack gap-2 fs-15">';

                    $btn .= '<a href="javascript:void(0)"  
                            data-location="' . $location->location_name . '" 
                            data-timestamp="' . $row->inventory_log_added_on . '"  
                            data-id="' . $row->inventory_log_id . '" 
                            data-bs-toggle="modal" 
                            data-bs-target="#Inventory_log_Information" 
                            class="btn btn-icon btn-sm btn-dark inventory_action" 
                            title="Open">
                            <i class="fa-solid fa-box"></i></a>';
                           
                    $btn .= '</div>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        
    }

    public function get_inventory_log_item(Request $request,$id){

        // dd($request->all());

        $query = InventoryLogItem::where('inventory_log_id',$id);
         
        if ($request->needing_attention == 1) {
            $query->whereRaw('stock - count != 0');
        }

        return Datatables::of($query)
        ->addIndexColumn()
        ->addColumn('product_name', function($row){

            $product = Product::where('product_id',$row->product_id)->first();
            return $product->product_name;
        })
        ->addColumn('stock', function($row){

            return $row->stock;
        })            
        ->addColumn('count', function($row){
                                 
            return $row->count;                    
        })
        ->addColumn('difference', function($row){

            $diff = '';
            $diff .= '<input type="hidden" name="product_stock_quantity['.$row->product_id.']" value="'.$row->stock.'" >';                        
            $diff .= '<input type="hidden" name="product_count_quantity['.$row->product_id.']" value="'.$row->count.'" >';                        
            $diff .= '<p>'.($row->stock - $row->count).'</p>'; 
            return $diff;
            
        })
        ->rawColumns(['difference'])
        ->make(true);

    }

    // public function fix_inventory_log_item(Request $request){

    //     $stock_quantity = $request->product_stock_quantity;
    //     $stock_count = $request->product_count_quantity;

    //     foreach ($stock_count as $key => $value) {

    //         $logItem = InventoryLogItem::where('inventory_log_id',$request->inventory_log_id)->first();
    //         $logItem->product_stock_quantity = $value->product_count_quantity[$key];
    //         $logItem->update();
    //     }

    //     return redirect()->route('admin.inventory')->with('success', 'Stock Fixed Successfully');

    // }

    // public function fix_inventory_log_item(Request $request) {
        
    //     $logItem = InventoryLogItem::where('inventory_log_id', $request->inventory_log_id)->get();

    //     foreach ($logItem as $key => $count) {
            
    //         $count->stock = $count->count;
    //         $count->update();            
    //     }    

    //     $log = InventoryLog::where('inventory_log_id',$request->inventory_log_id)->first();
       
    //     if ($log) {            
    //         $log->status = 0;
    //         $log->update();
    //     }
       
    //     return redirect()->route('admin.inventory')->with('success', 'Stock Fixed Successfully');
    // }


    public function fix_inventory_log_item(Request $request) {
        
        // dd($request->all());

        $log = InventoryLog::where('inventory_log_id',$request->inventory_log_id)->first();
       
        if ($log) {            
            $log->status = 2;
            $log->update();
        }


        $logItem = InventoryLogItem::where('inventory_log_id', $request->inventory_log_id)->get();
        
        
        // $upd['stock_quantity'] = $stock->stock_quantity + $product_quantity;
        // dd($log);
        
        foreach ($logItem as $key => $count) {
            
            $stock = Stock::where('stock_location', $log->location_id)
            ->where('stock_product', $count->product_id)
            ->first();

            // dd($count);

            if ($stock) {
                $stock->stock_quantity = $count->count; 
                $stock->save(); 
            }


            $count->stock = $count->count;
            $count->update();            
        }    

       
       
        return redirect()->route('admin.inventory')->with('success', 'Stock Fixed Successfully');
    }
    
}