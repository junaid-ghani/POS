
<div class="head d-flex align-items-center justify-content-between w-100" style="margin: 20px 0 20px 0;">
    <h5>Cart</h5>
    <h6 id="min_total" class="mb-0"></h6>  
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
                    <input type="text" class="form-control text-center edit_price" data-product_id="{{$item->id}}" value="{{$item->price}}" >                                            
                </div>
                <input type="hidden" id="cprd_minprice_{{ $item->id }}" value="{{ $item->attributes->min_price }}">
                <input type="hidden" class="cart_items citem_{{ $item->id }}" id="cprd_price_{{ $item->id }}" value="{{ $item->price }}">
                </div>
            </div>
        </div>
        <div class="qty-item text-center">
            <a href="javascript:void(0);" class="dec d-flex justify-content-center align-items-center" ><i data-feather="minus-circle" class="feather-14" onclick="update_cart('',{{ $item->id }})"></i></a>
            <input type="text" class="form-control text-center" id="quantity_{{ $item->id }}" name="product_qty[{{ $item->id }}]" value="{{ $item->quantity }}" onkeyup="update_cart('',{{ $item->id }})">
            <a href="javascript:void(0);" class="inc d-flex justify-content-center align-items-center" ><i data-feather="plus-circle" class="feather-14" onclick="update_cart('',{{ $item->id }})"></i></a>
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
<script src="{{ asset(config('constants.admin_path').'js/feather.min.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'js/script.js') }}"></script>