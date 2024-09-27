@extends('location.layouts.app')
@section('title', config('constants.site_title') . ' | Create Sale')
@section('contents')

    <div class="content pos-design p-0">
        <div class="row align-items-start pos-wrapper">
            <div class="col-md-12 col-lg-8">
                <div class="pos-categories tabs_wrapper">
                    <ul class="tabs owl-carousel pos-category ">
                        <li id="all" class="active">
                            <h6><a href="javascript:void(0);">All Categories</a></h6>
                        </li>
                        @foreach ($category_list as $category)
                            <li id="category_{{ $category->category_id }}">
                                <h6><a href="javascript:void(0);">{{ $category->category_name }}</a></h6>
                            </li>
                        @endforeach
                    </ul>
                    <div class="pos-products">
                        <div class="d-flex align-items-center justify-content-between">
                        </div>
                        <div class="tabs_container">
                            <div class="tab_content active" data-tab="all">
                                <div class="row">

                                    @foreach ($products as $product)
                                        <div class=" col-sm-2 col-md-6 col-lg-3 col-xl-3 @if ($loop->iteration != 1) pe-2 @endif"
                                            onclick="add_to_cart({{ $product->product_id }})" style="cursor: pointer">
                                            <div class="product-info default-cover card">
                                                <a href="javascript:void(0)" class="img-bg bg-white">
                                                    @if (!empty($product->product_image))
                                                        <img src="{{ asset(config('constants.admin_path') . 'uploads/product/' . $product->product_image) }}"
                                                            style="height: 80px" alt="Products">
                                                    @else
                                                        <img src="{{ asset(config('constants.admin_path') . 'img/no_image.jpeg') }}"
                                                            style="height: 80px" alt="Products">
                                                    @endif
                                                </a>
                                                <div class="d-flex align-items-center justify-content-between price">
                                                    <h6 class="product-name"><a
                                                            onclick="add_to_cart({{ $product->product_id }})">{{ $product->product_name }}</a>
                                                    </h6>
                                                    <p data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                        title="${{ $product->product_min_price }}">
                                                        ${{ $product->product_retail_price }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            @foreach ($category_list as $category)
                                <div class="tab_content" data-tab="category_{{ $category->category_id }}">
                                    <div class="row">
                                        @if (isset($category_products[$category->category_id]))
                                            @foreach ($category_products[$category->category_id] as $category_product)
                                                <div class="col-sm-2 col-md-6 col-lg-3 col-xl-3 @if ($loop->iteration != 1) pe-2 @endif"
                                                    style="cursor: pointer"
                                                    onclick="add_to_cart({{ $category_product['id'] }})">
                                                    <div class="product-info default-cover card">
                                                        <a href="javascript:void(0);" class="img-bg bg-white">
                                                            @if (!empty($category_product['image']))
                                                                <img src="{{ asset(config('constants.admin_path') . 'uploads/product/' . $category_product['image']) }}"
                                                                    style="height: 60px" alt="Products">
                                                            @else
                                                                <img src="{{ asset(config('constants.admin_path') . 'img/no_image.jpeg') }}"
                                                                    style="height: 60px" alt="Products">
                                                            @endif
                                                        </a>
                                                        <div
                                                            class="d-flex align-items-center justify-content-between price">
                                                            <h6 class="product-name">
                                                                <a href="{{ route('admin.view_product', ['id' => $category_product['id']]) }}"
                                                                    onclick="add_to_cart({{ $category_product['id'] }})">{{ $category_product['name'] }}</a>
                                                            </h6>
                                                            <p>${{ $category_product['price'] }}</p>
                                                        </div>
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
                <form id="sale_form" action="{{ route('location.add_sale') }}" method="post" novalidate>
                    @csrf
                    <input type="hidden" id="timezone" name="timezone">
                    <input type="hidden" id="location_id" name="sale_location" value="{{ Request()->segment(3) }}">
                    <aside class="product-order-list">
                        @php
                            $totalMinPrice = 0;
                            $totalPrice = 0;
                            foreach ($cart_items as $item) {
                                $totalMinPrice += $item->quantity * $item->attributes->min_price; // Assuming 'min_price' is stored in attributes
                                $totalPrice += $item->quantity * $item->price;
                            }
                            $MinPrice = $totalPrice - $totalMinPrice;
                            $FormatedMinPrice = number_format($MinPrice, 2, '.', '');
                        @endphp
                        <div class="product-added block-section" id="cart_div">
                            <div class="head d-flex align-items-center justify-content-between w-100"
                                style="margin: 20px 0 20px 0;">
                                <h5>Cart</h5>
                                <h6 id="min_total" class="mb-0"></h6>
                                <h6 class="current_min_total mb-0">{{ $FormatedMinPrice }}</h6>
                            </div>
                            <div class="head-text d-flex align-items-center justify-content-between">
                                <h6 class="d-flex align-items-center mb-0">Products Added<span
                                        class="count">{{ count($cart_items) }}</span></h6>
                                <a href="{{ route('location.clear_cart') }}" id="clear_cart" class="clear-noti"> Clear All
                                </a>
                            </div>
                            <div class="product-wrap ">
                                @if (count($cart_items))
                                    @foreach ($cart_items as $item)
                                        <input type="hidden" name="products[{{ $item->attributes->product_id }}]"
                                            value="{{ $item->products }}">
                                        <input type="hidden" name="product_price[{{ $item->attributes->product_id }}]"
                                            value="{{ $item->price }}">
                                        <div class="product-list d-flex align-items-center justify-content-between"
                                            style="margin: 0 10px 10px 10px; border :1px solid #B8BCC9 ;">
                                            <div class="d-flex align-items-center product-info">
                                                <a href="javascript:void(0)" class="img-bg" style="cursor: auto;">
                                                    @if ($item->attributes->image)
                                                        <img src="{{ asset(config('constants.admin_path') . 'uploads/product/' . $item->attributes->image) }}"
                                                            alt="Products">
                                                    @else
                                                        <img src="{{ asset(config('constants.admin_path') . 'img/no_image.jpeg') }}"
                                                            alt="Products">
                                                    @endif
                                                </a>
                                                <div class="info" style="margin-top: -20px;">
                                                    <div class="name">
                                                        <h6><a href="javascript:void(0)"
                                                                style="cursor: default;">{{ $item->name }}</a></h6>
                                                    </div>
                                                    <div class="name2">
                                                        <input type="hidden"
                                                            name="product_price[{{ $item->attributes->product_id }}]"
                                                            value="{{ $item->price }}">

                                                        <div class="input-group">
                                                            <span class="edit_price_symbol">$</span>
                                                            <input type="text"
                                                                class="form-control text-center edit_price"
                                                                @if ($item->is_readonly == 1) disabled @endif
                                                                data-product_id="{{ $item->id }}"
                                                                value="{{ $item->price }}">
                                                        </div>
                                                        <input type="hidden" id="cprd_minprice_{{ $item->id }}"
                                                            value="{{ $item->attributes->min_price }}">
                                                        <input type="hidden"
                                                            class="cart_items citem_{{ $item->id }}"
                                                            id="cprd_price_{{ $item->id }}"
                                                            value="{{ $item->price }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="qty-item text-center">
                                                <a href="javascript:void(0);"
                                                    class="inc d-flex justify-content-center align-items-center"
                                                    @if ($item->is_readonly == 1) style="pointer-events: none; opacity: 0.5;" @endif><i
                                                        data-feather="plus-circle" class="feather-14"
                                                        onclick="update_cart('','{{ $item->id }}')"></i></a>
                                                <input type="text" class="form-control text-center"
                                                    id="quantity_{{ $item->id }}"
                                                    name="product_qty[{{ $item->attributes->product_id }}]"
                                                    value="{{ $item->quantity }}"
                                                    @if ($item->is_readonly == 1) disabled @endif
                                                    onkeyup="update_cart('','{{ $item->id }}')">
                                                <a href="javascript:void(0);"
                                                    class="dec d-flex justify-content-center align-items-center"
                                                    @if ($item->is_readonly == 1) style="pointer-events: none; opacity: 0.5;" @endif><i
                                                        data-feather="minus-circle" class="feather-14"
                                                        onclick="update_cart('','{{ $item->id }}')"></i></a>
                                            </div>
                                            <div class="d-flex align-items-center action">
                                                <a class="btn-icon edit-icon me-2 exchange_product"
                                                    href="javascript:void(0)" data-p_id="{{ $item->id }}"
                                                    data-bs-toggle="modal" data-bs-target="#ExchangeModal">
                                                    <i class="fa-solid fa-arrow-right-arrow-left"></i>
                                                </a>
                                                <a class="btn-icon delete-icon confirm-text" href="javascript:void(0)"
                                                    onclick="remove_cart('{{ $item->id }}')">
                                                    <i data-feather="trash-2" class="feather-14"></i>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <!-- Exchange modal -->

                        <div class="modal fade" id="ExchangeModal" tabindex="-1" aria-labelledby="ExchangeModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="paymentModalLabel">Return Product</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="d-flex ExchangeModalButton">
                                            <input type="hidden" class="current_pro_id" value="">
                                            <button type="button" class="btn btn-primary used_product">Used</button>
                                            <button type="button" class="btn btn-primary new_product">New</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Exchange modal -->

                        <div class="block-section">
                            <div class="selling-info">
                                <div class="row">
                                    <div class="col-12 col-sm-12 mb-4 " id="discount_btn_div">
                                        <button type="button" name="Add_Discount" id="Add_Discount"
                                            class="btn btn-secondary"> Add Discount</button>
                                        <button type="button" name="hide_Discount" id="hide_Discount"
                                            class="btn btn-secondary"> Remove Discount</button>
                                    </div>
                                </div>
                                <div class="row" id="discount_div">

                                    <div class="col-12 col-sm-6">
                                        <div class="input-block">
                                            <label>Discount Type</label>
                                            <select id="discount_type" name="sale_discount_type" class="select"
                                                onchange="handleDiscountTypeChange()">
                                                <option value="">Select</option>
                                                <option value="1" selected>$</option>
                                                <option value="2">%</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="input-block">
                                            <label>Discount</label>
                                            <input type="number" id="discount" value="" name="sale_discount"
                                                class="form-control" placeholder="Enter Discount"
                                                onkeyup="handleDiscountKeyup()">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="order-total">
                                <table class="table table-responsive table-borderless">
                                    <tr id="discount_row">
                                        <td class="danger">Discount</td>
                                        <td class="danger text-end">$<span id="discount_text">0</span></td>
                                        <input type="hidden" name="sale_discount_amount" id="sale_discount_amount"
                                            value="0">
                                    </tr>
                                    <tr>
                                        <td>Subtotal</td>
                                        <td class="text-end">$<span id="sub_total_text">{{ Cart::getSubTotal() }}</span>
                                        </td>
                                        <input type="hidden" name="sale_sub_total" id="sale_sub_total"
                                            value="{{ Cart::getSubTotal() }}">
                                    </tr>

                                    @php $tax_amount = (Cart::getSubTotal()/100)*$location->location_tax; @endphp
                                    <tr>
                                        <td>Tax ({{ number_format($location->location_tax, 2) }}%)</td>
                                        <td class="text-end">$<span id="tax_text"> {{ number_format($tax_amount, 2) }}
                                            </span></td>
                                        <input type="hidden" name="sale_tax" id="sale_tax"
                                            value="{{ $location->location_tax }}">
                                        <input type="hidden" name="sale_tax_amount" id="sale_tax_amount"
                                            value="{{ $tax_amount }}">
                                    </tr>
                                    @php $total = Cart::getSubTotal() + $tax_amount; @endphp
                                    <tr>
                                        <td>Total</td>
                                        <td class="text-end">$<span id="total_text">{{ number_format($total, 2) }}</span>
                                        </td>
                                        <input type="hidden" name="sale_grand_total" id="sale_grand_total"
                                            value="{{ $total }}">
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Check User Pin modal -->
                        <div class="modal fade" id="checkUserPinModal" data-bs-backdrop="static"
                            data-bs-keyboard="false" tabindex="-1" aria-labelledby="checkUserLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="checkUserLabel">Sale Cannot be Completed</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="alert alert-danger" style="display:none" id="userPinMessage"></div>
                                        <div id="user_verify_pin">

                                            <div class="form-group">
                                                <label>Enter PIN *</label>
                                                <input type="number" name="user_pin" id="check_user_pin"
                                                    class="form-control" required>
                                            </div>
                                            <div class="d-flex justify-content-end mt-3">
                                                <button type="button" class="btn btn-primary"
                                                    id="verifyPinBtn">Submit</button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Check User Pin modal -->


                        <!-- Request Sale Reject modal -->
                        <div class="modal fade" id="Sale_Reject" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="checkUserLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="checkUserLabel">Sale Rejected</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                                <div class="alert alert-warning">Sale is not approved </div>
                                        </div>
                                        {{-- <div class="d-flex justify-content-end mt-3">
                                            <button type="button" class="btn btn-primary btn-close" data-bs-dismiss="modal" id="verifyPinBtn">Close</button>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!-- Request Sale Reject End modal -->

                        <!-- Checkout modal -->
                        <div class="modal fade" id="paymentModal" data-bs-backdrop="static">
                            <div class="modal-dialog" style="max-width: 830px ">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" style="margin: auto 350px">Checkout</h5>
                                        <button type="button" class="btn-close close_payment_model"
                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">

                                        <h3 class="text-center">Total Due: $<span
                                                id="popUp_total">{{ number_format($total, 2) }}</span> </h3>

                                        <h5 class="text-center mt-2" id="return_change_amount_div"
                                            style="color: red; display: none;">Change: $<span
                                                id="return_change_amount"></span> </h5>
                                                <input type="hidden" id="req_change_amount" value="" name="change_amount">

                                        <div class="customer-info block-section d-flex justify-content-evenly"
                                            style="margin-bottom: 0; padding-bottom: 0;">

                                            <div class="input-block d-flex " style="width: 45%">
                                                <div class="flex-grow-1">

                                                    <details class="multiple-select">
                                                        <summary>Salesperson</summary>
                                                        <div class="multiple-select-dropdown">
                                                            <div class="custom_search">
                                                                <input type="text" class="custom_search_field"
                                                                    placeholder="Search Salesperson">
                                                            </div>
                                                            @foreach ($sellers as $seller)
                                                                <label>
                                                                    <input type="checkbox" class="saller_value" hidden
                                                                        name="select" value="{{ $seller->user_id }}">
                                                                    <span class="content"
                                                                        data-seller="{{ $seller->user_name }}">{{ $seller->user_name }}</span>
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                    </details>
                                                    <div id="selected-values" class="selected-values"></div>
                                                    <small id="seller_error" class="text-danger" style="display: none;">Please Select Seller</small>

                                                    <input type="hidden" name="sale_sellers" id="selectedValues">
                                                    <input type="hidden" name="commission" value="" id="selectedCommission">
                                                    <input type="hidden" name="amount" value="" id="selectedAmount">

                                                </div>
                                            </div>

                                            <div class="input-block d-flex " style="width: 45%">
                                                <div class="flex-grow-1">
                                                    <select name="sale_customer" class="form-control selectbox_customer_v selectbox_customer"> </select>
                                                </div>
                                                @if ($errors->has('sale_customer'))
                                                    <small class="text-danger">{{ $errors->first('sale_customer') }}</small>
                                                @endif
                                                <div id="sale_customer_error"></div>
                                                <button type="button" id="Add_New_Customer" class="btn btn-primary" style="min-height:0; height: 40px;" >Add Customer</button>
                                            </div>
                                        </div>

                                        <div class="block-section payment-method">
                                            {{-- <h6>Payment Method</h6> --}}
                                            <input type="hidden" class="payment_type" name="method_cash"
                                                id="method_cash" value="0">
                                            <input type="hidden" class="payment_type" name="method_credit"
                                                id="method_credit" value="0">
                                            {{-- <input type="hidden" class="payment_type" name="method_debit"
                                                id="method_debit" value="0"> --}}
                                            <input type="hidden" class="payment_type" name="method_payment_app"
                                                id="method_payment_app" value="0">
                                            <input type="hidden" class="payment_type" name="method_check"
                                                id="method_check" value="0">
                                            <!--<input type="hidden" name="method_split_payment" id="method_split_payment" value="0">-->

                                            <div class="row d-flex align-items-center justify-content-between"
                                                style="margin: 10px">
                                                <div class="col-md-6 col-lg-4">
                                                    <h4>Payment Details</h4>
                                                </div>
                                                <div class="col-md-6 col-lg-4 d-flex justify-content-end">
                                                    <button type="button" id="split_payment_btn"
                                                        onclick="split_payment(this)" class="btn btn-primary ">Split
                                                        Payment</button>
                                                </div>
                                            </div>
                                            <div class="row d-flex align-items-center justify-content-center methods"
                                                style="margin: 10px">

                                                <div class="col-md-6 col-lg-3 item">
                                                    <div class="default-cover">
                                                        <a id="cash_link" href="javascript:void(0)"
                                                            data-toggle_active_value="method_cash" class="check_active"
                                                            onclick="select_cash()">
                                                            <img src="{{ asset(config('constants.admin_path') . 'img/icons/cash-pay.svg') }}"
                                                                alt="Cash">
                                                            <span>Cash</span>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-lg-3 item">
                                                    <div class="default-cover">
                                                        <a id="credit_link" href="javascript:void(0)"
                                                            data-toggle_active_value="method_credit" class="check_active"
                                                            onclick="select_credit()">
                                                            <img src="{{ asset(config('constants.admin_path') . 'img/icons/credit-card.svg') }}"
                                                                alt="Payment Method">
                                                            <span>Credit Card</span>
                                                        </a>
                                                    </div>
                                                </div>

                                                @if ($settings->allow_payment_app == 1)
                                                <div class="col-md-6 col-lg-3 item">
                                                    <div class="default-cover">
                                                        <a id="payment_link" href="javascript:void(0)"
                                                            data-toggle_active_value="method_payment_app"
                                                            class="check_active" onclick="select_payment_app()">
                                                            <img src="{{ asset(config('constants.admin_path') . 'img/icons/credit-card.svg') }}"
                                                                alt="Payment App">
                                                            <span>Payment App</span>
                                                        </a>
                                                    </div>
                                                </div>
                                                @endif
                                                @if ($settings->allow_check == 1)
                                                <div class="col-md-6 col-lg-3 item">
                                                    <div class="default-cover">
                                                        <a id="check_link" href="javascript:void(0)"
                                                            data-toggle_active_value="method_check" class="check_active"
                                                            onclick="select_check()">
                                                            <img src="{{ asset(config('constants.admin_path') . 'img/icons/credit-card.svg') }}"
                                                                alt="Check">
                                                            <span>Check</span>
                                                        </a>
                                                    </div>
                                                </div>    
                                                @endif
                                                

                                            </div>
                                            <div class="row mt-3" id="payment_method_div" style="margin: 4px">
                                                <div id="cash_div" class="col-12 col-sm-12 mt-3" style="display: none">
                                                    <label for="pcash">Cash Received:</label>
                                                    <div class="input-block mt-1">
                                                        <input type="hidden" value="" id="pcash_amount" name="pcash"> 
                                                        <input type="number" id="pcash" class="form-control decimals amount_in_cash cash_amount" placeholder="Enter Payment Amount" autocomplete="off">
                                                    </div>

                                                    <div id="cash_change_amount_div" style="display: none;">
                                                        <div class="input-block mt-3 return_amount">
                                                            {{-- <p> Change Amount: $<span id="return_amount_text">0.00</span>
                                                            </p> --}}
                                                            <small id="cash_changeAmount_error_message"
                                                                class="text-danger" style="display: none;">Payment Amount
                                                                Can not be Less than Total</small>
                                                        </div>
                                                    </div>

                                                    <div class="input-block mt-3">
                                                        <button type="button" id="cash_button"
                                                            class="btn btn-primary save_checkout"> OK</button>
                                                    </div>
                                                </div>
                                                <div id="credit_div" class="col-12 col-sm-12 mt-3" style="display: none">

                                                    <div class="input-block" style="display: flex; column-gap:25px;">
                                                        <input type="hidden" value="" id="pa_cradit_card_amount" name="pa_cradit_card"> 
                                                        <input type="number" id="pa_cradit_card" class="form-control amount_in_cash" value="{{ number_format($total, 2) }}">
                                                    </div>

                                                    <div class="input-block mt-3">

                                                        {{-- here (Transaction ID) is as 'Card Type'  --}}
                                                        <select id="pcredit" name="pcredit" class="form-control">
                                                            <option selected value="">Select Card Type</option>
                                                            <option value="Visa">Visa</option>
                                                            <option value="Mastercard">Mastercard</option>
                                                            <option value="Amex">Amex</option>
                                                            <option value="Discover">Discover</option>
                                                        </select>
                                                        <small id="credit_error_message" class="text-danger"
                                                            style="display: none;">Credit Card Type Field is
                                                            Required</small>
                                                    </div>
                                                    <div class="input-block mt-3">
                                                        <input type="number" id="last4" name="last4"
                                                            class="form-control" placeholder="Enter Last 4 Digits"
                                                            autocomplete="off">
                                                    </div>
                                                    <div class="input-block mt-3">
                                                        <button type="button" id="credit_button"
                                                            class="btn btn-primary save_checkout"> OK</button>
                                                    </div>
                                                </div>
                                                <div id="payment_div" class="col-12 col-sm-12 mt-3"
                                                    style="display: none">
                                                    <div class="input-block" style=" display: flex; column-gap:25px;">
                                                        <input type="hidden" value="" id="pa_payment_app_amount" name="pa_payment_app"> 
                                                        <input type="number" id="pa_payment_app"  class="form-control amount_in_cash" value="{{ number_format($total, 2) }}">
                                                    </div>

                                                    <div class="input-block mt-3">
                                                        {{-- <input type="text" id="payment_app" name="payment_app" class="form-control" placeholder="Enter Payment App" autocomplete="off"> --}}
                                                        {{-- <label style="margin: 3px;">Select Payment App</label> --}}
                                                        <select class="form-control" id="payment_app" name="payment_app">
                                                            <option value="" disabled selected>Select Payment App
                                                            </option>
                                                            @if($settings->allow_zella == 1)
                                                                <option value="Zelle">Zelle</option>
                                                            @endif
                                                            @if($settings->allow_venmo == 1)
                                                                <option value="Venmo">Venmo</option>
                                                            @endif
                                                            @if($settings->allow_cash_app == 1)
                                                                <option  value="Cash App">Cash App</option>
                                                            @endif 
                                                            @if($settings->allow_paypal == 1) 
                                                                <option  value="PayPal">PayPal</option>
                                                            @endif
                                                        </select>
                                                        <small id="payment_error_message" class="text-danger"
                                                            style="display: none;">Payment Field is Required</small>
                                                    </div>
                                                    <div class="input-block mt-3">
                                                        <button type="button" id="payment_button"
                                                            class="btn btn-primary save_checkout"> OK</button>
                                                    </div>
                                                </div>
                                                <div id="check_div" class="col-12 col-sm-12 mt-3" style="display: none">
                                                    <div class="input-block" style=" display: flex; column-gap:25px;">
                                                        <input type="hidden" value="" id="pa_check_amount" name="pa_check" > 
                                                        <input type="number" id="pa_check" class="form-control amount_in_cash" value="{{ number_format($total, 2) }}">
                                                    </div>

                                                    <div class="input-block mt-3"
                                                        style=" display: flex; column-gap:25px;">
                                                        <div class="w-100">
                                                            <input type="text" id="ck_name" name="ck_name"
                                                                class="form-control" placeholder="Name"
                                                                autocomplete="off">
                                                        </div>
                                                        <div class="w-100">
                                                            <input type="number" id="ck_no" name="ck_no"
                                                                class="form-control" placeholder="Check#"
                                                                autocomplete="off">
                                                            <small id="check_error_message" class="text-danger"
                                                                style="display: none;">Check Field is Required</small>
                                                        </div>
                                                    </div>

                                                    <div class="input-block mt-3">
                                                        <button type="button" id="check_button"
                                                            class="btn btn-primary save_checkout"> OK</button>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-sm-12 mt-3" id="change_amount_div">
                                                    <div class="input-block mt-3 ">
                                                        <small id="changeAmount_error_message" class="text-danger"
                                                            style="display: none;">Payment Amount Should be Equal to
                                                            Total</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row d-flex align-items-center justify-content-center "
                                                style="margin: 10px">
                                                <div class="col-12 col-sm-12 mt-3">

                                                    <table class="show_submitted_details_table"
                                                        id="show_submitted_details" style="display: none;">

                                                    </table>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="modal-footer" id="print_Btn_div"
                                        style="display: none; margin-top: -16px 16px 16px 16px;">

                                        <div class="col-12 col-sm-12 d-flex justify-content-between">

                                            <div class="input-block">
                                                <button type="submit" name="submit"
                                                    class="btn btn-primary submit_button">Print
                                                    Receipt</button>
                                            </div>
                                            <div class="input-block">
                                                <button  id="send_email_button" type="button" class="btn btn-primary">
                                                    Email
                                                    Receipt</button>
                                            </div>
                                            <div class="input-block">
                                                <button type="submit" name="submit"
                                                    class="btn btn-primary submit_button"> No
                                                    Receipt</button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Checkout modal -->

                        <!-- Send PrintReceipt Email Modal  -->
                        {{-- <div class="custom_modal" id="send_email">
                            <div class="custom_modal_content">
                                <div class="custom_modal_header mb-3 d-flex justify-content-between align-items-center">
                                    <div class="custom_modal_heading">
                                        <h5 class="modal-title">Add Email</h5>
                                    </div>
                                    <div class="custom_modal_close">
                                        <span class="custom_close" id="closeModal_send_email">&times;</span>
                                    </div>
                                </div>
                                <hr>
                                <div class="alert alert-danger" style="display:none" id="sendEmailError"></div>

                                <div class="mb-3">
                                    <label for="send_email" class="form-label">Email</label>
                                    <input type="text" class="form-control" id="email_text" name="send_email">
                                    <small id="email_error" class="text-danger"></small>
                                </div>
                                <button type="Submit" name="submit" value="email_receipt"
                                    class="btn btn-primary email_submit_button">Email Receipt</button>
                            </div>
                        </div> --}}

                        <div class="modal fade" id="send_email" aria-labelledby="sendEmailModalLabel" aria-hidden="true" data-bs-backdrop="static">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="sendEmailModalLabel">Add Email</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('location.SendReceiptCopy') }}" method="post">
                                            @csrf
                                            <div class="alert alert-danger" style="display:none" id="sendEmailError"></div>
                                            <div class="mb-3">
                                                <input type="hidden" id="sendEmail" name="sale_id" >
                
                                                <label for="send_email" class="form-label">Email</label>
                                                <input type="email" class="form-control" id="email_text" name="send_email">                                
                                                <small id="email_error" class="text-danger"></small>
                                            </div>
                                            <button type="submit" name="submit" value="email_receipt" class="btn btn-primary email_submit_button">Email Receipt</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- End PrintReceipt Email modal -->

                        <!-- Add New Customer Modal -->
                        <div class="modal fade custom_modal" id="Add_New_Customer_modal" aria-hidden="true" data-bs-backdrop="static">
                            <div class="modal-dialog custom_modal_content">
                                <div class="modal-content">
                                    <div class="modal-header custom_modal_header mb-3 d-flex justify-content-between align-items-center">
                                        <div class="custom_modal_heading">
                                            <h5 class="modal-title">Add Customer</h5>
                                        </div>
                                        <div class="custom_modal_close">
                                            {{-- <span class="custom_close" >&times;</span> --}}
                                            <button type="button" id="closeModal" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                                        </div>
                                    </div>                                   
                                    <div class="alert alert-danger" style="display:none" id="customerError"></div>
                                    <div class="modal-body">
                                        <form id="addNewCustomer">
                                            {{-- @csrf --}}
                                            <div class="mb-3">
                                                <label for="customerName" class="form-label">Customer Name</label>
                                                <input type="text" class="form-control" id="customerName"
                                                    name="customer_name">
                                            </div>
                                            <div class="mb-3">
                                                <label for="customerEmail" class="form-label">Email</label>
                                                <input type="email" class="form-control" id="customerEmail"
                                                    name="customer_email">
                                            </div>
                                            <div class="mb-3">
                                                <label for="customerPhone" class="form-label">Phone</label>
                                                <input type="text" class="form-control" id="customerPhone"
                                                    name="customer_phone">
                                            </div>
                                            <div class="mb-3">
                                                <label for="customerPhone" class="form-label">Salesperson</label>
                                                {{-- <select name="sale_sellers[]" class="form-control selectbox_seller" multiple> --}}
                                                <select class="form-control selectbox_seller " id="customer_seller" multiple>
                                                    {{-- <option>Search Salesperson</option> --}}
                                                    @foreach ($sellers as $seller)
                                                        <option value="{{ $seller->user_id }}">{{ $seller->user_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <button type="button" id="addCustomer" class="btn btn-primary">Add Customer</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Add New Customer Modal -->


                        <div class="d-grid btn-block">
                            <div id="enable_btn">
                                <a name="submit" class="btn btn-secondary w-100" value="submit" id="checkout_btn">
                                    Checkout
                                </a>
                            </div>
                            <div id="disable_btn">
                                <button type="button" disabled class="btn btn-secondary w-100">
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script>
        // ------------- Document Ready Function ------------- 

        
        var userTimeZone = Intl.DateTimeFormat().resolvedOptions().timeZone;
        document.getElementById('timezone').value = userTimeZone;
        // var currentDateTime = new Date().toLocaleString('en-US', { timeZone: userTimeZone });

        // console.log(currentDateTime);
        

        $(document).ready(function() {
           
            function updateQueryParam(param, value) {
                var url = new URL(window.location.href);
                url.searchParams.set(param, value);
                window.history.replaceState({}, '', url.toString());
            }
            updateQueryParam('cl', '1');

            $(window).keydown(function(event) {
                if (event.keyCode == 13) {

                    let storedId = localStorage.getItem('verifyButtonId');
                    let verifyPinBtn = $("#check_user_pin").attr('id');

                    if ($(event.target).is('#' + storedId) || $(event.target).is("#" + verifyPinBtn)) {

                        $('#verifyPinBtn').trigger('click');
                        return true;

                    } else {
                        event.preventDefault();
                        return false;
                    }
                }
            });

            get_customer()
            cart_qty();

            $("#discount_div").hide();
            $("#hide_Discount").hide();
            $("#discount_row").hide();

            $(document).on("click", ".exchange_product", function() {

                let product_id = $(this).data('p_id');
                $(".current_pro_id").val(product_id);

            })

            $(document).on("click", ".used_product", function() {

                let cp_id = $(".current_pro_id").val();
                used_update_cart('0', cp_id);
                $('#ExchangeModal').modal('hide');

            })

            $(document).on("click", ".new_product", function() {

                let cp_id = $(".current_pro_id").val();

                new_update_cart('0', cp_id);
                $('#ExchangeModal').modal('hide');

            })

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


            // --- show_condition_base_modal ---

            $("#checkout_btn").click(function() {

                let profitMargin = $("#min_total").text();

                if (profitMargin >= 0) {
                    $("#paymentModal").modal('show');
                } else {

                    $("#checkUserPinModal").modal('show');

                    setTimeout(() => {
                        $("#check_user_pin").focus();
                    }, 500);
                }

            });


            // Check User Pin Modal
            let myInterval;

            function emp_approve() {

                let request_approve_id = localStorage.getItem('emp_request_id');

                myInterval = setInterval(() => {
                    $.ajax({

                        url: "{{ route('location.get_approve_emp') }}",
                        type: "GET",
                        data: {
                            request_approve_id: request_approve_id
                        },
                        success: function(response) {

                            if (response.status == 'success') {
                                if (response.request_status == '1') {

                                    $("#checkUserPinModal").modal('hide');
                                    $("#paymentModal").modal('show');
                                    $("#seller_error").hide();
                                    clearInterval(myInterval);
                                }
                                else if (response.request_status == '2') {

                                    $("#checkUserPinModal").modal('hide');
                                    $("#Sale_Reject").modal('show');
                                    clearInterval(myInterval);
                                }
                            }
                        }
                    })
                }, 1000);
            }

            $('#verifyPinBtn').click(function(e) {
                e.preventDefault()
                let csrf = "{{ csrf_token() }}";
                let check_user_pin = $("#check_user_pin").val();
                // let location_id = $("#location_id").val();
                let timezone = $("#timezone").val();
                let discount = $("#discount_text").text();
                let sub_total = $("#sub_total_text").text();
                let tax = $("#tax_text").text();
                let total = $("#total_text").text();
                var loss_amount =  $("#min_total").text().replace('-', '');      

                $.ajax({
                    url: "{{ route('location.pin_authenticate') }}",
                    type: "POST",
                    data: {
                        _token: csrf,
                        check_user_pin: check_user_pin,
                        timezone: timezone,
                        discount:discount,
                        sub_total:sub_total,
                        tax:tax,
                        total: total,
                        loss_amount:loss_amount
                    },
                    beforeSend: function() {
                        $('#userPinMessage').text('').hide();
                        $('#verifyPinBtn').text('Submiting').attr('disabled', true);
                    },
                    success: function(response) {
                        if (response.status == 'error') {
                            $('#userPinMessage').text(response.message).show();
                        }
                        if (response.status == 'success2') {
                            
                            $("#user_verify_pin").hide();
                            $("#check_user_pin").val(null);
                            $('#userPinMessage').removeClass('alert alert-danger').addClass('alert alert-success');
                            $("#checkUserLabel").text('');
                            $('#userPinMessage').text(response.message).show();
                            localStorage.setItem('emp_request_id', response.emp_request_id);
                            emp_approve();
                        } else if (response.status == 'success') {
                           
                            $("#checkUserPinModal").modal('hide');
                            $("#paymentModal").modal('show');
                            
                        }
                    },
                    complete: function() {
                        $('#verifyPinBtn').text('Submit').attr('disabled', false);
                    }
                });
            });


            $("#checkUserPinModal").on('hide.bs.modal', function() {
                $('#userPinMessage').text('').hide();
                $("#check_user_pin").val(null);
                $("#user_verify_pin").show();
                $("#checkUserLabel").text('Sale Cannot be Completed');
                if (myInterval) {
                    clearInterval(myInterval);
                }
            });

            // checkout Modal

            // --- custom select box ---

            var multipleSelect = $('.multiple-select');
            var multipleSelectDropdown = $('.multiple-select-dropdown');
            var selectedValues = $('.selected-values');
            var hiddenInput = $('#selectedValues');


            $('.custom_search_field').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                var $parent = $(this).closest('.multiple-select-dropdown');
                var results = 0;

                $parent.find('label').each(function() {
                    var $span = $(this).find('span');
                    var text = $span.text().toLowerCase();
                    if (text.indexOf(value) !== -1) {
                        $(this).show();
                        results = 1;
                    } else {
                        $(this).hide();
                    }
                });

                if (results === 0) {
                    if (!$('.noresults', $parent).length) {
                        $parent.append(
                            '<div class="noresults" style="margin:5px" >No results found</div>'
                        );
                    }
                } else {
                    $('.noresults', $parent).remove();
                }
            });

            function updateHiddenInput() {
                var selectValues = [];
                $('input[name="select"]:checked').each(function() {
                    selectValues.push($(this).val());
                });
                hiddenInput.val(JSON.stringify(selectValues));
            }

            function createSelectedValueElement(value, seller_name, perc, sellerSubTotal) {

                return $('<div>', {
                        class: 'selected-value',
                        'data-value': value
                    })
                    .append($('<button>', {
                        class: 'remove-value'
                    }).append(' <i class="fa-solid fa-xmark fa-fw"></i> '))
                    .append($('<span>', {
                        class: 'seller-name'
                    }).text(seller_name))
                    .append($('<span>', {
                        class: 'percentage',
                        contenteditable: 'true'
                    }).text(` ${perc}% `).on('keydown', validateSellerInput).on('input',
                        validateSellerInputOnInput))
                    .append($('<span>', {
                        class: 'sub_total_CSS'
                    }).text('$' + sellerSubTotal))
            }

            function validateSellerInput(event) {
                const key = event.key;
                if (!/[\d.]/.test(key) && key !== 'Backspace' && key !== 'Delete' && key !== 'ArrowLeft' && key !==
                    'ArrowRight') {
                    event.preventDefault();
                }
            }

            function validateSellerInputOnInput(event) {
                const input = event.target.textContent;
                if (!/^\d*\.?\d*$/.test(input)) {
                    event.target.textContent = input.slice(0, -1);
                }
            }

            function updateSelectedValueElements() {

                var itemCount = $(".selected-value").length;
                var activeItemCount = $(".active-selected-value").length;

                var remainingItemCount = itemCount - activeItemCount;

                if (remainingItemCount > 0) {
                    var totalPercentage = 100;

                    $(".active-selected-value .percentage").each(function() {
                        let activeText = $(this).text().replace('%', '').trim();
                        totalPercentage -= parseFloat(activeText);
                    });
                    var newPercentage = (totalPercentage / remainingItemCount).toFixed(2);
                    var subTotal = $("#sub_total_text").text();
                    var newSellerSubTotal = (subTotal * (newPercentage / 100)).toFixed(2);

                    $(".selected-value").each(function() {
                        if (!$(this).hasClass('active-selected-value')) {
                            $(this).find('.percentage').text(`${newPercentage}%`);

                            var sellerSubTotal = (newPercentage / 100 * subTotal).toFixed(2);

                            $(this).find('.sub_total_CSS').text('$' + newSellerSubTotal);
                        }
                    });
                }
            }

            $('input[name="select"]').on('change', function() {
                var checkbox = $(this);

                if (checkbox.is(':checked')) {
                    var value = checkbox.val();
                    var seller_name = checkbox.siblings('.content').text();
                    var itemCount = $(".selected-value").length;
                    var perc = (100 / (itemCount + 1)).toFixed(2);

                    var subTotal = $("#sub_total_text").text();
                    // var sellerSubTotal = (subTotal / (itemCount + 1));
                    var sellerSubTotal = (subTotal * (perc / 100)).toFixed(2);

                    selectedValues.append(createSelectedValueElement(value, seller_name, perc,
                        sellerSubTotal));
                    checkbox.closest('label').hide();
                    multipleSelect.prop('open', false); // Hide the dropdown
                    updateSelectedValueElements();
                    
                    
                    // let sellerSelected = $(".selected-value");
                    // let sellerSelectedLength = sellerSelected.length - 1;                    
                    // let sellerAmount = 0;
                    // sellerSelected.each(function(index, el) {
                    //     if (sellerSelectedLength == index) {                            
                            
                    //         sellerAmount = subTotal - sellerAmount;
                    //         $(this).find('.sub_total_CSS').text('$'+sellerAmount.toFixed(2))                            

                    //     } else {
                    //         let sellertTextAmount = $(this).find('.sub_total_CSS').text();
                    //         sellertTextAmount = sellertTextAmount.replace('$', '');
                    //         sellerAmount += parseFloat(sellertTextAmount);
                            
                    //     }
                    // })
                }
                updateHiddenInput();
            });

            $(document).on('click', '.percentage', function() {

                var range = document.createRange();
                range.selectNodeContents(this);
                var selection = window.getSelection();
                selection.removeAllRanges();
                selection.addRange(range);
            })
            selectedValues.on('input', '.percentage', function() {

                setTimeout(() => {
                    var percentageElement = $(this);

                    var active_selected_value = $(this).parent().addClass('active-selected-value');

                    $(".selected-value").removeClass('selected_active_value');

                    var selected_active_value = $(this).parent().addClass('selected_active_value');

                    var value = percentageElement.closest('.selected-value').data('value');

                    var newPercentage = percentageElement.text().replace('%', '').trim();

                    if (newPercentage > 100 || newPercentage < 0) {

                        var error_total = 0;

                        $(".selected-value").each(function() {
                            if (!$(this).hasClass('selected_active_value')) {
                                error_total += Number($(this).find('.percentage').text().replace('%',
                                    '').trim());
                            }
                        });
                        var show_error_total = 100 - error_total;
                        percentageElement.text(`${show_error_total.toFixed(2)}%`);

                    } else {

                        var overAll_totalPercentage = 0;
                        $(".selected-value .percentage").each(function() {
                            var text = $(this).text().replace('%', '').trim();
                            overAll_totalPercentage += parseFloat(text);
                        });

                        if (overAll_totalPercentage > 100) {

                            // var show_error_total2 = 100 - newPercentage;
                            // var itemCount = $(".selected-value").length;
                            // var perc = (show_error_total2 / (itemCount - 1)).toFixed(2);
                            // $(".selected-value").each(function() {
                            //     if (!$(this).hasClass('selected_active_value')) {
                            //         $(this).find('.percentage').text(`${perc}%`);
                            //     }
                            //     // else{
                            //     //     let newText = parseFloat($(this).find('.percentage').text().replace('%', '').trim());

                            //     //     $(this).find('.percentage').text(`${newText.toFixed(2)}%`);
                            //     // }
                            // });

                            $('.selected-value').removeClass('active-selected-value');

                            // Phir specific element pe class apply karenge
                            $(this).parent('.selected-value').addClass('active-selected-value');
                            var show_error_total2 = 100 - newPercentage;
                            var itemCount = $(".selected-value").length;
                            var perc = (show_error_total2 / (itemCount - 1)).toFixed(2);
                            $(".selected-value").each(function() {
                                if (!$(this).hasClass('selected_active_value')) {
                                    $(this).find('.percentage').text(`${perc}%`);
                                } else {
                                    let newText = parseFloat($(this).find('.percentage').text().replace(
                                        '%', '').trim());
                                    $(this).find('.percentage').text(`${newText.toFixed(2)}%`);
                                }
                            });

                        }
                        else {
                                var totalItems = $(".selected-value").length;
                                if (newPercentage && !isNaN(newPercentage) && totalItems > 1) {
                                    var subTotal = parseFloat($("#sub_total_text").text());
                                
                                    var newSubTotal = (subTotal * (newPercentage / 100)).toFixed(2);
                                    percentageElement.closest('.selected-value').find('.sub_total_CSS').text('$' + newSubTotal);

                                    var newPercValue = parseFloat(newPercentage);
                                    percentageElement.text(`${newPercValue.toFixed(2)}%`);

                                    updateSelectedValueElements();

                                    // Calculate New Subtotals
                                    // let sellerSelected = $(".selected-value");
                                    // let sellerSelectedLength = sellerSelected.length - 1;
                                    // let sellerAmount = 0;

                                    // sellerSelected.each(function(index, el) {
                                    //     if (sellerSelectedLength === index) {
                                    //         // Last Seller Gets Remaining Amount
                                    //         sellerAmount = subTotal - sellerAmount;
                                    //         $(this).find('.sub_total_CSS').text('$' + sellerAmount.toFixed(2));
                                    //     } else {
                                    //         // Sum Amounts for Non-Last Sellers
                                    //         let sellertTextAmount = $(this).find('.sub_total_CSS').text().replace('$', '');
                                    //         sellerAmount += parseFloat(sellertTextAmount);
                                    //     }
                                    // });
                                }
                            }                                 
                    }
                }, 1000);

            });

            multipleSelect.on('toggle', function() {
                if (multipleSelect.prop('open')) {
                    multipleSelectDropdown.show();
                } else {
                    multipleSelectDropdown.hide();
                }
            });

            selectedValues.on('click', '.remove-value', function() {
                var selectedValue = $(this).closest('.selected-value');
                var value = selectedValue.data('value');
                selectedValue.remove();
                $(`input[name="select"][value="${value}"]`).prop('checked', false).closest('label').show();
                updateHiddenInput();
                updateSelectedValueElements();

                 // Calculate New Subtotals
                // var subTotal = parseFloat($("#sub_total_text").text());
                // let sellerSelected = $(".selected-value");
                // let sellerSelectedLength = sellerSelected.length - 1;
                // let sellerAmount = 0;

                // sellerSelected.each(function(index, el) {
                //     if (sellerSelectedLength === index) {
                //         // Last Seller Gets Remaining Amount
                //         sellerAmount = subTotal - sellerAmount;
                //         $(this).find('.sub_total_CSS').text('$' + sellerAmount.toFixed(2));
                //     } else {
                //         // Sum Amounts for Non-Last Sellers
                //         let sellertTextAmount = $(this).find('.sub_total_CSS').text().replace('$', '');
                //         sellerAmount += parseFloat(sellertTextAmount);
                //     }
                // });
            });

            function clearSeller() {

                selectedValues.empty();
                $('input[name="select"]').prop('checked', false).closest('label').show();
                updateHiddenInput();
                updateSelectedValueElements();
            }
         
            // --- end custom select box ---


            // --- Check Validation on Submit ---

            // ---Seller commission and percentage ---

            function sellerCommissionPercentage() {
                
                var percentageArray = [];
                $('.percentage').each(function() {
                    var value = $(this).text().replace('%', '').trim();
                    percentageArray.push(value);
                });
                $('#selectedCommission').val(JSON.stringify(percentageArray));

                var amountArray = [];
                $('.sub_total_CSS').each(function() {
                    var value = $(this).text().replace('$', '').trim();
                    amountArray.push(value);
                });
                $('#selectedAmount').val(JSON.stringify(amountArray));

            }

            $('.submit_button').click(function(e) {
               
                var check_seller = $('#selectedValues').val();
                var seller_error = $("#seller_error");
                
                if (check_seller == '' || check_seller == '[]') {
                    seller_error.show();
                    e.preventDefault();
                } else {
                    
                    seller_error.hide();
                    sellerCommissionPercentage()
            
                }
            });

            $('.email_submit_button').click(function(e) {
                
                var check_seller = $('#selectedValues').val();
                var send_email = $('#email_text').val();
                var seller_error = $("#seller_error");
                var email_error = $("#email_error");

                const emailPattern2 = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (check_seller == '' || check_seller == '[]') {

                    seller_error.show();
                    e.preventDefault();
                }
                if (send_email == '') {
                    email_error.text('Please Enter Email').show();
                    e.preventDefault();
                }
                if (!emailPattern2.test(send_email)) {
                    email_error.text('Please Enter a valid Email Address.').show();
                    e.preventDefault();

                } else {

                    seller_error.hide();
                    email_error.hide();
                    sellerCommissionPercentage()
                }
            });

            // --- End Check Validation on Submit ---


            // --- Payment buttons Code Start Here ---

            $(".save_checkout").on("click", function() {
                var grand_total = parseFloat($("#popUp_total").text());
                $(".check_active").each(function(index) {
                    if (!$(this).hasClass('Active')) {
                        if (grand_total == 0.00) {
                            $(this).addClass('disabled_div');
                        } else {
                            $(this).removeClass('disabled_div');
                        }
                    }
                    if ($(this).hasClass('Active')) {
                        $(this).addClass('active_disabled_div');
                    }

                })
                if ($("#split_payment_btn").hasClass('active_split_payment')) {

                    $("#split_payment_btn").addClass('disabled_div');
                }

                $("#split_payment_btn").addClass('first_ok');
            })


            // function split_btn() {
            //     if(!$("#split_payment_btn").hasClass('active_split_payment')) {
            //         if (grand_total == 0.00) {                        

            //             $("#split_payment_btn").addClass('disabled_div');
            //         }
            //     } 
            // }
            var errorMessage_cash = $('#cash_changeAmount_error_message');

            $("#cash_button").on("click", function(e) {

                var grand_total = parseFloat($("#popUp_total").text());
                var cash = parseFloat($("#pcash").val());
                var return_amount_text = cash - grand_total;
                var total_due = grand_total - cash;
                $("#pcash_amount").val(cash.toFixed(2));


                if ($("#split_payment_btn").hasClass('active_split_payment')) {

                    $("#cash_div").hide();
                    // var zero = 0;
                    $("#popUp_total").text(total_due.toFixed(2));
                    var show_submitted_details =
                        `<tr class="mt-3 mb-3 append_submitted" data-append-row="cash_link" data-append-amount="` +
                        cash + `">
                                                    <td><button type="button" class="multi_cross_buttton"><i class="fa-solid fa-x"></i></button></td>
                                                    <td>Payment Method: Cash </td>
                                                    <td>Amount: <span>$` + cash.toFixed(2) + `</span></td>
                                                    <td colspan="2">Tendered: <span>$` + cash.toFixed(2) + `</span></td>                                                    
                                                 </tr>
                                                 <tr class="empty_div"></tr>`

                    $("#show_submitted_details").prepend(show_submitted_details).show();

                    // if (check_seller == '18' ) {
                    //     seller_error.show();
                    //     $("#print_Btn_div").hide();
                    //     e.preventDefault();
                    // }
                    if (return_amount_text > grand_total) {
                        $("#return_change_amount_div").show();
                        $("#return_change_amount").text(return_amount_text.toFixed(2));
                        $("#req_change_amount").val(return_amount_text.toFixed(2));
                    }
                    var grand_total2 = parseFloat($("#popUp_total").text());
                    if (grand_total2 == 0.00) {

                        $("#print_Btn_div").show();
                    }
                    $(".check_active").each(function(index) {
                        if (!$(this).hasClass('Active')) {
                            if (grand_total2 == 0.00) {
                                $(this).addClass('disabled_div');
                            }
                        }
                    })
                } else {

                    // if (check_seller == '18' ) {                                              
                    //     seller_error.show();
                    //     $("#print_Btn_div").hide();
                    //     e.preventDefault();
                    // }
                    if (isNaN(cash) || cash < grand_total) {
                        errorMessage_cash.show();
                        e.preventDefault();
                    } else {
                        errorMessage_cash.hide();
                        $("#cash_div").hide();

                        var zero = 0;
                        $("#popUp_total").text(zero.toFixed(2));
                        var show_submitted_details = `<tr class="mt-3 mb-3 append_submitted">
                                                        <td><button type="button" class="cross_buttton"><i class="fa-solid fa-x"></i></button></td>
                                                        <td>Payment Method: Cash </td>
                                                        <td>Amount: <span>$` + grand_total.toFixed(2) + `</span></td>
                                                        <td colspan="2">Tendered: <span>$` + cash.toFixed(2) + `</span></td>                                                 
                                                 </tr>
                                                 <tr class="empty_div"></tr>`
                        $("#show_submitted_details").html(show_submitted_details).show();
                        $("#print_Btn_div").show();
                        $("#return_change_amount_div").show();
                        $("#return_change_amount").text(return_amount_text.toFixed(2));
                        $("#req_change_amount").val(return_amount_text.toFixed(2));

                    }
                    var grand_total2 = parseFloat($("#popUp_total").text());
                    if (grand_total2 == 0.00) {

                        $("#split_payment_btn").addClass('disabled_div');
                        $("#print_Btn_div").show();
                    }
                    $(".check_active").each(function(index) {
                        if (!$(this).hasClass('Active')) {
                            if (grand_total2 == 0.00) {
                                $(this).addClass('disabled_div');
                            }
                        }
                    })
                }

            })

            var errorMessage = '';
            var credit_error_message = $("#credit_error_message");
            $("#credit_button").on("click", function(e) {

                var return_amount_text = parseFloat($("#pa_cradit_card").val());
                var grand_total = parseFloat($("#popUp_total").text());
                errorMessage = $('#changeAmount_error_message');
                var transaction_id = $("#pcredit").val();
                var last4_No = $("#last4").val();

                var total_due = grand_total - return_amount_text;
                
                $("#pa_cradit_card_amount").val(return_amount_text.toFixed(2));

                if ($("#split_payment_btn").hasClass('active_split_payment')) {

                    if (!$('#pcredit').val()) {

                        credit_error_message.show();
                        e.preventDefault();
                    } else {

                        credit_error_message.hide();
                        $("#credit_div").hide();

                        // var zero = 0;
                        $("#popUp_total").text(total_due.toFixed(2));
                        // $("#print_Btn_div").show();
                        var show_submitted_details =
                            `<tr class="mt-3 mb-3 append_submitted"  data-append-row="credit_link" data-append-amount="` +
                            return_amount_text + `">
                                                    <td><button type="button" class="multi_cross_buttton"><i class="fa-solid fa-x"></i></button></td>
                                                    <td>Payment Method: Credit Card </td>
                                                    <td>Amount: <span>$` + return_amount_text.toFixed(2) + `</span></td>
                                                    <td>Card Type: <span>` + transaction_id + `</span> </td>
                                                    <td>Last 4#: <span>` + last4_No + `</span></td>
                                                </tr>
                                                <tr class="empty_div"></tr>`
                        $("#show_submitted_details").prepend(show_submitted_details).show();
                    }
                    var grand_total2 = parseFloat($("#popUp_total").text());
                    if (grand_total2 == 0.00) {

                        $("#print_Btn_div").show();
                    }
                    $(".check_active").each(function(index) {
                        if (!$(this).hasClass('Active')) {
                            if (grand_total2 == 0.00) {
                                $(this).addClass('disabled_div');
                            }
                        }
                    })


                } else {

                    if (return_amount_text != grand_total) {
                        errorMessage.show();
                        credit_error_message.hide();
                        e.preventDefault();
                    } else if (!$('#pcredit').val()) {
                        credit_error_message.show();
                        errorMessage.hide();
                        e.preventDefault();
                    } else {

                        errorMessage.hide();
                        credit_error_message.hide()
                        $("#credit_div").hide();

                        var zero = 0;
                        $("#popUp_total").text(zero.toFixed(2));
                        $("#print_Btn_div").show();

                        var show_submitted_details = `<tr class="mt-3 mb-3 append_submitted">
                                                        <td><button type="button" class="cross_buttton"><i class="fa-solid fa-x"></i></button></td>
                                                        <td>Payment Method: Credit Card </td>
                                                        <td>Amount: <span>$` + return_amount_text.toFixed(2) + `</span></td>
                                                        <td>Card Type: <span>` + transaction_id + `</span> </td>
                                                        <td>Last 4#: <span>` + last4_No + `</span></td>
                                                     </tr>
                                                     <tr class="empty_div"></tr> `

                        $("#show_submitted_details").html(show_submitted_details).show();

                    }
                    var grand_total2 = parseFloat($("#popUp_total").text());
                    if (grand_total2 == 0.00) {
                        $("#split_payment_btn").addClass('disabled_div');
                        $("#print_Btn_div").show();
                    }
                    $(".check_active").each(function(index) {
                        if (!$(this).hasClass('Active')) {
                            if (grand_total2 == 0.00) {
                                $(this).addClass('disabled_div');
                            }
                        }
                    })
                }

            })

            var payment_error_message = $("#payment_error_message");
            $("#payment_button").on("click", function(e) {

                var return_amount_text = parseFloat($("#pa_payment_app").val());
                var grand_total = parseFloat($("#popUp_total").text());

                var payment_app = $("#payment_app").val();
                errorMessage = $('#changeAmount_error_message');

                $("#pa_payment_app_amount").val(return_amount_text.toFixed(2));

                if ($("#split_payment_btn").hasClass('active_split_payment')) {
                    if (!$('#payment_app').val()) {

                        payment_error_message.show();
                        e.preventDefault();
                    } else {

                        $("#payment_div").hide();
                        payment_error_message.hide();
                        // var zero = 0;
                        var total_due = grand_total - return_amount_text;

                        $("#popUp_total").text(total_due.toFixed(2));

                        var show_submitted_details =
                            `<tr class="mt-3 mb-3 append_submitted" data-append-row="payment_link" data-append-amount="` +
                            return_amount_text + `">
                                                    <td><button type="button" class="multi_cross_buttton"><i class="fa-solid fa-x"></i></button></td>
                                                    <td>Payment Method: Payment App </td>
                                                    <td>Amount: <span>$` + return_amount_text.toFixed(2) + `</span></td>
                                                    <td colspan='2'>App Name: <span>` + payment_app + `</span>  </td>                                                 
                                                 </tr>
                                                 <tr class="empty_div"></tr>`
                        $("#show_submitted_details").prepend(show_submitted_details).show();
                        // $("#print_Btn_div").show();

                    }

                    var grand_total2 = parseFloat($("#popUp_total").text());
                    if (grand_total2 == 0.00) {

                        $("#print_Btn_div").show();
                    }
                    $(".check_active").each(function(index) {
                        if (!$(this).hasClass('Active')) {
                            if (grand_total2 == 0.00) {
                                $(this).addClass('disabled_div');
                            }
                        }
                    })
                } else {

                    if (return_amount_text != grand_total) {
                        errorMessage.show();
                        payment_error_message.hide();
                        e.preventDefault();
                    } else if (!$('#payment_app').val()) {

                        payment_error_message.show();
                        errorMessage.hide();
                        e.preventDefault();
                    } else {
                        errorMessage.hide();
                        payment_error_message.hide();
                        $("#payment_div").hide();

                        var zero = 0;
                        $("#popUp_total").text(zero.toFixed(2));

                        var show_submitted_details = `<tr class="mt-3 mb-3 append_submitted">
                                                    <td><button type="button" class="cross_buttton"><i class="fa-solid fa-x"></i></button></td>
                                                    <td>Payment Method: Payment App </td>
                                                    <td>Amount: <span>$` + return_amount_text.toFixed(2) + `</span></td>
                                                    <td colspan="2">App Name: <span>` + payment_app + `</span>  </td>                                                    
                                                 </tr>
                                                 <tr class="empty_div"></tr>`

                        $("#show_submitted_details").html(show_submitted_details).show();
                        $("#print_Btn_div").show();
                    }
                    var grand_total2 = parseFloat($("#popUp_total").text());
                    if (grand_total2 == 0.00) {
                        $("#split_payment_btn").addClass('disabled_div');
                        $("#print_Btn_div").show();
                    }
                    $(".check_active").each(function(index) {
                        if (!$(this).hasClass('Active')) {
                            if (grand_total2 == 0.00) {
                                $(this).addClass('disabled_div');
                            }
                        }
                    })

                }

            })

            var chkerrorMessage = $('#check_error_message');
            $("#check_button").on("click", function(e) {

                var ckNo = $('#ck_no').val().trim();

                var return_amount_text = parseFloat($("#pa_check").val());
                var grand_total = parseFloat($("#popUp_total").text());

                var ck_name = $("#ck_name").val();

                errorMessage = $('#changeAmount_error_message');
                $("#pa_check_amount").val(return_amount_text.toFixed(2));


                if ($("#split_payment_btn").hasClass('active_split_payment')) {
                    if (ckNo == '') {

                        chkerrorMessage.show();

                    } else {
                        chkerrorMessage.hide();
                        $("#check_div").hide();

                        // var zero = 0;
                        var total_due = grand_total - return_amount_text;

                        $("#popUp_total").text(total_due.toFixed(2));

                        var show_submitted_details =
                            `<tr class="mt-3 mb-3 append_submitted" data-append-row="check_link" data-append-amount="` +
                            return_amount_text + `">
                                                    <td><button type="button" class="multi_cross_buttton"><i class="fa-solid fa-x"></i></button></td>
                                                    <td>Payment Method:  Check </td>
                                                    <td>Amount: <span>$` + return_amount_text.toFixed(2) + `</span></td>
                                                    <td>Check#: <span>` + ckNo + `</span> </td>
                                                    <td>Name: <span>` + ck_name + `</span></td>
                                                </tr>
                                                <tr class="empty_div"></tr>`

                        $("#show_submitted_details").prepend(show_submitted_details).show();
                        // $("#print_Btn_div").show();

                    }

                    var grand_total2 = parseFloat($("#popUp_total").text());
                    if (grand_total2 == 0.00) {

                        $("#print_Btn_div").show();
                    }
                    $(".check_active").each(function(index) {
                        if (!$(this).hasClass('Active')) {
                            if (grand_total2 == 0.00) {
                                $(this).addClass('disabled_div');
                            }
                        }
                    })
                } else {

                    if (return_amount_text != grand_total) {

                        errorMessage.show();
                        chkerrorMessage.hide();
                        e.preventDefault();

                    } else if (ckNo == '') {

                        chkerrorMessage.show();
                        errorMessage.hide();
                    } else {
                        chkerrorMessage.hide();
                        errorMessage.hide();
                        $("#check_div").hide();

                        var zero = 0;
                        $("#popUp_total").text(zero.toFixed(2));
                        var show_submitted_details = `<tr class="mt-3 mb-3 append_submitted">
                                                    <td><button type="button" class="cross_buttton"><i class="fa-solid fa-x"></i></button></td>
                                                    <td>Payment Method:  Check </td>
                                                    <td>Amount: <span>$` + return_amount_text.toFixed(2) + `</span></td>
                                                    <td>Check#: <span>` + ckNo + `</span> </td>
                                                    <td>Name: <span>` + ck_name + `</span></td>
                                                </tr>
                                                <tr class="empty_div"></tr>`
                        $("#show_submitted_details").html(show_submitted_details).show();
                        $("#print_Btn_div").show();

                    }
                    var grand_total2 = parseFloat($("#popUp_total").text());
                    if (grand_total2 == 0.00) {
                        $("#split_payment_btn").addClass('disabled_div');
                        $("#print_Btn_div").show();
                    }
                    $(".check_active").each(function(index) {
                        if (!$(this).hasClass('Active')) {
                            if (grand_total2 == 0.00) {
                                $(this).addClass('disabled_div');
                            }
                        }
                    })
                }

            })

            function clear_error_message() {
                errorMessage_cash.hide();
                errorMessage.hide();
                credit_error_message.hide();
                payment_error_message.hide();
                chkerrorMessage.hide();
            }

            // --- Payment buttons Code End Here ---


            // --- Model_clear_start ----

            $('#paymentModal').on('hidden.bs.modal', function(e) {

                $(this).find("input:not(.saller_value) ,select").val('').end()
                    .find("input[type=checkbox], input[type=radio]").prop("checked", false).end();
                $(this).find('.selectbox_customer_v').prop('selectedIndex', 0);
                $('#payment_app').val('').trigger('change');
                $("#print_Btn_div").hide();
                $(".selectbox_customer_v").val('').trigger('change');
                $('.decimals').val('');
                handle_clear_modal()
                clearSeller()
                clear_error_message()

            })

            $(document).on('click', ".close_payment_model", function() {

                $('#paymentModal').trigger('click')
                handle_clear_modal()
                clearSeller()
                clear_error_message()
            })

            $(document).on("click", ".cross_buttton", function() {

                handle_clear_modal()
            })

            // --- remove submitted payment row ---

            $(document).on("click", ".multi_cross_buttton", function() {
                clear_disable_payment()
                let totalDueAmount = localStorage.getItem("set_total_text");

                let totalAmount = 0;

                $(this).closest('tr').remove();
                let active_append_row = $(this).closest('tr').data("append-row");
             
                $('#' + active_append_row).removeClass(
                    'Active updated_active_link active_disabled_div disabled_div');
                if (active_append_row == 'cash_link') {

                    $('#' + active_append_row + '.decimals').val('');
                }
                $('.append_submitted').each(function() {

                    let appendAmount = $(this).data("append-amount");
                    totalAmount += appendAmount;
                });

                // let active_append_amount = $(this).closest('tr').data("append-amount");
                let totalDueAmount2 = totalDueAmount - totalAmount;

                $("#popUp_total").text(totalDueAmount2.toFixed(2));
            })

            function clear_disable_payment() {

                if ($(document).find('.append_submitted').length > 0) {

                    $(document).find('.append_submitted').each(function() {

                        let data_append_row = $(this).attr('data-append-row');
                        $('.disabled_div').not('#' + data_append_row).removeClass('disabled_div');
                    });

                }
            }


            // --- Model_clear_end ----

            $(".selectbox_customer").select2({
                placeholder: "Walk-in Customer",
            });
            $(".selectbox_seller").select2({
                placeholder: "Search Salesperson"
            });

        });

        // ------------- JS Function ------------- 


        function handle_clear_modal() {

            $("#cash_link").css('background-color', 'white');
            $("#cash_div").hide();
            $("#method_cash").val(0);
            $("#cash_link").removeClass("Active");
            c = 1;

            $("#credit_link").css('background-color', 'white');
            $("#credit_div").hide();
            $("#method_credit").val(0);
            $("#credit_link").removeClass("Active");
            cr = 1;

            $("#payment_link").css('background-color', 'white');
            $("#payment_div").hide();
            $("#method_payment_app").val(0);
            $("#payment_link").removeClass("Active");
            ma = 1;


            $("#check_link").css('background-color', 'white');
            $("#check_div").hide();
            $("#method_check").val(0);
            $("#check_link").removeClass("Active");
            ck = 1;

            $('#changeAmount_error_message').hide();
            $('#check_error_message').hide();
            $('#change_amount_div').hide();
            $('#cash_change_amount_div').hide();


            if ($("#split_payment_btn").hasClass('active_split_payment')) {
                $("#split_payment_btn").text('Split Payment');
                $("#split_payment_btn").removeClass('active_split_payment');
            }

            // $("#show_submitted_details").hide();
            $(".check_active").removeClass('active_disabled_div');
            $(".check_active").removeClass('disabled_div')
            $("#split_payment_btn").removeClass('disabled_div');

            // Grande Total 
            var sub_total = $("#sub_total_text").text();
            var tax = $("#tax_text").text();
            var grand_total = parseFloat(sub_total) + parseFloat(tax);
            grand_total = grand_total.toFixed(2);
            $("#popUp_total").text(grand_total);

            $("#return_change_amount_div").hide();

            let zero = 0;
            $("#return_amount_text").text(zero.toFixed(2));

            $("#print_Btn_div").hide();
            $(".append_submitted").remove();

        }

        function edit_cart_price(edit_price, id) {

            $("#cprd_price_" + id).val(edit_price);
            total_calculation();
            sub_total_calculation();
        }

        function cart_qty() {

            var count = $('.product-list').length;

            if (count > 0) {
                $('#enable_btn').show();
                $('#disable_btn').hide();
                $('.selling-info').show();

            } else {
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

        function exchange_calculation() {
            total_price = 0;
            total_min_price = 0

            $('.cart_items').each(function() {

                var class_name = $("#" + $(this).attr('id')).attr('class').split(' ')[1];
                item_id = class_name.split("_");

                var price = $("#cprd_price_" + item_id[1]).val();
                var min_price = $("#cprd_minprice_" + item_id[1]).val();

                if ($("#quantity_" + item_id[1]).val() == 1 && $("#quantity_" + item_id[1]).attr('disabled')) {

                    var quantity = -1;

                } else {

                    var quantity = $("#quantity_" + item_id[1]).val();
                }

                total_min_price += parseFloat(min_price) * parseFloat(quantity);

                total_price += parseFloat(price) * parseFloat(quantity);

            });

            var f_min_total = (total_price) - (total_min_price);

            $("#min_total").text(f_min_total.toFixed(2));

            $('.current_min_total').remove();

        }

        function sub_total_calculation() {
            total_price = 0;
            total_min_price = 0

            $('.cart_items').each(function() {

                var class_name = $("#" + $(this).attr('id')).attr('class').split(' ')[1];
                item_id = class_name.split("_");

                var price = $("#cprd_price_" + item_id[1]).val();
                var min_price = $("#cprd_minprice_" + item_id[1]).val();
                // var quantity = $("#quantity_"+item_id[1]).val();

                if ($("#quantity_" + item_id[1]).val() == 1 && $("#quantity_" + item_id[1]).attr('disabled')) {

                    var quantity = -1;

                } else {

                    var quantity = $("#quantity_" + item_id[1]).val();
                }

                total_price += parseFloat(price) * parseFloat(quantity);
                total_min_price += parseFloat(min_price) * parseFloat(quantity);

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
            $("#sale_sub_total").val(total_price.toFixed(2));

            var tax_amount = (total_price / 100) * {{ $location->location_tax }};

            $("#tax_text").text(tax_amount.toFixed(2));
            $("#sale_tax_amount").val(tax_amount.toFixed(2));

            var sub_total = $("#sub_total_text").text();
            var min_total = parseFloat(sub_total) - parseFloat(total_min_price);
            var f_min_total = min_total.toFixed(2);

            $("#min_total").text(f_min_total);

            $('.current_min_total').remove();
            total_calculation();
        }

        function total_calculation() {
            var sub_total = $("#sub_total_text").text();
            var tax = $("#tax_text").text();

            var grand_total = parseFloat(sub_total) + parseFloat(tax);

            grand_total = grand_total.toFixed(2);

            $("#total_text").text(grand_total);
            $("#grand_total_text").text(grand_total);

            $("#sale_grand_total").val(grand_total);

            // Checkout Popup
            $("#popUp_total").text(grand_total);
            localStorage.setItem("set_total_text", grand_total);

        }

        // --- add decimals at input field function ---

        $('.decimals').on('blur', function() {
            var value = $(this).val();
            if (value !== '') {
                var floatValue = parseFloat(value);
                if (!isNaN(floatValue)) {
                    $(this).val(floatValue.toFixed(2));
                }
            }
        });


        var cash_link_id = $('#cash_link').attr('id');
        $('#pcash').attr('data-cash_amount', cash_link_id);

        var credit_link_id = $('#credit_link').attr('id');
        $('#pa_cradit_card').attr('data-cash_amount', credit_link_id);

        var payment_link_id = $('#payment_link').attr('id');
        $('#pa_payment_app').attr('data-cash_amount', payment_link_id);

        var check_link_id = $('#check_link').attr('id');
        $('#pa_check').attr('data-cash_amount', check_link_id);

        $(".amount_in_cash").on("keyup", function() {

            $(this).addClass('updated_amount');
            var currentDataAttribute = $(this).data('cash_amount');
            $('#' + currentDataAttribute).addClass('updated_active_link');
        });

        $(document).on("keyup", ".edit_price", function() {

            let edit_price = $(this).val();
            if (!isNaN(edit_price) && edit_price !== '') {

                let decimalIndex = edit_price.indexOf('.');
                if (decimalIndex !== -1 && edit_price.length - decimalIndex - 1 > 2) {
                    edit_price = edit_price.substring(0, decimalIndex + 3);
                    $(this).val(edit_price);
                }
            }
            let product_id = $(this).data('product_id');
            edit_cart_price(edit_price, product_id);
            update_cart(edit_price, product_id);
        });

        function handleDiscountTypeChange() {
            total_calculation();
            sub_total_calculation();
        }

        function handleDiscountKeyup() {
            total_calculation();
            sub_total_calculation();
        }

        function split_payment(active_class) {

            $(active_class).toggleClass('active_split_payment')
            // $('.check_active').css('pointer-events','auto');
            $(".check_active").removeClass('disabled_div active_disabled_div');
            resetInputField()

            $("#cash_link").css('background-color', 'white').removeClass("Active");
            $("#cash_div").hide();
            $("#credit_link").css('background-color', 'white').removeClass("Active");
            $("#credit_div").hide();
            $("#payment_link").css('background-color', 'white').removeClass("Active");
            $("#payment_div").hide();
            $("#check_link").css('background-color', 'white').removeClass("Active");
            $("#check_div").hide();
            $("#method_cash").val(0);
            $("#method_credit").val(0);
            $("#method_payment_app").val(0);
            $("#method_check").val(0);
            c = 1;
            cr = 1;
            ma = 1;
            ck = 1;

            if ($("#split_payment_btn").hasClass('active_split_payment')) {

                $("#pa_cradit_card").removeAttr('disabled');
                $("#pa_payment_app").removeAttr('disabled');
                $("#pa_check").removeAttr('disabled');
                $("#split_payment_btn").text("Cancel Split");
                $(".check_active").removeClass('disabled_div');


            } else {

                $("#pa_cradit_card").attr('disabled', 'disabled');
                $("#pa_payment_app").attr('disabled', 'disabled');
                $("#pa_check").attr('disabled', 'disabled');
                $("#split_payment_btn").text(" Split Payment");
            }

        }

        function resetInputField() {

            $("#pcash").val('');
            $("#pcredit").val('');
            $("#last4").val('');
            $("#payment_app").val('');
            $("#ck_name").val('');
            $("#ck_no").val('');
        }

        function resetPaymentType() {
            c = 1;
            cr = 1;
            ma = 1;
            ck = 1;
            var grand_total = parseFloat($("#popUp_total").text());

            $("#cash_link").css('background-color', 'white').removeClass("Active");
            $("#cash_div").hide();
            $("#pcash").val('');
            $("#method_cash").val(0);

            $("#credit_link").css('background-color', 'white').removeClass("Active");
            $("#credit_div").hide();
            $("#pa_cradit_card").val(grand_total).attr('disabled', 'disabled');
            $("#method_credit").val(0);

            $("#payment_link").css('background-color', 'white').removeClass("Active");
            $("#payment_div").hide();
            $("#pa_payment_app").val(grand_total).attr('disabled', 'disabled');
            $("#method_payment_app").val(0);
            $("#payment_app").val()

            $("#check_link").css('background-color', 'white').removeClass("Active");
            $("#check_div").hide();
            $("#pa_check").val(grand_total).attr('disabled', 'disabled');
            $("#method_check").val(0);

            $("#print_Btn_div").hide();

            $('#changeAmount_error_message').hide();

            let zero = 0;

            $("#return_amount_text").text(zero.toFixed(2));
        }

        function resetPaymentType2() {

            // $("#method_cash").val(0);
            // $("#method_credit").val(0);
            // $("#method_payment_app").val(0);
            // $("#method_check").val(0);                

            $("#cash_link").css('background-color', 'white').removeClass("Active");
            $("#cash_div").hide();


            $("#credit_link").css('background-color', 'white').removeClass("Active");
            $("#credit_div").hide();

            $("#payment_link").css('background-color', 'white').removeClass("Active");
            $("#payment_div").hide();
            $("#payment_app").val()

            $("#check_link").css('background-color', 'white').removeClass("Active");
            $("#check_div").hide();

            $("#print_Btn_div").hide();

        }

        function multiple_payment() {

            c = 1;
            cr = 1;
            ma = 1;
            ck = 1;

            $('.check_active').each(function() {

                var currentDataAttribute = $(this).data('toggle_active_value');
                if (!$(this).hasClass('active_disabled_div')) {

                    $('#' + currentDataAttribute).val(0);
                } else {
                    $('#' + currentDataAttribute).val(1);
                }

                if ($(this).hasClass('Active')) {

                    $('#' + currentDataAttribute).val(1);
                }
            })
        }

        function change_amount_old() {

            let grand_total = parseFloat($("#popUp_total").text());
            let return_cash = pcash - grand_total;

            var remain_cash_amount = 0;
            $(".updated_amount").each(function() {

                remain_cash_amount += parseFloat($(this).val());
            });
            var remaining_payment_amount = 0;
            if ($("#split_payment_btn").hasClass('first_ok')) {
                remaining_payment_amount = grand_total;
            } else {
                remaining_payment_amount = grand_total - remain_cash_amount;
            }
            let payment_type_count = $(".Active").length;
            let payment_type_count2 = $(".updated_active_link").length || 1;
            let payment_type_result = payment_type_count - payment_type_count2;

            let display_remaining_payment_amount = remaining_payment_amount / (payment_type_result);

            $(".amount_in_cash").each(function() {

                if (!$(this).hasClass('updated_amount') && !$(this).hasClass('cash_amount')) {

                    $(this).val(display_remaining_payment_amount.toFixed(2))
                }
            });
        }

        function change_amount() {

            let grand_total = parseFloat($("#popUp_total").text());

            var show_payment_amount = grand_total;
            $(".amount_in_cash").not('.cash_amount').val(show_payment_amount);

        }

        function updateChangeAmount() {

            if ($("#cash_link").hasClass("Active") || $("#credit_link").hasClass("Active") || $("#payment_link").hasClass(
                    "Active") || $("#check_link").hasClass("Active")) {

                if ($("#split_payment_btn").hasClass("active_split_payment")) {

                    $("#cash_change_amount_div").hide();

                } else {

                    $("#cash_change_amount_div").show();
                    $("#change_amount_div").show();
                }

            } else {

                $("#cash_change_amount_div").hide();
                $("#change_amount_div").hide();

            }
        }


        let c = 1;
        let cr = 1;
        let ma = 1;
        let ck = 1;

        function select_cash() {

            if ($("#split_payment_btn").hasClass("active_split_payment")) {
                resetPaymentType2()
                if (c % 2 == 0) {
                    $("#cash_link").css('background-color', 'white');
                    $("#cash_div").hide();
                    $("#cash_link").removeClass("Active");
                    $("#pcash").val('');
                    // $("#method_cash").val(0);
                    change_amount()

                } else {
                    $("#cash_link").css('background-color', 'antiquewhite');
                    $("#cash_div").show();
                    $("#cash_div").prependTo("#payment_method_div");
                    $("#cash_link").addClass("Active");
                    // $("#method_cash").val(1);
                    $("#pcash").focus();
                    change_amount()
                }
                c++;

                multiple_payment()
            } else {
                resetPaymentType()
                resetInputField()
                if (c % 2 == 0) {
                    $("#cash_link").css('background-color', 'white');
                    $("#cash_div").hide();
                    $("#method_cash").val(0);
                    $("#cash_link").removeClass("Active");
                    $("#cash_change_amount_div").hide();
                } else {
                    $("#cash_link").css('background-color', 'antiquewhite');
                    $("#cash_div").show();
                    $("#cash_link").addClass("Active");
                    $("#method_cash").val(1);
                    $("#cash_change_amount_div").show();
                    $("#pcash").focus();
                }

                c++;
            }
            updateChangeAmount();
        }

        function select_credit() {

            if ($("#split_payment_btn").hasClass("active_split_payment")) {
                resetPaymentType2()
                if (cr % 2 == 0) {
                    $("#credit_link").css('background-color', 'white');
                    $("#credit_div").hide();
                    $("#credit_link").removeClass("Active");
                    $("#pa_cradit_card").val('');
                    $("#pcredit").val('');
                    $("#last4").val('');
                    // $("#method_credit").val(0);
                    change_amount()
                } else {
                    $("#credit_link").css('background-color', 'antiquewhite');
                    $("#credit_div").show();
                    $("#credit_div").prependTo("#payment_method_div");
                    $("#credit_link").addClass("Active");
                    change_amount()
                    setTimeout(() => {
                        $('#pa_cradit_card').focus().select().css('user-select', 'all');
                    }, 200);
                }

                cr++;
                multiple_payment()
            } else {

                resetPaymentType()
                resetInputField()
                if (cr % 2 == 0) {
                    $("#credit_link").css('background-color', 'white');
                    $("#credit_div").hide();
                    $("#method_credit").val(0);
                    $("#credit_link").removeClass("Active");
                    $("#change_amount_div").hide();
                } else {
                    $("#credit_link").css('background-color', 'antiquewhite');
                    $("#credit_div").show();
                    $("#credit_link").addClass("Active");
                    $("#method_credit").val(1);
                    $("#change_amount_div").show();
                }

                cr++;
            }

            // updateChangeAmount();

        }

        function select_payment_app() {

            if ($("#split_payment_btn").hasClass("active_split_payment")) {
                resetPaymentType2()
                if (ma % 2 == 0) {
                    $("#payment_link").css('background-color', 'white');
                    $("#payment_div").hide();
                    $("#payment_link").removeClass("Active");
                    $("#pa_payment_app").val('');
                    $("#payment_app").val('');
                    // $("#method_payment_app").val(0);
                    change_amount()
                } else {
                    $("#payment_link").css('background-color', 'antiquewhite');
                    $("#payment_div").show();
                    $("#payment_div").prependTo("#payment_method_div");
                    $("#payment_link").addClass("Active");
                    // $("#method_payment_app").val(1);
                    change_amount()
                    setTimeout(() => {
                        $('#pa_payment_app').focus().select().css('user-select', 'all');
                    }, 200);
                }

                ma++;
                multiple_payment()
            } else {

                resetPaymentType()
                if (ma % 2 == 0) {
                    $("#payment_link").css('background-color', 'white');
                    $("#payment_div").hide();
                    $("#method_payment_app").val(0);
                    $("#payment_link").removeClass("Active");
                    $("#change_amount_div").hide();
                } else {
                    $("#payment_link").css('background-color', 'antiquewhite');
                    $("#payment_div").show();
                    $("#payment_link").addClass("Active");
                    $("#method_payment_app").val(1);
                    $("#change_amount_div").show();
                }


                ma++;

            }
            // updateChangeAmount()
        }

        function select_check() {
            if ($("#split_payment_btn").hasClass("active_split_payment")) {
                resetPaymentType2()

                if (ck % 2 == 0) {
                    $("#check_link").css('background-color', 'white');
                    $("#check_div").hide();
                    $("#check_link").removeClass("Active");
                    $("#pa_check").val('');
                    $("#ck_name").val('');
                    $("#ck_no").val('');
                    // $("#method_check").val(0);

                    change_amount()
                } else {
                    $("#check_link").css('background-color', 'antiquewhite');
                    $("#check_div").show();
                    $("#check_div").prependTo("#payment_method_div");
                    $("#check_link").addClass("Active");
                    // $("#method_check").val(1);                    
                    change_amount()
                    setTimeout(() => {
                        $('#pa_check').focus().select().css('user-select', 'all');
                    }, 200);
                }

                ck++;
                multiple_payment()
            } else {

                resetPaymentType()
                resetInputField()
                if (ck % 2 == 0) {
                    $("#check_link").css('background-color', 'white');
                    $("#check_div").hide();
                    $("#method_check").val(0);
                    $("#check_link").removeClass("Active");
                    $("#change_amount_div").hide();
                } else {
                    $("#check_link").css('background-color', 'antiquewhite');
                    $("#check_div").show();
                    $("#check_link").addClass("Active");
                    $("#method_check").val(1);
                    $("#change_amount_div").show();
                }

                ck++;
            }
            // updateChangeAmount()
        }

        // Xx------------- Extra code START here -------------xX

        d = 1;

        function select_debit() {
            if (d % 2 == 0) {
                $("#debit_link").css('background-color', 'white');
                $("#debit_div").hide();
                $("#method_debit").val(0);
            } else {
                $("#debit_link").css('background-color', 'antiquewhite');
                $("#debit_div").show();
                $("#method_debit").val(1);
            }

            d++;
        }
        $("#pa_cradit_card").on("keyup", function() {

        })
        $("#pa_payment_app").on("keyup", function() {

        })
        $("#pa_check").on("keyup", function() {

        })
        // Xx------------- Extra code END here -------------xX 


        // ------------- AJAX Request -------------

        function add_to_cart(product_id) {

            var qty = 1;
            var csrf = "{{ csrf_token() }}";

            $.ajax({
                url: "{{ route('location.add_to_cart') }}",
                type: "post",
                data: "product_id=" + product_id + "&qty=" + qty + "&_token=" + csrf,
                success: function(response) {
                    $("#cart_div").html(response);
                    sub_total_calculation();
                },
                complete: function(params) {

                    $(document).find('.edit_price').focus().select().css('user-select', 'all');
                    cart_qty();

                }
            });
        }

        function update_cart(edit_price, product_id) {

            var qty = $("#quantity_" + product_id).val();

            if (qty < 1) {
                $("#quantity_" + product_id).val(1);
            } else {
                if (edit_price != '') {

                    setTimeout(function() {

                        var qty = $("#quantity_" + product_id).val();

                        var csrf = "{{ csrf_token() }}";

                        $.ajax({
                            url: "{{ route('location.update_cart') }}",
                            type: "post",
                            data: "product_id=" + product_id + "&edit_price=" + edit_price + "&qty=" + qty +
                                "&_token=" + csrf,
                            success: function(response) {
                                sub_total_calculation();
                                exchange_calculation()
                            }
                        });

                    }, 200);
                } else {

                    setTimeout(function() {

                        var qty = $("#quantity_" + product_id).val();

                        var csrf = "{{ csrf_token() }}";

                        $.ajax({
                            url: "{{ route('location.update_cart') }}",
                            type: "post",
                            data: "product_id=" + product_id + "&qty=" + qty + "&_token=" + csrf,
                            success: function(response) {
                                sub_total_calculation();
                                exchange_calculation()
                            }
                        });

                    }, 200);
                }

            }
        }

        function used_update_cart(edit_price, product_id) {
            if (edit_price != '') {

                setTimeout(function() {

                    var qty = 1;

                    var csrf = "{{ csrf_token() }}";

                    $.ajax({
                        url: "{{ route('location.used_update_cart') }}",
                        type: "post",
                        data: "product_id=" + product_id + "&edit_price=" + edit_price + "&qty=" + qty +
                            "&is_readonly=1" + "&_token=" + csrf,
                        success: function(response) {
                            $("#cart_div").html(response);
                            sub_total_calculation();
                            exchange_calculation();
                        }
                    });

                }, 200);
            }
        }

        function new_update_cart(edit_price, product_id) {
            if (edit_price != '') {

                setTimeout(function() {

                    var qty = -1;

                    var csrf = "{{ csrf_token() }}";

                    $.ajax({
                        url: "{{ route('location.new_update_cart') }}",
                        type: "post",
                        data: "product_id=" + product_id + "&edit_price=" + edit_price + "&qty=" + qty +
                            "&is_readonly=1" + "&_token=" + csrf,
                        success: function(response) {
                            $("#cart_div").html(response);
                            sub_total_calculation();
                            exchange_calculation();
                        }
                    });

                }, 200);
            }
        }

        function remove_cart(product_id) {
            var csrf = "{{ csrf_token() }}";

            $.ajax({
                url: "{{ route('location.remove_cart') }}",
                type: "post",
                data: "product_id=" + product_id + "&_token=" + csrf,
                success: function(response) {
                    $("#cart_div").html(response);
                    sub_total_calculation();
                    cart_qty();
                }
            });

        }

        // --- add new customer modal ----

        function get_customer(newCustomerId) {

            $.ajax({
                url: "{{ route('location.getCustomers') }}",
                type: "GET",
                dataType: "json",
                success: function(response) {

                    let selectBox = $('select.selectbox_customer_v');
                    selectBox.empty();

                    if (response.length > 0) {
                        selectBox.append('<option value="">Walk-in Customer</option>');
                        response.forEach(function(customer) {
                            selectBox.append('<option value="' + customer.customer_id + '">' + customer
                                .customer_name + '</option>');
                        });

                        if (newCustomerId) {
                            selectBox.val(newCustomerId).trigger('change');
                        } else {
                            selectBox.val('').trigger('change');
                        }
                    } else {
                        $('#customerError').text('No customers found').show();
                    }
                },
                error: function() {
                    $('#customerErrornot').text('Failed to load customers').show();
                }
            });
        }

        var customer_modal = $('#Add_New_Customer_modal');

        $('#Add_New_Customer').on('click', function() {
            customer_modal.modal('show');
        });

        $('#closeModal').on('click', function() {
            customer_modal.modal('hide');
            customer_modal.find("input").val('');
            $('#customer_seller').val(null).trigger('change');
        });

        $('#addCustomer').on("click", function(e) {

            var csrf = "{{ csrf_token() }}";
            let customerName = $("#customerName").val();
            let customerEmail = $("#customerEmail").val();
            let customerPhone = $("#customerPhone").val();
            let customer_seller = $("#customer_seller").val();

            if (customerName === "") {
                $('#customerError').text('Customer Name is required.').show();
                return;
            }
            if (customerEmail === "") {
                $('#customerError').text('Customer Email is required.').show();
                return;
            }
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(customerEmail)) {
                $('#customerError').text('Please Enter a valid Email Address.').show();
                return;
            }

            $.ajax({
                url: "{{ route('location.add_customer') }}",
                type: "POST",
                data: {
                    _token: csrf,
                    customer_name: customerName,
                    customer_email: customerEmail,
                    customer_phone: customerPhone,
                    customer_seller: customer_seller
                },
                beforeSend: function() {
                    $('#customerError').text('').hide();
                    $('#addCustomer').text('Adding').attr('disabled', true);
                },
                success: function(response) {

                    if (response.status == 'error') {
                        $('#customerError').text(response.message).show();
                    } else if (response.status == 'success') {
                        get_customer(response.customer_id)
                        customer_modal.modal('hide');
                        customer_modal.find("input").val('');
                        $('#customer_seller').val(null).trigger('change');
                    }
                },
                complete: function() {
                    $('#addCustomer').text('Add Customer').attr('disabled', false);
                }
            });
        });

        // --- end Add customer modal ----

        // --- send email ---

        var send_email_modal = $("#send_email");
        $('#send_email_button').on('click', function(e) {
            
            var check_seller = $('#selectedValues').val();
            var seller_error = $("#seller_error");
            
            if (check_seller == '' || check_seller == '[]') {
                seller_error.show();
                e.preventDefault();
            } 
            else{

                $("#send_email").modal('show');
                var check_seller = $('#selectedValues').val();
                var seller_error = $("#seller_error");

                if (check_seller == '' || check_seller == '[]') {
                    seller_error.show();
                    e.preventDefault();
                } else {
                    seller_error.hide();
                    send_email_modal.show();
                }
            }
           
        });
        $('#closeModal_send_email').on('click', function() {
            send_email_modal.hide();
        });
        // --- end send email ---
    </script>
@endsection
