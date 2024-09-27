@extends('admin.layouts.app')
@section('title',config('constants.site_title').' | Create Sale')
@section('contents')
<div class="content pos-design p-0">    
    {{-- <div class="btn-row d-sm-flex align-items-center ">
        <a href="{{ route('admin.sales') }}" class="btn btn-secondary mb-xs-3 "><span class="me-1 d-flex align-items-center"><i data-feather="shopping-cart" class="feather-16"></i></span>View Sales</a>
        <a href="{{ route('admin.reset_sale',['id'=>Request()->segment(3)]) }}" class="btn btn-info "><span class="me-1 d-flex align-items-center"><i data-feather="rotate-cw" class="feather-16"></i></span>Reset</a>
        <a href="{{ route('admin.search_location') }}" class="btn btn-primary "><span class="me-1 d-flex align-items-center "><i data-feather="arrow-left" class="feather-16"></i></span>Back</a>
    </div> --}}
    <div class="row align-items-start pos-wrapper">
        <div class="col-md-12 col-lg-8">
            <div class="pos-categories tabs_wrapper">
                {{-- <h5 style="font-size: 15px;" >Categories</h5>
                <p>Select From Below Categories</p> --}}
                <ul class="tabs owl-carousel pos-category ">
                    <li id="all" class="active">
                        {{-- <a href="javascript:void(0);">
                            <img src="{{ asset(config('constants.admin_path').'img/categories/category-01.png') }}" alt="Categories">
                        </a> --}}
                        <h6><a href="javascript:void(0);">All Categories</a></h6>
                    </li>
                    @foreach($category_list as $category)
                    <li id="category_{{ $category->category_id }}">
                        {{-- <a href="javascript:void(0);">
                            @if(!empty($category->category_image))
                            <img src="{{ asset(config('constants.admin_path').'uploads/category/'.$category->category_image) }}" alt="Categories">
                            @else
                            <img src="{{ asset(config('constants.admin_path').'img/no_image.jpeg') }}" alt="Categories">
                            @endif
                        </a> --}}
                        <h6><a href="javascript:void(0);">{{ $category->category_name }}</a></h6>
                    </li>
                    @endforeach
                </ul>
                <div class="pos-products">
                    <div class="d-flex align-items-center justify-content-between">
                        {{-- <h5 class="mb-3">Products</h5> --}}
                    </div>
                    <div class="tabs_container">
                        <div class="tab_content active" data-tab="all">
                            <div class="row">
                          
                                @foreach($products as $product)
                                <div class=" col-sm-2 col-md-6 col-lg-3 col-xl-3 @if($loop->iteration != 1) pe-2 @endif" onclick="add_to_cart({{ $product->product_id }})" style="cursor: pointer">
                                    <div class="product-info default-cover card">
                                        <a href="javascript:void(0)" class="img-bg bg-white" >
                                            @if(!empty($product->product_image))
                                            <img src="{{ asset(config('constants.admin_path').'uploads/product/'.$product->product_image) }}" style="height: 80px" alt="Products">
                                            @else
                                            <img src="{{ asset(config('constants.admin_path').'img/no_image.jpeg') }}" style="height: 80px" alt="Products">
                                            @endif
                                        </a>
                                        {{-- <h6 class="cat-name"><a href="{{ route('admin.view_product',['id'=>$product->product_id]) }}">{{ $product->category_name }}</a></h6> --}}
                                        
                                        <div class="d-flex align-items-center justify-content-between price">

                                        <h6 class="product-name"><a onclick="add_to_cart({{ $product->product_id }})" >{{ $product->product_name }}</a></h6>
                                        <p data-bs-toggle="tooltip" data-bs-placement="bottom"  title="${{ $product->product_min_price }}" >${{ $product->product_retail_price }}</p>

                                        </div>

                                        {{-- <div class="d-flex align-items-center justify-content-between price">
                                            @if($product->stock_quantity == 0)
                                            <span class="text-danger">Out of Stock</span>
                                            @else
                                            <span>{{ $product->stock_quantity }} Pcs</span>
                                            @endif
                                            <p>${{ $product->product_retail_price }}</p>
                                        </div> --}}
                                        {{-- <div class="hstack gap-2 fs-15 mt-3 ">
                                            @if($product->stock_quantity != 0)
                                            <button type="button" class="btn btn-sm btn-primary" onclick="add_to_cart({{ $product->product_id }})" ><i class="fa fa-shopping-cart"></i></button>
                                            @else
                                            <button type="button" class="btn btn-sm btn-primary" disabled><i class="fa fa-shopping-cart"></i></button>
                                            @endif
                                        </div> --}}
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        
                        @foreach($category_list as $category)
                        <div class="tab_content" data-tab="category_{{ $category->category_id }}">
                            <div class="row">
                                @if(isset($category_products[$category->category_id]))                                
                                @foreach($category_products[$category->category_id] as $category_product)
                                <div class="col-sm-2 col-md-6 col-lg-3 col-xl-3 @if($loop->iteration != 1) pe-2 @endif" style="cursor: pointer" onclick="add_to_cart({{ $category_product['id'] }})">
                                    <div class="product-info default-cover card">
                                        <a href="javascript:void(0);" class="img-bg bg-white">
                                            @if(!empty($category_product['image']))
                                            <img src="{{ asset(config('constants.admin_path').'uploads/product/'.$category_product['image']) }}" style="height: 60px" alt="Products">
                                            @else
                                            <img src="{{ asset(config('constants.admin_path').'img/no_image.jpeg') }}" style="height: 60px" alt="Products">
                                            @endif
                                        </a>
                                        {{-- <h6 class="cat-name"><a href="{{ route('admin.view_product',['id'=>$category_product['id']]) }}">{{ $category_product['category'] }}</a></h6> --}}
                                        
                                        <div class="d-flex align-items-center justify-content-between price">

                                        <h6 class="product-name">
                                            <a href="{{ route('admin.view_product',['id'=>$category_product['id']]) }}" onclick="add_to_cart({{ $category_product['id'] }})" >{{ $category_product['name'] }}</a>
                                        </h6>
                                        <p >${{ $category_product['price'] }}</p>
                                        </div>
                                        {{-- <div class="d-flex align-items-center justify-content-between price">
                                            @if($category_product['stock_qty'] == 0)
                                            <span class="text-danger">Out of Stock</span>
                                            @else
                                            <span>{{ $category_product['stock_qty'] }} Pcs</span>
                                            @endif
                                            <p>${{ $category_product['price'] }}</p>
                                        </div>
                                        <div class="hstack gap-2 fs-15 mt-3">
                                            @if($category_product['stock_qty'] != 0)
                                            <button type="button" class="btn btn-sm btn-primary" onclick="add_to_cart({{ $category_product['id'] }})"><i class="fa fa-shopping-cart"></i></button>
                                            @else
                                            <button type="button" class="btn btn-sm btn-primary" disabled><i class="fa fa-shopping-cart"></i></button>
                                            @endif
                                        </div> --}}
                                    </div>
                                </div>
                                @endforeach
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-lg-4 ps-0">
            <form id="sale_form" action="{{ route('admin.add_sale') }}" method="post">
                @csrf
                <input type="hidden" name="sale_location" value="{{ Request()->segment(3) }}">
                <aside class="product-order-list">
                    @php 
                        $totalMinPrice = 0;
                        $totalPrice = 0;                        
                        foreach ($cart_items as $item) {
                            $totalMinPrice += $item->quantity * $item->attributes->min_price; // Assuming 'min_price' is stored in attributes
                            $totalPrice += $item->quantity * $item->price;
                        }
                        
                       $MinPrice =  $totalPrice - $totalMinPrice;                       
                       $FormatedMinPrice = number_format($MinPrice,2,".","")
                       
                    @endphp
                   
                    {{-- <div class="customer-info block-section">
                        <div class="input-block d-flex align-items-center">
                            <div class="flex-grow-1">
                                <select name="sale_customer" class="form-control selectbox_customer">
                                    <option value="">Customer</option>
                                    @foreach($customers as $customer)
                                    <option value="{{ $customer->customer_id }}">{{ $customer->customer_name }}</option>
                                    @endforeach
                                </select>
                            </div>                            
                        </div>
                        <div id="sale_customer_error"></div>
                        @if($errors->has('sale_customer'))
                        <small class="text-danger">{{ $errors->first('sale_customer') }}</small>
                        @endif
                        <div class="input-block d-flex align-items-center">
                            <div class="flex-grow-1">
                                <select name="sale_sellers[]" class="form-control selectbox_seller" multiple>
                                    <option value="">Sellers</option>
                                    @foreach($sellers as $seller)
                                    <option value="{{ $seller->user_id }}">{{ $seller->user_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div id="sale_sellers_error"></div>
                    </div> --}}

                    <div class="product-added block-section" id="cart_div">
                        <div class="head d-flex align-items-center justify-content-between w-100" style="margin: 20px 0 20px 0;">
                            <h5>Cart</h5>
                            <h6 id="min_total" class="mb-0" ></h6>  
                           
                                <h6 class="current_min_total mb-0" >{{$FormatedMinPrice}}</h6>    
                            
                            
                        </div>
                        <div class="head-text d-flex align-items-center justify-content-between">
                            <h6 class="d-flex align-items-center mb-0">Products Added<span class="count">{{ count($cart_items) }}</span></h6>
                            <a href="{{ route('admin.clear_cart') }}" class="clear-noti"> Clear All </a>
                        </div>
                        <div class="product-wrap ">
                            @if(count($cart_items))
                            @foreach($cart_items as $item)
                            <input type="hidden" name="products[{{ $item->id }}]" value="{{ $item->id }}">
                            <input type="hidden" name="product_price[{{ $item->id }}]" value="{{ $item->price }}">
                            <div class="product-list d-flex align-items-center justify-content-between" style="margin: 0 10px 10px 10px; border :1px solid #B8BCC9 ;">
                                <div class="d-flex align-items-center product-info">
                                    <a href="{{ route('admin.view_product',['id'=>$item->id]) }}" class="img-bg">
                                        @if($item->attributes->image)
                                        <img src="{{ asset(config('constants.admin_path').'uploads/product/'.$item->attributes->image) }}" alt="Products">
                                        @else
                                        <img src="{{ asset(config('constants.admin_path').'img/no_image.jpeg') }}" alt="Products">
                                        @endif
                                    </a>
                                    
                                    <div class="info" style="margin-top: -20px;">
                                        {{-- <span>{{ $item->attributes->code }}</span> --}}                                        
                                        
                                        {{-- <p>${{ $item->price }}</p> --}}
                                        <div class="name">
                                            <h6><a href="{{ route('admin.view_product',['id'=>$item->id]) }}">{{ $item->name }}</a></h6>
                                        </div>
                                        <div class="name2">
                                        <input type="hidden" name="product_price[{{ $item->id }}]" value="{{ $item->price }}">
                                                                               
                                        <div class="input-group">
                                            <span class="edit_price_symbol">$</span>
                                         
                                            <input type="text" class="form-control text-center edit_price"  data-product_id="{{$item->id}}" value="{{$item->price}}" >                                            
                                        </div>
                                        <input type="hidden" id="cprd_minprice_{{ $item->id }}" value="{{ $item->attributes->min_price }}">
                                        <input type="hidden" class="cart_items citem_{{ $item->id }}" id="cprd_price_{{ $item->id }}" value="{{ $item->price }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="qty-item text-center">
                                    <a href="javascript:void(0);" class="inc d-flex justify-content-center align-items-center"><i data-feather="plus-circle" class="feather-14" onclick="update_cart('',{{ $item->id }})"></i></a>
                                    <input type="text" class="form-control text-center" id="quantity_{{ $item->id }}" name="product_qty[{{ $item->id }}]" value="{{ $item->quantity }}" onkeyup="update_cart('',{{ $item->id }})">
                                    <a href="javascript:void(0);" class="dec d-flex justify-content-center align-items-center"><i data-feather="minus-circle" class="feather-14" onclick="update_cart('',{{ $item->id }})"></i></a>
                                </div>
                                <div class="d-flex align-items-center action">
                                    <a class="btn-icon edit-icon me-2" href="{{ route('admin.edit_product',['id'=>$item->id]) }}">
                                        <i data-feather="edit" class="feather-14"></i>
                                    </a>
                                    <a class="btn-icon delete-icon confirm-text" href="javascript:void(0)" onclick="remove_cart({{ $item->id }})">
                                        <i data-feather="trash-2" class="feather-14"></i>
                                    </a>
                                </div>
                            </div>
                            @endforeach
                            @endif
                        </div>
                    </div>


                    <div class="block-section">
                        <div class="selling-info">
                            <div class="row">
                                <div class="col-12 col-sm-12 mb-4 " id="discount_btn_div"> 
                                    <button type="button" name="Add_Discount" id="Add_Discount" class="btn btn-secondary"> Add Discount</button>
                                    <button type="button" name="hide_Discount" id="hide_Discount" class="btn btn-secondary"> Remove Discount</button>
                                </div>
                            </div>
                            <div class="row" id="discount_div">
                                
                                <div class="col-12 col-sm-6" >
                                    <div class="input-block">
                                        <label>Discount Type</label> 
                                        <select id="discount_type" name="sale_discount_type" class="select" onchange="handleDiscountTypeChange()">
                                            <option value="">Select</option>
                                            <option value="1" selected>$</option>
                                            <option value="2">%</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="input-block">
                                        <label>Discount</label>
                                        <input type="number" id="discount" value="" name="sale_discount" class="form-control" placeholder="Enter Discount" onkeyup="handleDiscountKeyup()">
                                    </div>
                                </div>
                            </div>
                        </div>                       
                        <div class="order-total">
                            <table class="table table-responsive table-borderless">
                                <tr id="discount_row">
                                    <td class="danger">Discount</td>
                                    <td class="danger text-end">$<span id="discount_text">0</span></td>
                                    <input type="hidden" name="sale_discount_amount" id="sale_discount_amount" value="0">
                                </tr>
                                <tr>
                                    <td>Subtotal</td>
                                    <td class="text-end">$<span id="sub_total_text">{{ Cart::getSubTotal() }}</span></td>
                                    <input type="hidden" name="sale_sub_total" id="sale_sub_total" value="{{ Cart::getSubTotal() }}">
                                </tr>                                
                                
                                @php $tax_amount = (Cart::getSubTotal()/100)*$location->location_tax; @endphp
                                <tr>
                                    <td>Tax ({{ number_format($location->location_tax, 2) }}%)</td>
                                    <td class="text-end">$<span id="tax_text"> {{ number_format($tax_amount, 2) }} </span></td>
                                    <input type="hidden" name="sale_tax" id="sale_tax" value="{{ $location->location_tax }}">
                                    <input type="hidden" name="sale_tax_amount" id="sale_tax_amount" value="{{ $tax_amount }}">
                                </tr>                                                               
                                @php $total = Cart::getSubTotal() + $tax_amount; @endphp
                                <tr>
                                    <td>Total</td>
                                    <td class="text-end">$<span id="total_text">{{ number_format($total, 2) }}</span></td>
                                    <input type="hidden" name="sale_grand_total" id="sale_grand_total" value="{{ $total }}">
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Select Payment modal -->

                    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="paymentModalLabel">Payment Method</h5>
                                {{-- <h6>Payment Method</h6> --}}
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="block-section payment-method">
                                    {{-- <h6>Payment Method</h6> --}}
                                    <input type="hidden" name="method_cash" id="method_cash" value="0">
                                    <input type="hidden" name="method_credit" id="method_credit" value="0">
                                    <input type="hidden" name="method_debit" id="method_debit" value="0">
                                    <div class="row d-flex align-items-center justify-content-center methods">
                                        <div class="col-md-6 col-lg-4 item">
                                            <div class="default-cover">
                                                <a id="cash_link" href="javascript:void(0)" onclick="select_cash()">
                                                    <img src="{{ asset(config('constants.admin_path').'img/icons/cash-pay.svg') }}" alt="Payment Method">
                                                    <span>Cash</span>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-4 item">
                                            <div class="default-cover">
                                                <a id="credit_link" href="javascript:void(0)" onclick="select_credit()">
                                                    <img src="{{ asset(config('constants.admin_path').'img/icons/credit-card.svg') }}" alt="Payment Method">
                                                    <span>Credit Card</span>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-4 item">
                                            <div class="default-cover">
                                                <a id="debit_link" href="javascript:void(0)" onclick="select_debit()">
                                                    <img src="{{ asset(config('constants.admin_path').'img/icons/credit-card.svg') }}" alt="Payment Method">
                                                    <span>Debit Card</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div id="cash_div" class="col-12 col-sm-12" style="display: none">
                                            <div class="input-block">
                                                <input type="number" id="pcash" name="pcash" class="form-control" placeholder="Enter Cash" autocomplete="off">
                                            </div>
                                        </div>
                                        <div id="credit_div" class="col-12 col-sm-12 mt-3" style="display: none">
                                            <div class="input-block">
                                                <input type="text" id="pcredit" name="pcredit" class="form-control" placeholder="Enter Credit Card Transaction ID" autocomplete="off">
                                            </div>
                                        </div>
                                        <div id="debit_div" class="col-12 col-sm-12 mt-3" style="display: none">
                                            <div class="input-block">
                                                <input type="text" id="pdebit" name="pdebit" class="form-control" placeholder="Enter Debit Card Transaction ID" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal"> Save</button>
                            </div>
                            </div>
                        </div>
                    </div>

                    {{-- <button type="button" name="submit" class="btn btn-secondary" value="submit" data-bs-toggle="modal" data-bs-target="#paymentModal">
                        Checkout : $<span id="grand_total_text">{{ $total }}</span>
                    </button>   --}}

                    <div class="d-grid btn-block">
                        <div id="enable_btn">
                            <button type="button" name="submit" class="btn btn-secondary w-100" value="submit" data-bs-toggle="modal" data-bs-target="#paymentModal">
                                Checkout
                            </button>                        
                        </div>
                        <div id="disable_btn">
                            <button type="button" disabled class="btn btn-secondary w-100" >
                                Checkout
                            </button>  
                        </div>
                       
                    </div>

                </aside>
            </form>
        </div>
    </div>
</div>

@endsection
@section('custom_script')

<script>
  
// cart_qty() 

$(document).ready(function(){
    
    cart_qty();

    $("#discount_div").hide();
    $("#hide_Discount").hide();
    $("#discount_row").hide();

    $("#Add_Discount").click(function() {
        $("#discount_div").show();
        $("#Add_Discount").hide();
        $("#hide_Discount").show();
        $("#discount_row").show();
        sub_total_calculation();
        total_calculation();
    })

    $("#hide_Discount").click(function() {
        
        $("#discount_div").hide();
        $("#Add_Discount").show();
        $("#hide_Discount").hide();
        $("#discount_row").hide();
        $("#discount").val(null)
        $("#discount_text").text(0);
        sub_total_calculation();
        total_calculation();
    })

});

function edit_cart_price(edit_price,id) {

    $("#cprd_price_"+id).val(edit_price);   
    total_calculation();
    sub_total_calculation();

}


function cart_qty() {

    var count = $('.product-list').length;

    if (count > 0) {
        $('#enable_btn').show();
        $('#disable_btn').hide();                
        $('.selling-info').show();
       
    } 
    else {
        $('#disable_btn').show();
        $('#enable_btn').hide();
        $('.selling-info').hide();
        $('#discount_row').hide();
        $("#discount").val(null)
        $("#discount_text").text(0);

        $("#discount_div").hide();
        $("#Add_Discount").show();
        $("#hide_Discount").hide();    

        sub_total_calculation();
        total_calculation();
    }
}

function sub_total_calculation()
{        
    total_price = 0;
    total_min_price = 0

    $('.cart_items').each(function(){

        var class_name = $("#"+$(this).attr('id')).attr('class').split(' ')[1];
        item_id = class_name.split("_");
        
        var price = $("#cprd_price_"+item_id[1]).val();
        var min_price = $("#cprd_minprice_"+item_id[1]).val();
        var quantity = $("#quantity_"+item_id[1]).val();

        total_price += parseFloat(price)*parseFloat(quantity);
        total_min_price += parseFloat(min_price)*parseFloat(quantity);

    });

    // Discount calculation
    var discount_type = $("#discount_type").val();
    var discount = 0;        

    if ($('#discount').val() != '') {
        discount = $('#discount').val();
    }

    if (discount_type == 1) {
        var discount_amount = parseFloat(discount);
        total_price = total_price - discount_amount;
    } else {
        var discount_amount = (total_price / 100) * parseFloat(discount);
        total_price = total_price - discount_amount;
    }

    $("#discount_text").text(discount_amount.toFixed(2));
    $("#sale_discount_amount").val(discount_amount.toFixed(2));


    $("#sub_total_text").text(total_price.toFixed(2));
    // $("#min_price").text(total_min_price.toFixed(2));
    $("#sale_sub_total").val(total_price.toFixed(2));
    // $("#min_price_total").val(total_min_price.toFixed(2));

    var tax_amount = (total_price/100)*{{ $location->location_tax }};

    $("#tax_text").text(tax_amount.toFixed(2));
    $("#sale_tax_amount").val(tax_amount.toFixed(2));

    var sub_total = $("#sub_total_text").text();  
    var min_total = parseFloat(sub_total)-parseFloat(total_min_price);
    console.log(min_total);
    console.log(sub_total);
    var f_min_total = min_total.toFixed(2);
    
        $("#min_total").text(f_min_total);    
    
    $('.current_min_total').remove();
    total_calculation();
}


function total_calculation()
{
    var sub_total = $("#sub_total_text").text();    
    var tax = $("#tax_text").text();   
    
    // var discount_type = $("#discount_type").val();

    // var discount = 0;

    // if($('#discount').val() != '')
    // {
    //   var discount = $('#discount').val();
    
    // }

    // if(discount_type == 1)
    // {
    //     var discount_amount = parseFloat(discount);
    //     var total = parseFloat(sub_total)-parseFloat(discount);
    // }
    // else
    // {
    //     var discount_amount = (parseFloat(sub_total)/100)*(parseFloat(discount));
    //     var total = parseFloat(sub_total)-parseFloat(discount_amount);
    // }
    
    // $("#discount_text").text(discount_amount.toFixed(2));
    // $("#sale_discount_amount").val(discount_amount.toFixed(2));
    

    // Total Price Calculation
    // total_price = 0;        

    // $('.cart_items').each(function(){

    //     var class_name = $("#"+$(this).attr('id')).attr('class').split(' ')[1];
    //     item_id = class_name.split("_");
        
    //     var price = $("#cprd_price_"+item_id[1]).val();
    //     var quantity = $("#quantity_"+item_id[1]).val();

    //     total_price += parseFloat(price)*parseFloat(quantity);

    // });
    // var tax_amount = (total_price/100)*{{ $location->location_tax }};
    
    // $("#tax_text").text(tax_amount.toFixed(2));
    // $("#sale_tax_amount").val(tax_amount.toFixed(2));

    
    var grand_total = parseFloat(sub_total)+parseFloat(tax);

    grand_total = grand_total.toFixed(2);

    $("#total_text").text(grand_total);
    $("#grand_total_text").text(grand_total);

    $("#sale_grand_total").val(grand_total);
}

function handleDiscountTypeChange() {
        total_calculation();
        sub_total_calculation();
    }

function handleDiscountKeyup() {
    total_calculation();
    sub_total_calculation();
    
}

function add_to_cart(product_id)
{        

    var qty = 1;  
    // var min  
    var csrf = "{{ csrf_token() }}";

    $.ajax({
        url: "{{ route('admin.add_to_cart') }}",
        type: "post",
        data: "product_id="+product_id+"&qty="+qty+"&_token="+csrf,
        success: function (response) 
        {
            // alert(response)
            // Swal.fire({
            //     title: "Good job!",
            //     text: "Product Added Successfully",
            //     type: "success",
            //     confirmButtonClass: "btn btn-primary",
            //     buttonsStyling: !1,
            //     icon: "success"
            // });

            $("#cart_div").html(response);

            sub_total_calculation();
        },
        complete:function (params) {

            $(document).find('.edit_price').focus().select().css('user-select', 'all');
            cart_qty();
          

        }
    });
}

function update_cart(edit_price,product_id)
{

    // console.log(edit_price);
    // console.log(product_id);

    if ( edit_price != '' ) {
           
        setTimeout(function(){

            var qty = $("#quantity_"+product_id).val();

            var csrf = "{{ csrf_token() }}";

            $.ajax({
                url: "{{ route('admin.update_cart') }}",
                type: "post",
                data: "product_id="+product_id+"&edit_price="+edit_price+"&qty="+qty+"&_token="+csrf,
                success: function (response) 
                {
                    sub_total_calculation();
                }
            });

        }, 200); 
    }
    else{
        
    setTimeout(function(){

        var qty = $("#quantity_"+product_id).val();
        
        var csrf = "{{ csrf_token() }}";

        $.ajax({
            url: "{{ route('admin.update_cart') }}",
            type: "post",
            data: "product_id="+product_id+"&qty="+qty+"&_token="+csrf,
            success: function (response) 
            {
                sub_total_calculation();
            }
        });

    }, 200);
    }
    
}

$(document).on("keyup", ".edit_price", function () {
     
    let edit_price = $(this).val();
    let product_id = $(this).data('product_id');
    
    edit_cart_price(edit_price, product_id);
    update_cart(edit_price, product_id);
});



function remove_cart(product_id)
{
    var csrf = "{{ csrf_token() }}";

    $.ajax({
        url: "{{ route('admin.remove_cart') }}",
        type: "post",
        data: "product_id="+product_id+"&_token="+csrf,
        success: function (response) 
        {
            // Swal.fire({
            //     title: "Good job!",
            //     text: "Product Removed Successfully",
            //     type: "success",
            //     confirmButtonClass: "btn btn-primary",
            //     buttonsStyling: !1,
            //     icon: "success"
            // });

            $("#cart_div").html(response);

            sub_total_calculation();
            cart_qty();           
        }
    });
}  

c = 1;

function select_cash()
{
    if(c%2==0)
    {
        $("#cash_link").css('background-color','white');
        $("#cash_div").hide();
        $("#method_cash").val(0);
    }
    else
    {
        $("#cash_link").css('background-color','antiquewhite');
        $("#cash_div").show();
        $("#method_cash").val(1);
    }

    c++;
}

cr = 1;

function select_credit()
{
    if(cr%2==0)
    {
        $("#credit_link").css('background-color','white');
        $("#credit_div").hide();
        $("#method_credit").val(0);
    }
    else
    {
        $("#credit_link").css('background-color','antiquewhite');
        $("#credit_div").show();
        $("#method_credit").val(1);
    }

    cr++;
}

d = 1;

function select_debit()
{
    if(d%2==0)
    {
        $("#debit_link").css('background-color','white');
        $("#debit_div").hide();
        $("#method_debit").val(0);
    }
    else
    {
        $("#debit_link").css('background-color','antiquewhite');
        $("#debit_div").show();
        $("#method_debit").val(1);
    }

    d++;
}

$(".selectbox_customer").select2({
	placeholder: "Customer"
});

$(".selectbox_seller").select2({
	placeholder: "Sellers"
});
</script>
@endsection