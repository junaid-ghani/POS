@extends('admin.layouts.app')
@section('title',config('constants.site_title').' | View Product')
@section('contents')
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Product Details</h4>
            <h6>Full details of a product</h6>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="productdetails">
                        <ul class="product-bar">
                            <li>
                                <h4>Product</h4>
                                <h6>{{ $product->product_name }}</h6>
                            </li>
                            <li>
                                <h4>Category</h4>
                                <h6>{{ $product->category_name }}</h6>
                            </li>
                            <li>
                                <h4>Code</h4>
                                <h6>{{ $product->product_code }}</h6>
                            </li>
                            <li>
                                <h4>Cost Price</h4>
                                <h6>{{ $product->product_cost_price }}</h6>
                            </li>
                            <li>
                                <h4>Minimum Price</h4>
                                <h6>{{ $product->product_min_price }}</h6>
                            </li>
                            <li>
                                <h4>Retail Price</h4>
                                <h6>{{ $product->product_retail_price }}</h6>
                            </li>
                            <li>
                                <h4>Priority</h4>
                                <h6>
                                @if($product->product_priority == 1)
                                1 - Best Seller
                                @elseif($product->product_priority == 2)
                                2
                                @elseif($product->product_priority == 3)
                                3
                                @elseif($product->product_priority == 4)
                                4 - Worst Seller
                                @else
                                -
                                @endif
                                </h6>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="slider-product-details">
                        <div class="owl-carousel owl-theme product-slide">
                            <div class="slider-product">
                                @if(!empty($product->product_image))
                                <img src="{{ asset(config('constants.admin_path').'uploads/product/'.$product->product_image) }}" alt="img">
                                @else
                                <img src="{{ asset(config('constants.admin_path').'img/no_image.jpeg') }}" alt="img">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection