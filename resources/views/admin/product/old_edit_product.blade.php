@extends('admin.layouts.app')
@section('title',config('constants.site_title').' | Edit Product')
@section('contents')
<div class="content">
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Items</h4>
                <h6>Edit Items</h6>
            </div>
        </div>
        <ul class="table-top-head">
            <li>
                <div class="page-btn">
                    <a href="{{ route('admin.products') }}" class="btn btn-secondary"><i data-feather="arrow-left" class="me-2"></i>Back</a>
                </div>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i data-feather="chevron-up" class="feather-chevron-up"></i></a>
            </li>
        </ul>
    </div>
    <form id="product_form" action="{{ route('admin.edit_product',['id'=>Request()->segment(3)]) }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="new-employee-field">
                    <div class="card-title-head">
                        <h6><span><i data-feather="info" class="feather-edit"></i></span>Item Information</h6>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-6 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Category</label>
                                <select name="product_category" class="form-control selectbox">
                                    <option value="">Select</option>
                                    @foreach($category_list as $category)
                                    <option value="{{ $category->category_id }}" {{ $product->product_category == $category->category_id ? 'selected':'' }}>{{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                                <div id="product_category_error"></div>
                                @if($errors->has('product_category'))
                                <small class="text-danger">{{ $errors->first('product_category') }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Product Name</label>
                                <input type="text" name="product_name" class="form-control" value="{{ $product->product_name }}" autocomplete="off">
                                @if($errors->has('product_name'))
                                <small class="text-danger">{{ $errors->first('product_name') }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Barcode</label>
                                <input type="text" name="product_code" class="form-control" value="{{ $product->product_code }}" autocomplete="off">
                                @if($errors->has('product_code'))
                                <small class="text-danger">{{ $errors->first('product_code') }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Cost Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="product_cost_price" class="form-control decimals" value="{{ $product->product_cost_price }}" autocomplete="off">
                                    @if($errors->has('product_cost_price'))
                                    <small class="text-danger">{{ $errors->first('product_cost_price') }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Minimum Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="product_min_price" class="form-control decimals" value="{{ $product->product_min_price }}" autocomplete="off">
                                    @if($errors->has('product_min_price'))
                                    <small class="text-danger">{{ $errors->first('product_min_price') }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Retail Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="product_retail_price" class="form-control decimals" value="{{ $product->product_retail_price }}" autocomplete="off">
                                    @if($errors->has('product_retail_price'))
                                    <small class="text-danger">{{ $errors->first('product_retail_price') }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-6 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Image</label>
                                <div>
                                    @if(!empty($product->product_image))
                                    <img src="{{ asset(config('constants.admin_path').'uploads/product/'.$product->product_image) }}" class="img-thumbnail" id="product_image_src" style="height: 100px" alt="">
                                    @else
                                    <img src="{{ asset(config('constants.admin_path').'img/no_image.jpeg') }}" class="img-thumbnail" id="product_image_src" style="height: 100px" alt="">
                                    @endif
                                </div>
                                <input type="file" name="product_image" id="product_image" class="form-control">
                                @if($errors->has('product_image'))
                                <small class="text-danger">{{ $errors->first('product_image') }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Priority</label>
                                <select name="product_priority" id="product_priority" class="form-control selectbox">
                                    <option value="">Select</option>
                                    <option value="1" {{ $product->product_priority == 1 ? 'selected':'' }}>1 - Best Seller</option>
                                    <option value="2" {{ $product->product_priority == 2 ? 'selected':'' }}>2</option>
                                    <option value="3" {{ $product->product_priority == 3 ? 'selected':'' }}>3</option>
                                    <option value="4" {{ $product->product_priority == 4 ? 'selected':'' }}>4 - Worst Seller</option>
                                </select>
                                <div id="product_priority_error"></div>
                                @if($errors->has('product_priority'))
                                <small class="text-danger">{{ $errors->first('product_priority') }}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-end mb-3">
            <button type="submit" name="submit" class="btn btn-submit" value="submit">Save</button>
        </div>
    </form>
</div>
@endsection
@section('custom_script')
<script>
    $(document).ready(function() {
            // $('.decimals').on('input', function() {
            //     var value = $(this).val();
            //     if (value !== '') {
            //         var floatValue = parseFloat(value);
            //         if (!isNaN(floatValue)) {
            //             var fixedValue = floatValue.toFixed(2);
            //             // $(this).val(fixedValue.replace(/(\.\d*?[1-9])0*$/, '$1'));
            //         }
            //     }
            // });

            $('.decimals').on('input', function() {
                var value = $(this).val();
                if (value !== '') {
                    var floatValue = parseFloat(value);
                    if (!isNaN(floatValue)) {
                        $(this).blur(function () {
                            $(this).val(floatValue.toFixed(2));
                            // $(this).val(fixedValue.replace(/(\.\d*?[1-9])0*$/, '$1'));
                        })
                    }
                }
            });
        });
product_image.onchange = evt => {
  const [file] = product_image.files
  if (file) {
    product_image_src.src = URL.createObjectURL(file)
  }
}
</script>
@endsection