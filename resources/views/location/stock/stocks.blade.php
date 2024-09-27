@extends('location.layouts.app')
@section('title', config('constants.site_title') . ' | Stocks')
@section('contents')

    {{-- @dd(session("verified_user_id")) --}}
    
    <div class="content">
        <form id="update_stock_form" method="post">
            @csrf
            <input type="hidden" id="timezone" name="timezone">

            <div class="mb-3 d-flex ">
            </div>
            <div class="page-header">

                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4 class="mb-2">Inventory</h4>

                        <h6>Manage Inventory</h6>
                    </div>
                </div>
                <ul class="table-top-head">
                    <li>
                        <a href="{{ route('location.stocks') }}" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Refresh"><i data-feather="rotate-ccw" class="feather-rotate-ccw"></i></a>
                    </li>
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i
                                data-feather="chevron-up" class="feather-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="page-btn d-flex">
                    
                    <a href="javascript:void(0)" class="btn btn-dark color" id="report" style="margin-right: 10px;">Report Tester/Loss</a>


                    @if ($user->user_role == 4)

                        @if ($settings->allow_transfer == 1)                        
                            {{-- <a href="{{ route('location.transfer_products') }}" class="btn btn-dark color"  style="margin-right: 10px;">Transfer</a> --}}
                            <button type="button" class="btn btn-dark color" style="margin-right: 10px;" data-bs-toggle="modal" data-bs-target="#Transfer_product">Transfer</button> 

                        @endif
                        @if ($settings->allow_changes == 1) 
                            <button type="submit" name="submit" id="update_stock" class="btn btn-dark color" value="submit" style="margin-right: 10px;">Update Stock</button>
                        @elseif($settings->allow_changes == 0)

                            <input type="hidden" name="user_id" value="{{ $user->user_id }}">
                            <button type="submit" id="submit_count" class="btn btn-dark color" style="margin-right: 10px;">Submit Count</button>                    
                  
                        @endif
                        
                    @else

                        {{-- <a href="{{ route('location.transfer_products') }}" class="btn btn-dark color" style="margin-right: 10px;">Transfer</a> --}}
                        <button type="button" class="btn btn-dark color" style="margin-right: 10px;" data-bs-toggle="modal" data-bs-target="#Transfer_product">Transfer</button> 

                        <button type="submit" name="submit" id="update_stock" class="btn btn-dark color" value="submit" style="margin-right: 10px;">Update Stock</button>
                    
                    @endif                    

                    <button type="button" class="btn btn-primary color" ><i class="fa-solid fa-print me-2" ></i>Print</button>
                </div>
            </div>

            <div class="card table-list-card">
                <div class="card-body">
                    <div class="table-top">
                        <div class="search-set">
                            <div class="search-input">
                                <a href="javascript:void(0);" class="btn btn-searchset"><i data-feather="search"
                                        class="feather-search"></i></a>
                            </div>
                        </div>
                        {{-- <div class="search-path">
                        <a class="btn btn-filter" id="filter_search">
                            <i data-feather="filter" class="filter-icon"></i>
                            <span><img src="{{ asset(config('constants.admin_path').'img/icons/closes.svg') }}" alt="img"></span>
                        </a>
                    </div> --}}

                        <div class="card mb-0" id="filter_inputs" style="display: block">
                            <div class="card-body" style="padding: 0px;">
                                <div class="row">
                                    <div class="col-lg-12 col-sm-12">

                                        <div class="input-blocks" style="margin-bottom:0;">
                                            {{-- <i data-feather="home" class="info-img"></i> --}}
                                            @php
                                                $location_id = Auth::guard('location')->id();
                                            @endphp
                                            <input type="hidden" value="{{ $location_id }}" name="stock_location"  id="stock_location" />

                                            {{-- <select name="stock_location" id="stock_location" class="form-control selectbox" onchange="filter_options()">
                                                    <option value="">Location</option>
                                                    @foreach ($locations as $location)
                                                    <option value="{{ $location->location_id }}" @if ($loop->iteration == 1) selected @endif>{{ $location->location_name }}</option>
                                                    @endforeach
                                                </select> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="table-responsive">
                        <table id="stock_table" class="table" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Barcode</th>
                                    <th>Category</th>
                                    <th>Product Name</th>
                                    {{-- <th>Location</th> --}}
                                    <th>Stock</th>
                                    <th>Count</th>
                                    {{-- <th>Action</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </form>
        
        <!-- Report modal -->
        <div class="modal fade" id="report_modal" data-bs-backdrop="static">
            <div class="modal-dialog" style="max-width: 630px ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" style="margin: auto 210px">Report Tester/Loss</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="stock_update_form" action="{{ route('location.update_stock') }}" method="post">
                            @csrf
                            <div class="report_form">
                                <input type="hidden" value="{{ $location_id }}" name="stock_location"  id="stock_location" />
                                <div class="input-block row inputBlock">
                                    {{-- <div class="col-10">
                                        <div class="alert alert-danger" style="display:none" id="ProductError"></div>
                                    </div> --}}
                                    <div class="col-5">
                                        <label for="product">Product:</label>
                                    </div>
                                    <div class="col-5">
                                        <select class="form-control getProducts" name="product_id" id="product">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                        </select>
                                        <small id="ProductError" class="text-danger" style="display: none;"></small>
                                    </div>                                        
                                </div>   
                                <div class="input-block  row inputBlock">
                                    <div class="col-5">
                                        <label for="quantity">Quantity:</label>
                                    </div>
                                    <div class="col-5">
                                        <input type="number" id="deduct_product_quantity" name="deduct_product_quantity" class="form-control">
                                        <small id="quantityError" class="text-danger" style="display: none;"></small>
                                    </div>
                                </div>   
                                <div class="input-block  row inputBlock">
                                    <div class="col-5">
                                        <label for="reason">Reason:</label>
                                    </div>
                                    <div class="col-5">
                                        <select class="form-control select" name="reason" id="reason">
                                            <option value="">Select Reason</option>
                                            <option value="Tester">Tester</option>
                                            <option value="Damaged">Damaged</option>
                                            <option value="Other">Other</option>
                                        </select>
                                        <small id="reasonError" class="text-danger" style="display: none;"></small>
                                    </div>                                       
                                </div> 
                                <div class="input-block row inputBlock">
                                    <div class="col-5">
                                        <label for="comment">Comments:</label>
                                    </div>
                                    <div class="col-5">
                                        <textarea class="form-control" name="comment" id="comment" cols="3" rows="2"></textarea>
                                    </div>
                                </div>  
                                <div class="input-block mt-5 row ">
                                    <div class="col-10 inputBlock">
                                        <button type="submit" name="submit" value="submit" class="btn btn-primary " >Submit</button>                                        
                                    </div>                                      
                                </div>                   
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Report modal -->



        <div class="modal fade" id="Transfer_product">
            <div class="modal-dialog modal-dialog-centered custom-modal-two" style="max-width: 1000px;">
                <div class="modal-content">
                    <div class="page-wrapper-new p-0">
                        <div class="content">
                            <div class="modal-header border-0 custom-modal-header">
                                <div class="page-title">
                                    <h4>Transfer Items</h4>
                                </div>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body custom-modal-body">
                               @include('location.transfer_product.transfer_products')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
@section('custom_script')

    <script>

        var userTimeZone = Intl.DateTimeFormat().resolvedOptions().timeZone;
        document.getElementById('timezone').value = userTimeZone;

        $(document).ready(function () {    

            $('#update_stock').on('click', function() {
                $('#update_stock_form').attr('action', "{{ route('location.update_stock') }}").submit();
            });

            $('#submit_count').on('click', function() {
                $('#update_stock_form').attr('action', "{{ route('location.inventory_log') }}").submit();
            }); 

            $('#report').click(function () {

                $("#report_modal").modal('show');
        
                var stock_location = $('#stock_location').val();
                $.ajax({
                    url: "{{ route('location.getProduct') }}",
                    type: "GET",
                    data:{
                        
                        stock_location:stock_location    
                    },
                    success: function(response) {                   
                    
                        let selectBox = $('select.getProducts');
                        selectBox.empty();

                        if (response.product.length > 0) {                        

                            selectBox.append('<option value="">Search Product</option>');
                            response.product.forEach(function(product) {                      
                                selectBox.append('<option value="' + product.product_id + '">' + product.product_name + '</option>');
                            });
                        }else{

                            $('#ProductError').text('No customers found').show();
                        }
                    },
                    error: function() {
                        $('#ProductError').text('Failed to load customers').show();
                    }
                });
            });

            $('#stock_update_form').on('submit', function(e) {
                let isValid = true;

                // Validate Product
                if ($('#product').val() === '') {
                    $('#ProductError').text('Product is required').show();
                    isValid = false;
                } else {
                    $('#ProductError').hide();
                }

                // Validate Quantity
                if ($('#deduct_product_quantity').val() === '') {
                    $('#quantityError').text('Quantity is required').show();
                    isValid = false;
                }else{
                    $('#quantityError').hide();
                }

                // Validate Reason
                if ($('#reason').val() === '') {
                    $('#reasonError').text('Reason is required').show();
                    isValid = false;
                }else{
                    $('#reasonError').hide();   
                }

                if (!isValid) {
                    e.preventDefault();
                }
            });


            $(".getProducts").select2({
                placeholder: "Search Product",
                dropdownParent: $('#report_modal')
            });

        })



        var dataTable = $('#stock_table').DataTable({
            "bFilter": true,
            "sDom": 'fBtlpi',
            "ordering": true,
            "language": {
                search: ' ',
                sLengthMenu: '_MENU_',
                searchPlaceholder: "Search",
                info: "_START_ - _END_ of _TOTAL_ items",
                paginate: {
                    next: ' <i class=" fa fa-angle-right"></i>',
                    previous: '<i class="fa fa-angle-left"></i> '
                },
            },
            initComplete: (settings, json) => {
                $('.dataTables_filter').appendTo('#tableSearch');
                $('.dataTables_filter').appendTo('.search-input');
            },
            "iDisplayLength": 50,
            processing: true,
            serverSide: true,
            'ajax': {
                type: 'POST',
                url: "{{ route('location.get_stocks') }}",
                'data': function(data) {

                    var token = "{{ csrf_token() }}";
                    var stock_location = $('#stock_location').val();

                    data._token = token;
                    data.stock_location = stock_location;
                }
            },
            columns: [{
                    data: 'DT_RowIndex'
                },
                {
                    data: 'product_code'
                },
                {
                    data: 'category_name'
                },
                {
                    data: 'product_name'
                },
                {
                    data: 'stock_quantity'
                },
                {
                    data: 'qty'
                },
            ]
        });

        function filter_options() {
            dataTable.draw();
        }

        function confirm_msg(ev) {
            ev.preventDefault();

            var urlToRedirect = ev.currentTarget.getAttribute('href');

            Swal.fire({
                title: "Are you sure?",
                text: "You want to delete it!",
                type: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
                confirmButtonClass: "btn btn-success",
                cancelButtonClass: "btn btn-danger ml-1",
                buttonsStyling: !1,
                icon: "warning",
            }).then(function(t) {

                if (t.value) {
                    window.location.href = urlToRedirect;
                }
            });
        }
    </script>
@endsection
