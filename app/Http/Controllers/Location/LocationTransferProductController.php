<?php

namespace App\Http\Controllers\Location;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Validator;
use DataTables;
use App\Models\Category;
use App\Models\Product;
use App\Models\Location;
use App\Models\Stock;

class LocationTransferProductController extends Controller
{
    public function index(Request $request)
    {
        if($request->has('submit'))
        {
           $rules = ['from_location' => 'required',
                     'to_location' => 'required'];

            $messages = ['from_location.required' => 'Please Select From Location',
                         'to_location.required' => 'Please Select To Location'];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                if($request->has('transfer_products'))
                {
                    $products = $request->transfer_products;

                    foreach($products as $product)
                    {
                        $where_from['stock_location'] = $request->from_location;
                        $where_from['stock_product']  = $product;
                        $from_stock = Stock::where($where_from)->first();

                        if(!empty($request->transfer_quantity[$product]))
                        {
                            $qty = $request->transfer_quantity[$product];

                            if($from_stock->stock_quantity >= $qty)
                            {
                                $upd_from['stock_quantity'] = $from_stock->stock_quantity - $qty;

                                Stock::where($where_from)->update($upd_from);

                                $where_to['stock_location'] = $request->to_location;
                                $where_to['stock_product']  = $product;
                                $check_to_stock = Stock::where($where_to)->first();

                                if(!isset($check_to_stock))
                                {
                                    $ins['stock_location']   = $request->to_location;
                                    $ins['stock_product']    = $product;
                                    $ins['stock_quantity']   = $qty;
                                    $ins['stock_added_on']   = date('Y-m-d H:i:s');
                                    // $ins['stock_added_by']   = Auth::user()->user_id;
                                    $ins['stock_updated_on'] = date('Y-m-d H:i:s');
                                    // $ins['stock_updated_by'] = Auth::user()->user_id;
                                    $ins['stock_status']     = 1;

                                    Stock::create($ins);
                                }
                                else
                                {
                                    $upd['stock_quantity']   = $check_to_stock->stock_quantity + $qty;
                                    $upd['stock_updated_on'] = date('Y-m-d H:i:s');
                                    // $upd['stock_updated_by'] = Auth::user()->user_id;

                                    $where_upd['stock_id'] = $check_to_stock->stock_id;
                                    Stock::where($where_upd)->update($upd);
                                }
                            }
                        }
                    }
                }

                return redirect()->back()->with('success','Product Transfered Successfully');
            }
        }

        $data['locations'] = Location::where('location_status',1)->get();

        $data['set'] = 'transfer_products';
        return view('location.transfer_product.transfer_products',$data);
    }

    public function select_transfer_products(Request $request)
    {
        $where['stock_location'] = $request->from_location;
        $where['product_status'] = 1;
        $data['products'] = Product::getStockDetails($where);

        return view('location.transfer_product.select_transfer_products',$data);
    }

    public function select_to_location(Request $request)
    {
        $data['locations'] = Location::where('location_id','!=',$request->from_location)->where('location_status',1)->get();

        return view('location.transfer_product.select_to_location',$data);
    }
}
