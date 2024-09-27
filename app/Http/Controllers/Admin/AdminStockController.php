<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DataTables;
use App\Models\Category;
use App\Models\Product;
use App\Models\Location;
use App\Models\Stock;

class AdminStockController extends Controller
{
    public function index()
    {
        $data['locations'] = Location::where('location_status',1)->get();

        $data['set'] = 'stocks';
        return view('admin.stock.stocks',$data);
    }

    public function get_stocks(Request $request)
    {
        if(!empty($request->stock_location))
        {
            $where['stock_location'] = $request->stock_location;
        }

        $where['stock_status'] = 1;
        $data = Stock::getDetails($where);

        return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('product_name', function($row){
                    
                    if(!empty($row->product_image))
                    {
                        $img_path = asset(config('constants.admin_path').'uploads/product/'.$row->product_image);
                    }
                    else
                    {
                        $img_path = asset(config('constants.admin_path').'img/no_image.jpeg');
                    }                    

                    $product_name = '<div class="productimgname">
                                        <a href="javascript:void(0)" class="product-img stock-img img-thumbnail d-none">
                                        <img src="'.$img_path.'" alt="">
                                        </a>
                                        <a href="javascript:void(0)">'.$row->product_name.'</a>
                                    </div>';

                    return $product_name;
                })
                ->addColumn('qty', function($row){

                    $qty = '<input type="number" name="product_quantity['.$row->stock_product.']" class="form-control" placeholder="Enter Quantity" autocomplete="off">';

                    return $qty;
                })
                ->addColumn('action', function($row){

                    $btn = '<a href="'.route('admin.delete_stock',['id'=>$row->stock_id]).'" class="btn btn-danger btn-sm" title="Delete" onclick="confirm_msg(event)"><i class="fa fa-trash"></i></a>';

                    return $btn;
                })
                ->rawColumns(['action','qty','product_name'])
                ->make(true);
    }
        
    public function update_stock(Request $request)
    {
        if($request->has('submit'))
        {
            if($request->has('product_quantity'))
            {
                foreach($request->product_quantity as $product_id => $product_quantity)
                {
                    if(!empty($product_quantity))
                    {
                        $where['stock_location'] = $request->stock_location;
                        $where['stock_product']  = $product_id;
                        $stock = Stock::where($where)->first();

                        $upd['stock_quantity'] = $stock->stock_quantity + $product_quantity;
                        Stock::where($where)->update($upd);
                    }
                }
            }

            return redirect()->back()->with('success','Stock Updated Successfully!');
        }
    }

    public function delete_stock(Request $request)
    {
        $where['stock_id'] = $request->segment(3);
        Stock::where($where)->delete();

        return redirect()->back()->with('success','Deleted Successfully!');
    }
}