<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Validator;
use DataTables;
use App\Models\Category;

class AdminCategoryController extends Controller
{
    public function index()
    {
        $data['set'] = 'category';
        return view('admin.category.category',$data);
    }

    public function get_category(Request $request)
    {
        if($request->ajax())
        {
            $data = Category::orderby('category_id','desc')->get();

            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('category_name', function($row){
                    
                        if(!empty($row->category_image))
                        {
                            $img_path = asset(config('constants.admin_path').'uploads/category/'.$row->category_image);
                        }
                        else
                        {
                            $img_path = asset(config('constants.admin_path').'img/no_image.jpeg');
                        }
    
                        $category_name = '<div class="productimgname">
                                          
                                            <a href="javascript:void(0)">'.$row->category_name.'</a>
                                        </div>';
    
                        return $category_name;
                    })
                    ->addColumn('action', function($row){
                        
                        $btn = '<div class="hstack gap-2 fs-15">';

                        $btn .= '<a href="javascript:void(0)" class="btn btn-icon btn-sm btn-info" onclick="edit_category('.$row->category_id.')" title="Edit"><i class="fa fa-edit"></i></a> ';                        
                        $btn .= '<a href="'.route('admin.delete_category',['id'=>$row->category_id]).'" class="btn btn-icon btn-sm btn-danger" title="Delete"><i class="fa fa-remove"></i></a> ';
                        
                        // if($row->category_status == 1)
                        // {
                        //     $btn .= '<a href="'.route('admin.category_status',['id'=>$row->category_id,'status'=>$row->category_status]).'" class="btn btn-icon btn-sm btn-danger" title="Click to disable" onclick="confirm_msg(event)"><i class="fa fa-ban"></i></a> ';
                        // }
                        // else
                        // {
                        //     $btn .= '<a href="'.route('admin.category_status',['id'=>$row->category_id,'status'=>$row->category_status]).'" class="btn btn-icon btn-sm btn-warning" title="Click to enable" onclick="confirm_msg(event)"><i class="fa fa-check"></i></a>';
                        // }

                        $btn .= '</div>';

                        return $btn;
                    })
                    ->rawColumns(['action','category_name'])
                    ->make(true);
        }
    }

    public function add_category(Request $request)
    {
        if($request->has('submit'))
        {
            $rules = ['category_name' => 'required'];
            
            $messages = ['category_name.required' => 'Please Enter Category'];
                        
            $validator = Validator::make($request->all(),$rules,$messages);
            
            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $where_check['category_name'] = $request->category_name;
                $check_category = Category::where($where_check)->count();

                if($check_category > 0)
                {
                    return redirect()->back()->with('error','Category Already Exists');
                }

                $ins['category_name']       = $request->category_name;
                $ins['category_added_on']   = date('Y-m-d H:i:s');
                $ins['category_added_by']   = Auth::user()->user_id;
                $ins['category_updated_on'] = date('Y-m-d H:i:s');
                $ins['category_updated_by'] = Auth::user()->user_id;
                $ins['category_status']     = 1;

                // if($request->hasFile('category_image'))
                // {
                //     $category_image = $request->category_image->store('assets/admin/uploads/category');
                    
                //     $category_image = explode('/',$category_image);
                //     $category_image = end($category_image);
                //     $ins['category_image'] = $category_image;
                // }

                $add = Category::create($ins);

                if($add)
                {
                    return redirect()->back()->with('success','Category Added Successfully');
                }
            }
        }
    }

    public function edit_category(Request $request)
    {
        if($request->has('submit'))
        {
            $rules = ['category_name' => 'required'];
            
            $messages = ['category_name.required' => 'Please Enter Category'];
                        
            $validator = Validator::make($request->all(),$rules,$messages);
            
            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            else
            {
                $where_check['category_name'] = $request->category_name;
                $check_category = Category::where($where_check)->where('category_id','!=',$request->category_id)->count();

                if($check_category > 0)
                {
                    return redirect()->back()->with('error','Category Already Exists');
                }

                $upd['category_name']       = $request->category_name;
                $upd['category_updated_on'] = date('Y-m-d H:i:s');
                $upd['category_updated_by'] = Auth::user()->user_id;

                // if($request->hasFile('category_image'))
                // {
                //     $category_image = $request->category_image->store('assets/admin/uploads/category');
                    
                //     $category_image = explode('/',$category_image);
                //     $category_image = end($category_image);
                //     $upd['category_image'] = $category_image;
                // }

                $add = Category::where('category_id',$request->category_id)->update($upd);

                return redirect()->back()->with('success','Category Updated Successfully');
            }
        }
    }

    public function delete_category($id)
    {
        $delete_category  = Category::find($id);
        $delete_category->delete();
        return redirect()->route('admin.items')->with('success','Delete Category Successfully!');
    }



    public function get_category_details(Request $request)
    {
        $data['category'] = Category::where('category_id',$request->category_id)->first();

        $data['set'] = 'category';
        return view('admin.category.edit_category',$data);
    }

    public function category_status(Request $request)
    {
        $id = $request->segment(3);
        $status = $request->segment(4);

        if($status == 1)
        {
            $upd['category_status'] = 0;
        }
        else
        {
            $upd['category_status'] = 1;
        }

        $where['category_id'] = $id;

        $update = Category::where($where)->update($upd);

        if($update)
        {
            return redirect()->back()->with('success','Status Changed Successfully');
        }
    }
}