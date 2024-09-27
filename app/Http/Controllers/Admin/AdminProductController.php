<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Validator;
use DataTables;
use App\Models\Category;
use App\Models\Location;
use App\Models\Product;

class AdminProductController extends Controller
{
    public function index()
    {
        $data['set'] = 'products';
        return view('admin.product.products',$data);
    }

    public function get_products(Request $request)
    {
        $data = Product::getDetails();

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
                                        <a href="javascript:void(0)" class="product-img stock-img img-thumbnail">
                                        <img src="'.$img_path.'" alt="">
                                        </a>
                                        <a href="javascript:void(0)">'.$row->product_name.'</a>
                                    </div>';

                    return $product_name;
                })
                ->addColumn('product_cost_price',function($row){
                
                    return '$'.$row->product_cost_price ;

                })
                ->addColumn('product_min_price',function($row){
                
                    return '$'.$row->product_min_price ;

                })
                ->addColumn('product_retail_price',function($row){
                
                    return '$'.$row->product_retail_price ;

                })
                ->addColumn('priority', function($row){

                    $priority = '';

                    if($row->product_priority == 1)
                    {
                        $priority = '1 - Best Seller';
                    }
                    elseif($row->product_priority == 2)
                    {
                        $priority = '2';
                    }
                    elseif($row->product_priority == 3)
                    {
                        $priority = '3';
                    }
                    elseif($row->product_priority == 4)
                    {
                        $priority = '4 - Worst Seller';
                    }

                    return $priority;
                })
                ->addColumn('action', function($row){

                    $btn = '<div class="hstack gap-2 fs-15">';

                    // $btn .= '<a href="'.route('admin.view_product',['id'=>$row->product_id]).'" class="btn btn-icon btn-sm btn-dark" title="View"><i class="fa fa-eye"></i></a> ';

                    // $btn .= '<a href="'.route('admin.edit_product',['id'=>$row->product_id]).'" class="btn btn-icon btn-sm btn-info" title="Edit"><i class="fa fa-edit"></i></a> ';

                    $btn .= '<a href="javascript:void(0)" onclick="edit_product('.$row->product_id.')"  class="btn btn-icon btn-sm btn-info" title="Edit"><i class="fa fa-edit"></i></a> ';

                    $btn .= '<a href="'.route('admin.delete_product',['id'=>$row->product_id]).'" class="btn btn-icon btn-sm btn-danger" title="Delete"><i class="fa fa-remove"></i></a> ';

                    // if($row->product_status == 1)
                    // {
                    //     $btn .= '<a href="'.route('admin.product_status',['id'=>$row->product_id,'status'=>$row->product_status]).'" class="btn btn-icon btn-sm btn-danger" title="Click to disable" onclick="confirm_msg(event)" ><i class="fa fa-ban"></i></a>';
                    // }
                    // else
                    // {
                    //     $btn .= '<a href="'.route('admin.product_status',['id'=>$row->product_id,'status'=>$row->product_status]).'" class="btn btn-icon btn-sm btn-dark" title="Click to enable" onclick="confirm_msg(event)"><i class="fa fa-check"></i></a> ';
                    // }
                    
                    $btn .= '</div>';

                    return $btn;
                })
                ->rawColumns(['action','product_name'])
                ->make(true);
    }

    public function add_product(Request $request)
    {

        if($request->has('submit'))
        {
            //dd($request->all());
            $rules = ['product_category' => 'required',
                      'product_name' => 'required|unique:products',
                      'product_code' => 'required',
                      'product_cost_price' => 'required',
                      'product_min_price' => 'required',
                      'product_retail_price' => 'required',
                      'product_priority' => 'required'
                     ];

            $messages = ['product_category.required' => 'Please Select Category',
                         'product_name.required' => 'Please Enter Product',
                         'product_name.unique' => 'Item Name Already Exist',
                         'product_code.required' => 'Please Enter Code',
                         'product_cost_price.required' => 'Please Enter Cost Price',
                         'product_min_price.required' => 'Please Enter Minimum Price',
                         'product_retail_price.required' => 'Please Enter Retail Price',
                         'product_priority.required' => 'Please Select Priority'
                        ];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $where_check['product_code'] = $request->product_code;
                $check_product = Product::where($where_check)->count();

                if($check_product > 0)
                {
                    return redirect()->back()->with('error','Product Already Exists')->withInput();
                }
                else
                {
                    $ins['product_category']     = $request->product_category;
                    $ins['product_name']         = $request->product_name;
                    $ins['product_code']         = $request->product_code;
                    $ins['product_cost_price']   = $request->product_cost_price;
                    $ins['product_min_price']    = $request->product_min_price;                    
                    $ins['product_retail_price'] = $request->product_retail_price;                    
                    // $ins['product_cost_price']   = number_format($request->product_cost_price, 2, '.', '');
                    // $ins['product_min_price']    = number_format($request->product_min_price, 2, '.', '');                    
                    // $ins['product_retail_price'] = number_format($request->product_retail_price, 2, '.', '');
                    $ins['product_priority']     = $request->product_priority;
                    $ins['product_added_on']     = date('Y-m-d H:i:s');
                    $ins['product_added_by']     = Auth::user()->user_id;
                    $ins['product_updated_on']   = date('Y-m-d H:i:s');
                    $ins['product_updated_by']   = Auth::user()->user_id;
                    $ins['product_status']       = 1;

                    if($request->hasFile('product_image'))
                    {
                        $product_image = $request->product_image->store('assets/admin/uploads/product');
                        
                        $product_image = explode('/',$product_image);
                        $product_image = end($product_image);
                        $ins['product_image'] = $product_image;
                    }

                    $add = Product::create($ins);

                    if($add)
                    {
                        // return redirect()->back()->with('success','Product Added Successfully');
                        return redirect()->route('admin.items')->with('success','Product Added Successfully');
                    }
                }
            }
        }

        $data['category_list'] = Category::where('category_status',1)->orderby('category_name','asc')->get();

        $data['set'] = 'items';
        return view('admin.product.add_product',$data);
    }

    public function edit_product(Request $request)
    {
        if($request->has('submit'))
        {
            $rules = ['product_category' => 'required',
                      'product_name' => 'required',
                      'product_code' => 'required',
                      'product_cost_price' => 'required',
                      'product_min_price' => 'required',
                      'product_retail_price' => 'required',
                      'product_priority' => 'required'
                     ];

            $messages = ['product_category.required' => 'Please Select Category',
                         'product_name.required' => 'Please Enter Product',
                         'product_code.required' => 'Please Enter Code',
                         'product_cost_price.required' => 'Please Enter Cost Price',
                         'product_min_price.required' => 'Please Enter Minimum Price',
                         'product_retail_price.required' => 'Please Enter Retail Price',
                         'product_priority.required' => 'Please Select Priority'
                        ];

            $validator = Validator::make($request->all(),$rules,$messages);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $where_check['product_code'] = $request->product_code;
                $check_product = Product::where($where_check)->where('product_id','!=',$request->product_id)->count();
                $check_name = Product::where('product_name',$request->product_name)->where('product_id','!=',$request->product_id)->count();
                if ($check_name > 0) {
                    return redirect()->back()->with('error','Product Name Already Exists')->withInput();
                }
                if($check_product > 0)
                {
                    return redirect()->back()->with('error','Product Already Exists')->withInput();
                }
                else
                {
                    $upd['product_category']     = $request->product_category;
                    $upd['product_name']         = $request->product_name;
                    $upd['product_code']         = $request->product_code;
                    $upd['product_cost_price']   = $request->product_cost_price;
                    $upd['product_min_price']    = $request->product_min_price;
                    $upd['product_retail_price'] = $request->product_retail_price;

                    // $upd['product_cost_price']   = number_format($request->product_cost_price, 2, '.', '');
                    // $upd['product_min_price']    = number_format($request->product_min_price, 2, '.', '');                    
                    // $upd['product_retail_price'] = number_format($request->product_retail_price, 2, '.', '');
                    $upd['product_priority']     = $request->product_priority;
                    $upd['product_updated_on']   = date('Y-m-d H:i:s');
                    $upd['product_updated_by']   = Auth::user()->user_id;
                    
                    if($request->hasFile('product_image'))
                    {
                        $product_image = $request->product_image->store('assets/admin/uploads/product');
                        
                        $product_image = explode('/',$product_image);
                        $product_image = end($product_image);
                        $upd['product_image'] = $product_image;
                    }

                    $add = Product::where('product_id',$request->product_id)->update($upd);

                    // return redirect()->back()->with('success','Product Updated Successfully');
                    return redirect()->route('admin.items')->with('success','Product Updated Successfully');
                }
            }
        }

        // $where['product_id'] = $request->segment(3);
        // $data['product'] = Product::where($where)->first();
        
        // if(!isset($data['product']))
        // {
        //     return redirect()->route('admin.products');
        // }

        
        // $data['category_list'] = Category::where('category_status',1)->orderby('category_name','asc')->get();

        // $data['set'] = 'items';
        // return view('admin.product.edit_product',$data);
    }

    public function view_edit_product(Request $request){
    
        $where['product_id'] = $request->product_id;
        $data['product'] = Product::where($where)->first();
        $data['category_list'] = Category::where('category_status',1)->orderby('category_name','asc')->get();
        
        if(!isset($data['product']))
        {
            return redirect()->route('admin.items');
        }
        $data['set'] = 'Edit';
        return view('admin.product.edit_product',$data);
    }

    public function delete_product($id)
    {
        $delete_product  = Product::find($id);
        $delete_product->delete();
        return redirect()->route('admin.items')->with('success','Delete Product Successfully!');
    }


    public function view_product(Request $request)
    {
        $where['product_id'] = $request->segment(3);
        $data['product'] = Product::getDetail($where);
        
        if(!isset($data['product']))
        {
            return redirect()->route('admin.products');
        }

        $data['set'] = 'products';
        return view('admin.product.view_product',$data);
    }

    public function product_status(Request $request)
    {
        $status = $request->segment(4);

        if($status == 1)
        {
            $upd['product_status'] = 0;
        }
        else
        {
            $upd['product_status'] = 1;
        }

        $where['product_id'] = $request->segment(3);
        Product::where($where)->update($upd);
        
        return redirect()->back()->with('success','Status Changed Successfully!');
    }

}
