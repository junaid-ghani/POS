<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Validator;
use DataTables;
use App\Models\Location;
use App\Models\Category;
use App\Models\Product;
use App\Models\Stock;

class AdminAssignProductController extends Controller
{
    public function index(Request $request)
    {
        if($request->has('submit'))
        {
            $rules = ['stock_location' => 'required',
                    //   'stock_category' => 'required',  
                    //   'stock_product' => 'required'
                    ];

            $messages = ['stock_location.required' => 'Please Select Location',
                        //  'stock_category.required' => 'Please Select Category',
                        //  'stock_product.required' => "Please Select Product"
                        ];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                if(!empty($request->stock_product))
                {
                    $products = $request->stock_product;

                    foreach($products as $product)
                    {
                        $ins['stock_location']   = $request->stock_location;
                        $ins['stock_product']    = $product;
                        $ins['stock_quantity']   = 0;
                        $ins['stock_added_on']   = date('Y-m-d H:i:s');
                        $ins['stock_added_by']   = Auth::user()->user_id;
                        $ins['stock_updated_on'] = date('Y-m-d H:i:s');
                        $ins['stock_updated_by'] = Auth::user()->user_id;
                        $ins['stock_status']     = 1;

                        Stock::create($ins);
                    }
                }
                
                return redirect()->back()->with('success','Product Assigned Successfully');
            }
        }

        $data['locations'] = Location::where('location_status',1)->get();
        // $data['category_list'] = Category::where('category_status',1)->orderby('category_name','asc')->get();

        $data['set'] = 'assign_products';
        return view('admin.assign_product.assign_products',$data);
    }

    public function select_products(Request $request)
    {
        $stocks = Stock::where('stock_location',$request->location_id)->get();

        $stock_products = array();

        if($stocks->count() > 0)
        {
            foreach($stocks as $stock)
            {
                $stock_products[] = $stock->stock_product;
            }
        }

        // $where_product['product_category'] = $request->category_id;
        $where_product['product_status'] = 1;
        $data['products'] = Product::where($where_product)->whereNotIn('product_id',$stock_products)->get();

        return view('admin.assign_product.select_products',$data);
        // return view('admin.assign_product.select_products',$data);
    }
}
