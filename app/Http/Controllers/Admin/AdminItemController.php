<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Validator;
use DataTables;
use App\Models\Category;
use App\Models\Product;
use App\Models\Location;
use App\Models\Stock;

class AdminItemController extends Controller
{
    public function index(Request $request)
    {
         //manage inventry
         $data['locations'] = Location::where('location_status',1)->get();    
    
         //transfer
        //  $where['stock_location'] = $request->from_location;
        //  $where['product_status'] = 1;
        //  $data['products'] = Product::getStockDetails($where);
 
         // transfer
        //  $data['locations'] = Location::where('location_id','!=',$request->from_location)->where('location_status',1)->get();
         // $data['locations'] = Location::where('location_status',1)->get();
         
        
        //  $data['set'] = 'items';
         $data['category_list'] = Category::where('category_status',1)->orderby('category_name','asc')->get();


         return view('admin.item.item',$data);
    }

}
