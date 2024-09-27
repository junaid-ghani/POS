@extends('location.layouts.app')
@section('title', config('constants.site_title') . ' | Sales')
@section('contents')
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Sales</h4>
                    <h6>Manage Sales</h6>
                </div>
            </div>
            <ul class="table-top-head">
                <li>
                    <a href="{{ route('location.sales') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i
                            data-feather="rotate-ccw" class="feather-rotate-ccw"></i></a>
                </li>
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i
                            data-feather="chevron-up" class="feather-chevron-up"></i></a>
                </li>
            </ul>
        </div>

        <div class="card table-list-card">
            {{-- @dd($user); --}}
            <div class="card-body card_header" data-user-role="{{ $user->user_role }}">

                @if ($user->user_role != 4)

                    <div class="table-top">

                        <div class="search-set">
                            <div class="search-input">
                                <a href="javascript:void(0);" class="btn btn-searchset"><i data-feather="search"
                                        class="feather-search"></i></a>
                            </div>
                        </div>


                        <div class="card mb-0" id="filter_inputs" style="display:block; width: 520px;">
                            <div class="card-body pb-0">

                                <div class="row">
                                    <div class="col-lg-4 col-sm-6 col-12 p-0">
                                        <div class="input-blocks  mb-0">
                                            <i data-feather="home" class="info-img"></i>

                                            {{-- <select name="location_id" id="location_id" class="form-control selectbox_location" >
                                                <option selected value="{{$locations->location_id}}">{{$locations->location_name}}</option>
                                            </select> --}}
                                            <select name="location_id" id="location_id"
                                                class="form-control selectbox_location" onchange="filter_options()">

                                                <!--<option value="" > Location</option>-->
                                                @php
                                                    $location_id = Auth::guard('location')->id();
                                                @endphp

                                                <option selected value="all"> All Locations</option>
                                                @foreach ($locations as $location)
                                                    <option @if ($location->location_id == $location_id) selected @endif
                                                        value="{{ $location->location_id }}">
                                                        {{ $location->location_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-8 col-sm-6 col-12">
                                        <div id="reportrange"
                                            style=" cursor: pointer; padding: 8.5px 10px; border: 1px solid #dbe0e6; width: 106%; color:#5B6670; border-radius:5px;">
                                            <i class="fa fa-calendar"></i>&nbsp;
                                            <span></span> <i class="fa fa-caret-down"></i>
                                        </div>
                                    </div>
                                    <input type="hidden" value="{{ $today }}" id="ini_start_date">
                                    <input type="hidden" value="{{ $today }}" id="ini_end_date">

                                </div>

                            </div>
                        </div>
                    </div>

                @endif
                <div class="table-responsive">
                    <table id="sale_table" class="table" style="width: 100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Receipt #</th>
                                <th>Timestamp</th>
                                <!--<th>Customer</th>-->
                                <th>Location Name</th>
                                <th>Salesperson</th>
                                {{-- <th>Subtotal</th> --}}
                                {{-- <th>Tax</th> --}}
                                <th>Total</th>
                                <!--<th>Created By</th>-->
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>


                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="Receipt_Information" aria-labelledby="receiptModalLabel" aria-hidden="true"
            data-bs-backdrop="static">
            <div class="modal-dialog" style="max-width: 1000px ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="receiptModalLabel" style="margin: auto 390px">Receipt Information</h5>
                        <button type="button" id="receiptClose" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row receipt_info">
                            <div class="col-6">
                                <h4>Receipt # <span id="receipt_no"></span></h4>
                                <h4>Location: <span id="location"></span> </h4>
                            </div>
                            <div class="col-6">
                                <h4 style="text-align:end;" id="date_time"></h4>
                            </div>
                        </div>

                        <div class="row sale_detail_sec mt-4 p-4">

                            <div class="col-6">
                                <div class="row align-items-center">
                                    <div class="col-12">
                                        <h5>Products</h5>
                                    </div>

                                </div>

                                <table class="table table-bordered product_table mt-3">
                                    <thead class="text-center">
                                        <th>Product Name</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Subtotal</th>
                                    </thead>
                                    <tbody class="text-center" id="product_data">

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-end">Discounts</th>
                                            <th class="text-center" id="discount_text"></th>
                                        </tr>
                                        <tr>
                                            <th colspan="3" class="text-end">Subtotal</th>
                                            <th class="text-center" id="subtotal_text"></th>
                                        </tr>
                                        <tr>
                                            <th colspan="3" class="text-end">Tax</th>
                                            <th class="text-center" id="tax_text"></th>
                                        </tr>
                                        <tr>
                                            <th colspan="3" class="text-end">Total</th>
                                            <th class="text-center" id="total_text"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="col-6">
                                <div class="row commision_section align-items-center">
                                    <div id="sale_customer_error"></div>
                                    <div class="commision_heading col-6">
                                        <h5>Customer </h5>
                                    </div>
                                    <div class="commision_button col-6 text-end">
                                        <div id="">
                                            <button type="button" id="cancel_update" class="btn btn-secondary" style="padding:5px 25px; display: none;">Cancel</button>
                                            <button type="button" id="Update_Customer" class="btn btn-primary" style="padding:5px 25px; display:none">Update</button>                                            
                                            <button type="button" id="Add_New_Customer" class="btn btn-primary" style="padding:5px 25px;">Add</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="" style="margin: 20px 0">
                                    <select name="update_sale_customer" class="form-control selectbox_customer_v selectbox_customer" id="customer_selectbox"> </select>
                                </div>

                                <div class="row commision_section align-items-center">
                                    <div class="commision_heading col-6">
                                        <h5>Commission</h5>
                                    </div>
                                    <div class="commision_button col-6 text-end">
                                        @if ($user->user_role != 4)
                                        <div id="edit_btn_div">
                                            <button type="button" id="commission_edit" class="btn btn-primary"
                                                style="padding:5px 25px;">Edit </button>
                                        </div>
                                        @endif
                                        <div id="update_btn_div" style="display: none">
                                            <button type="button" id="cancel_edit" class="btn btn-secondary"
                                                style="padding:5px 25px;">Cancel </button>
                                            <button type="button" id="commission_update" class="btn btn-primary"
                                                style="padding:5px 25px;">Update </button>
                                            <input type="hidden" id="commission_update_id" value="">
                                        </div>
                                    </div>

                                </div>
                                <span id="comMsg" class="d-none text-danger"></span>
                                <table class="table table-bordered commission_table mt-3">
                                    <thead class="text-center">
                                        <th>Salesperson</th>
                                        <th>%</th>
                                        <th>Amount</th>
                                    </thead>
                                    <tbody class="text-center seller_data">

                                    </tbody>
                                </table>

                                <form action="" method="post" id="update_commission"
                                    style="display: none; overflow-x:visible">
                                    @csrf
                                    <table class="table table-bordered mt-3">
                                        <thead class="text-center">
                                            <th>Salesperson</th>
                                            <th>%</th>
                                            <th>Amount</th>
                                        </thead>
                                        <tbody>

                                            <tr>
                                                <td colspan="3">
                                                    <span style="display: none;" id="sub_total_text"></span>
                                                    <div class="flex-grow-1">

                                                        <details class="multiple-select">
                                                            <summary>Salesperson</summary>
                                                            <div class="multiple-select-dropdown">
                                                                <div class="custom_search">
                                                                    <input type="text" class="custom_search_field"
                                                                        placeholder="Search Salesperson">
                                                                </div>

                                                            </div>
                                                        </details>
                                                        <div id="selected-values" class="selected-values"></div>
                                                        <small id="seller_error" class="text-danger"
                                                            style="display: none;">Please Select Seller</small>

                                                        <input type="hidden" name="sale_sellers" id="selectedValues">
                                                        <input type="hidden" name="commission" value=""
                                                            id="selectedCommission">
                                                        <input type="hidden" name="amount" value=""
                                                            id="selectedAmount">

                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot class="">

                                        </tfoot>
                                    </table>
                                </form>

                                <div class="row payment_section mt-3">
                                    <div class="payment_heading col-12">
                                        <h5>Payment Methods</h5>
                                    </div>
                                </div>

                                <table class="table table-bordered payment_table mt-3">
                                    <thead class="text-center">
                                        <th>Method</th>
                                        <th>Details</th>
                                        <th>Amount</th>
                                    </thead>
                                    <tbody id="payment_data" class="text-center">

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                    <hr class="m-0">
                    <div class="modal-footer">
                        <div class="col-12 col-sm-12 d-flex justify-content-between">
                            @if ($user->user_role != 4)
                                <form id="deleteSaleForm" method="POST" action="">
                                    @csrf
                                    @method('DELETE')
                                    <div class="input-block">
                                        <input type="hidden" id="deleteAndUpdate" name="deleteAndUpdate"
                                            value="">
                                        <button type="button" id="delete_sale" class="btn btn-primary">Delete
                                            Receipt</button>

                                    </div>
                                </form>
                            @endif
                            <div class="input-block">
                                <button type="submit" name="submit" class="btn btn-primary submit_button">Print
                                    Receipt</button>
                            </div>
                            <div class="input-block">
                                <button id="send_email_button" type="button" class="btn btn-primary"> Email
                                    Receipt</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add New Customer Modal -->
        <div class="modal fade custom_modal" id="Add_New_Customer_modal" aria-hidden="true" data-bs-backdrop="static">
            <div class="modal-dialog custom_modal_content">
                <div class="modal-content">
                    <div class="modal-header custom_modal_header mb-3 d-flex justify-content-between align-items-center">
                        <div class="custom_modal_heading">
                            <h5 class="modal-title">Add Customer</h5>
                        </div>
                        <div class="custom_modal_close">
                            <button type="button" id="closeModal" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                    </div>
                    <div class="alert alert-danger" style="display:none; margin:10px;" id="customerError"></div>
                    <div class="modal-body">
                        <form id="addNewCustomer">
                            <div class="mb-3">
                                <label for="customerName" class="form-label">Customer Name</label>
                                <input type="text" class="form-control" id="customerName" name="customer_name">
                            </div>
                            <div class="mb-3">
                                <label for="customerEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="customerEmail" name="customer_email">
                            </div>
                            <div class="mb-3">
                                <label for="customerPhone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="customerPhone" name="customer_phone">
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

        <!-- Send PrintReceipt Email Modal  -->

        <div class="modal fade" id="send_email_copy" aria-labelledby="sendEmailModalLabel" aria-hidden="true"
            data-bs-backdrop="static">
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
                                <input type="hidden" id="sendEmail" name="sale_id">

                                <label for="email_text" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email_text" name="send_email">
                                <small id="email_error" class="text-danger"></small>
                            </div>
                            <button type="submit" name="submit" value="email_receipt"
                                class="btn btn-primary email_submit_button">Email Receipt</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End PrintReceipt Email modal -->



    </div>
@endsection
@section('custom_script')


    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script type="text/javascript">
        function formatDate(timestamp) {
            var date = new Date(timestamp);
            return date.toLocaleString('en-US', {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });
        }


        let previousValue = null;
        $(document).on('click', ".sale_action", function() {

            let sale_id = $(this).data('saleid');

            $.ajax({

                url: "{{ route('location.update_get_sales', 'id') }}",
                type: 'GET',
                data: {
                    sale_id: sale_id
                },
                success: function(response) {


                    if (response.status == 'success') {

                        // ---- Customer ----

                        let selectBox = $('select.selectbox_customer_v');
                        selectBox.empty();

                        if (response.customers.length > 0) {
                            selectBox.append('<option value="">Walk-in Customer</option>');

                            response.customers.forEach(function(customer) {
                                var sale_customer = (response.sale.sale_customer == customer
                                    .customer_id) ? 'selected' : '';
                                selectBox.append('<option ' + sale_customer + ' value="' +
                                    customer.customer_id + '">' + customer.customer_name +
                                    '</option>');

                                previousValue = $('#customer_selectbox').val();
                            });

                        } else {
                            $('#customerError').text('No customers found').show();
                        }

                        var PayemntHTML = '';

                        let sale = response.sale;
                        
                        setTimeout(() => {

                            $(document).find('#delete_sale').attr('sale_delete_id', sale
                                .sale_id);
                            $(document).find('#sendEmail').val(sale.sale_id);
                            $(document).find('#commission_update_id').val(sale.sale_id);


                        }, 500);

                        $("#receipt_no").text(sale.sale_invoice_no);
                        $("#location").text(response.location.location_name);
                        let saleAddedOn = sale.sale_added_on;
                        let formattedDate = formatDate(saleAddedOn);
                        $("#date_time").text(formattedDate);


                        // --- Products ---

                        var saleItemsHTML = '';
                        response.saleItems.forEach(function(saleItems) {
                            saleItemsHTML += `                                
                                        <tr>
                                            <td>${saleItems.get_product.product_name}</td>
                                            <td>$${saleItems.sale_item_price}</td>
                                            <td>${saleItems.sale_item_quantity}</td>
                                            <td>$${saleItems.sale_item_total}</td>
                                        </tr>
                                    `
                        });
                        $('#product_data').html(saleItemsHTML);

                        $("#discount_text").text('$' + sale.sale_discount_amount);
                        $("#subtotal_text").text('$' + sale.sale_sub_total);
                        $("#tax_text").text('$' + sale.sale_tax_amount);
                        $("#total_text").text('$' + sale.sale_grand_total);


                        // --- Commission ---

                        var all_sellerHTML = '';
                        response.seller.forEach(function(seller) {

                            var isChecked = response.commission.some(function(commission) {
                                return commission.seller_id === seller.user_id;
                            })

                            var check_element = isChecked ? 'checked' : '';
                            var check_elementClass = isChecked ? 'selected_seller' : '';

                            all_sellerHTML += `
                                <label>
                                    <input type="checkbox" class="saller_value ${check_elementClass} " ${check_element} hidden name="select" value="${ seller.user_id }">
                                    <span class="content" data-seller="${seller.user_name }">${seller.user_name }</span>
                                </label>
                            `;

                        });
                        $('.multiple-select-dropdown').append(all_sellerHTML);


                        var sellerHTML = '';
                        response.commission.forEach(function(commission) {
                            var sellerName = commission.seller ? commission.seller.user_name :
                                'Seller not found';
                            sellerHTML += `                                
                                        <tr>
                                            <td>${sellerName}</td>
                                            <td>${commission.commission}</td>
                                            <td>$${commission.amount}</td>
                                        </tr>
                                    `;
                        });
                        $('.seller_data').html(sellerHTML);

                        var updateSellerHTML = '';
                        var sellerIds = [];
                        response.commission.forEach(function(commission) {
                            var sellerName = commission.seller ? commission.seller.user_name :
                                'Seller not found';

                            var sellerId = commission.seller ? commission.seller.user_id : null;

                            if (sellerId) {
                                sellerIds.push(`"${sellerId}"`);
                            }
                            updateSellerHTML += `                                
                                        <div class="selected-value" data-value="${sellerId}">
                                            <button class="remove-value"> <i class="fa-solid fa-xmark fa-fw"></i> </button>
                                            <span class="seller-name">${sellerName}</span>
                                            <span class="percentage" contenteditable="true">${commission.commission}%</span>
                                            <span class="sub_total_CSS">$${commission.amount}</span>
                                        </div>                                      
                                    `;
                        });

                        var sellerIdsValue = `[${sellerIds.join(",")}]`;
                        $('#selectedValues').val(sellerIdsValue);
                        $('#selected-values').html(updateSellerHTML);

                        // --- Payemnt Methods ---

                        var paymentMethods = [];
                        var paymentDetails = [];
                        var paymentAmount = [];

                        // ---paymentMethods
                        if (sale.sale_pay_cash == 1) {
                            paymentMethods.push('Cash');
                        }
                        if (sale.sale_pay_ccard == 1) {
                            paymentMethods.push('Credit Card');
                        }
                        if (sale.sale_pay_payment_app == 1) {
                            paymentMethods.push('Payment App');
                        }
                        if (sale.sale_pay_check == 1) {
                            paymentMethods.push('Check');
                        }

                        // ---paymentDetails
                        if (sale.sale_pay_camount != null) {

                            paymentDetails.push('Tendered: $' + sale.sale_pay_camount);
                        }
                        if (sale.sale_pay_cc_transaction != null) {

                            if (sale.sale_pay_cc_last_no == null) {
                                paymentDetails.push(sale.sale_pay_cc_transaction);    
                            }
                            paymentDetails.push(sale.sale_pay_cc_transaction + ' - ' + sale.sale_pay_cc_last_no);
                        }
                        if (sale.sale_pay_pa_transaction != null) {

                            paymentDetails.push(sale.sale_pay_pa_transaction);
                        }
                        if (sale.sale_pay_check_transaction != null && sale.sale_pay_check_no != null) {

                            paymentDetails.push(sale.sale_pay_check_transaction + ' - ' + sale.sale_pay_check_no);

                        } else {
                            paymentDetails.push(sale.sale_pay_check_no);
                        }

                        // ---paymentAmount
                        if (sale.sale_pay_camount != null) {
                            let totalCash = sale.sale_pay_camount - sale.change_amount;
                            paymentAmount.push('$' + totalCash);
                            // paymentAmount.push('$' + sale.sale_pay_camount);
                        }
                        if (sale.sale_pay_ccamount != null) {
                            paymentAmount.push('$' + sale.sale_pay_ccamount);
                        }
                        if (sale.sale_pay_pa_amount != null) {
                            paymentAmount.push('$' + sale.sale_pay_pa_amount);
                        }
                        if (sale.sale_pay_ckamount != null) {
                            paymentAmount.push('$' + sale.sale_pay_ckamount);
                        }

                        var rowCount = paymentMethods.length;

                        for (var i = 0; i < rowCount; i++) {
                            PayemntHTML += '<tr>';
                            PayemntHTML += '<td>' + paymentMethods[i] + '</td>';
                            PayemntHTML += '<td>' + paymentDetails[i] + '</td>';
                            PayemntHTML += '<td>' + paymentAmount[i] + '</td>';
                            PayemntHTML += '</tr>';
                        }

                        $('#payment_data').html(PayemntHTML);


                    } else {
                        console.log("No Record Found");

                    }
                },
                complete: function() {
                    $('.selected_seller').parent().hide();
                },
                error: function(xhr, status, error) {
                    console.error('An error occurred:', error);
                }
            })

        });

        // --- add new customer modal ----

        var customer_modal = $('#Add_New_Customer_modal');

        $('#Add_New_Customer').on('click', function() {
            customer_modal.modal('show');
        });

        $('#closeModal').on('click', function() {
            customer_modal.modal('hide');
            customer_modal.find("input").val('');
            $('#customer_seller').val(null).trigger('change');
           
        });

        $('#receiptClose').on('click', function() {

            $(document).find(".selectbox_customer").val(null).trigger('change');;
            $("#Update_Customer").hide();
            $("#Add_New_Customer").show();
            $("#cancel_update").hide();

        });
        $(document).on("change", "#customer_selectbox", function() {

            $("#Add_New_Customer").hide();
            $("#Update_Customer").show();
            $("#cancel_update").show();
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
                        customer_modal.modal('hide');
                        customer_modal.find("input").val('');
                        $('#customer_seller').val(null).trigger('change');

                        // Append newly added customer to the select bo
                        let selectBox = $('select.selectbox_customer_v');
                        selectBox.append('<option value="' + response.customer_id + '">' + response
                            .customer_name + '</option>');

                        // Select the new customer in the dropdown
                        selectBox.val(response.customer_id).trigger('change');

                    }
                },
                complete: function() {
                    // $("#Update_Customer").hide();
                    // $("#Add_New_Customer").show();
                    // $("#cancel_update").hide();
                    
                    $('#addCustomer').text('Add Customer').attr('disabled', false);
                }

            });
        });

        $('#Update_Customer').on("click", function(e) {

            var csrf = "{{ csrf_token() }}";
            let customer_id = $(".selectbox_customer_v").val();
            let sale_id = $("#commission_update_id").val();

            $.ajax({
                url: "{{ route('location.update_sale_customer') }}",
                type: "POST",
                data: {
                    _token: csrf,
                    sale_id: sale_id,
                    customer_id: customer_id,
                },
                beforeSend: function() {
                    $('#Update_Customer').text('Updating').attr('disabled', true);
                },
                success: function(response) {

                    if (response.status == 'success') {

                        $('#Update_Customer').text('Update').removeAttr('disabled');

                        // Append newly added customer to the select bo
                        let selectBox = $('select.selectbox_customer_v');
                        // selectBox.append('<option value="' + response.customer_id + '">' + response.customer_name + '</option>');

                        // Select the new customer in the dropdown
                        selectBox.val(response.customer_id).trigger('change');

                        localStorage.setItem("customer_id",response.customer_id);

                        previousValue = $('#customer_selectbox').val();
                        
                    }
                },
                complete: function() {
                    setTimeout(() => {
                        $("#Update_Customer").hide();
                        $("#cancel_update").hide();
                        $("#Add_New_Customer").show();
                    }, 200);
                }

            });
        });

                
        $('#cancel_update').on("click", function(e) {
            if (previousValue) {
                $('#customer_selectbox').val(previousValue).trigger('change');
            } else {
                $('#customer_selectbox').val(null).trigger('change');
            }
            $(this).hide();
            $("#Update_Customer").hide();
            $("#Add_New_Customer").show();                 
        })
        // --- end Add customer modal ----


        //  ---- Email Modal ----

        $("#send_email_button").click(function() {

            $("#send_email_copy").modal('show');
        })

        //  ---- Send Receipt ----

        $('.email_submit_button').click(function(e) {

            var send_email = $('#email_text').val();
            var email_error = $("#email_error");

            const emailPattern2 = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (send_email == '') {
                email_error.text('Please Enter Email').show();
                e.preventDefault();
            } else if (!emailPattern2.test(send_email)) {
                email_error.text('Please Enter a valid Email Address.').show();
                e.preventDefault();
            } else {
                email_error.hide();
            }
        });

        // ------ Delete Sale ------

        $(document).on('click', '#delete_sale', function() {

            var saleId = $(this).attr('sale_delete_id').trim();

            Swal.fire({
                title: "Delete Receipt",
                text: "Are you sure you want to delete this receipt?",
                type: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                confirmButtonClass: "btn btn-primary",
                cancelButtonClass: "btn btn-danger ml-1",
                buttonsStyling: !1,
            }).then(function(res) {

                if (res.isConfirmed) {

                    Swal.fire({
                        title: "Update Stock",
                        text: "Would you like to return items to stock?",
                        type: "warning",
                        showCancelButton: !0,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes",
                        cancelButtonText: "No",
                        confirmButtonClass: "btn btn-primary",
                        cancelButtonClass: "btn btn-danger ml-1",
                        buttonsStyling: !1,
                    }).then(function(result) {

                        if (result.isConfirmed) {
                            setTimeout(() => {

                                var deleteSaleUrl =
                                    "{{ route('location.delete_sale', ['id' => ':id']) }}"
                                    .replace(':id', saleId);
                                if (saleId) {
                                    // $('#deleteSaleForm').attr('action', deleteSaleUrl).submit();
                                    $.ajax({
                                        url: deleteSaleUrl,
                                        type: 'DELETE',
                                        data: {
                                            _token: "{{ csrf_token() }}",
                                            saleId: saleId,
                                            deleteAndUpdate: 'Yes'
                                        },
                                        success: function(response) {

                                            if ($.fn.DataTable.isDataTable(
                                                    '#sale_table')) {
                                                var table = $('#sale_table')
                                                    .DataTable();
                                                table.ajax.reload(null, false);
                                            }

                                            if (response.status == "success") {
                                                Swal.fire({
                                                    type: "success",
                                                    title: "Deleted!",
                                                    text: response
                                                        .message,
                                                    confirmButtonClass: "btn btn-primary",
                                                    icon: "success"
                                                })
                                            }
                                            if (response.status == "success2") {
                                                Swal.fire({
                                                    type: "success",
                                                    title: "Deleted!",
                                                    text: response
                                                        .message,
                                                    confirmButtonClass: "btn btn-primary",
                                                    icon: "success"
                                                })
                                            } else {
                                                if (response.status ==
                                                    "error") {
                                                    Swal.fire({
                                                        type: "error",
                                                        title: "Cancelled",
                                                        text: response
                                                            .message,
                                                        confirmButtonClass: "btn btn-primary",
                                                        icon: "error"
                                                    })
                                                }
                                            }

                                            $("#Receipt_Information").modal(
                                                'hide')

                                        },
                                        error: function(xhr) {
                                            console.log('Something went wrong');
                                        }
                                    });

                                }


                            }, 500);
                        } else if (result.isDismissed && result.dismiss === Swal.DismissReason.cancel) {

                            setTimeout(() => {
                                var deleteSaleUrl =
                                    "{{ route('location.delete_sale', ['id' => ':id']) }}"
                                    .replace(':id', saleId);
                                if (saleId) {
                                    // $('#deleteSaleForm').attr('action', deleteSaleUrl).submit();
                                    $.ajax({
                                        url: deleteSaleUrl,
                                        type: 'DELETE',
                                        data: {
                                            _token: "{{ csrf_token() }}",
                                            saleId: saleId,
                                        },
                                        success: function(response) {

                                            if ($.fn.DataTable.isDataTable(
                                                    '#sale_table')) {
                                                var table = $('#sale_table')
                                                    .DataTable();
                                                table.ajax.reload(null,
                                                    false);
                                            }

                                            if (response.status ==
                                                "success") {
                                                Swal.fire({
                                                    type: "success",
                                                    title: "Deleted!",
                                                    text: response
                                                        .message,
                                                    confirmButtonClass: "btn btn-primary",
                                                    icon: "success"
                                                })
                                            }
                                            if (response.status ==
                                                "success2") {
                                                Swal.fire({
                                                    type: "success",
                                                    title: "Deleted!",
                                                    text: response
                                                        .message,
                                                    confirmButtonClass: "btn btn-primary",
                                                    icon: "success"
                                                })
                                            } else {
                                                if (response.status ==
                                                    "error") {
                                                    Swal.fire({
                                                        type: "error",
                                                        title: "Cancelled",
                                                        text: response
                                                            .message,
                                                        confirmButtonClass: "btn btn-primary",
                                                        icon: "error"
                                                    })
                                                }
                                            }

                                            $("#Receipt_Information").modal(
                                                'hide')

                                        },
                                        error: function(xhr) {
                                            console.log(
                                                'Something went wrong');
                                        }
                                    });

                                }
                            }, 500);

                        } else {

                            Swal.fire({
                                title: "Action Cancelled",
                                text: "Sale deletion cancelled.",
                                icon: "info",
                                confirmButtonText: "OK",
                            });

                        }

                    });
                }

            })

        });

        // ---- Edit Commission ----

        $("#commission_edit").click(function() {

            $('#edit_btn_div').hide();
            $('#update_btn_div').show();

            var subtotal = $(document).find("#subtotal_text").text().replace('$', '').trim();
            $("#sub_total_text").text(subtotal);
            $(".commission_table").hide();
            $("#update_commission").show();
        });

        $("#cancel_edit").click(function() {

            $('#edit_btn_div').show();
            $('#update_btn_div').hide();
            $(".commission_table").show();
            $("#update_commission").hide();
        });

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


            var subTotal = parseFloat($("#sub_total_text").text());
            var sellerSelected = $(".selected-value");
            var sellerAmount = 0;

            sellerSelected.each(function(index) {
                if (index === sellerSelected.length - 1) {
                    $(this).find('.sub_total_CSS').text('$' + (subTotal - sellerAmount).toFixed(2));
                } else {
                    let sellertTextAmount = $(this).find('.sub_total_CSS').text().replace('$', '');
                    sellerAmount += parseFloat(sellertTextAmount);
                }
            });

        }

        $(document).on('change', 'input[name="select"]', function() {

            var checkbox = $(this);

            if (checkbox.is(':checked')) {
                var value = checkbox.val();
                var seller_name = checkbox.siblings('.content').text();
                var itemCount = $(".selected-value").length;
                var perc = (100 / (itemCount + 1)).toFixed(2);

                var subTotal = $("#sub_total_text").text();
                // var sellerSubTotal = (subTotal / (itemCount + 1));
                var sellerSubTotal = (subTotal * (perc / 100)).toFixed(2);

                selectedValues.append(createSelectedValueElement(value, seller_name, perc, sellerSubTotal));
                checkbox.closest('label').hide();
                multipleSelect.prop('open', false); // Hide the dropdown
                updateSelectedValueElements();

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

                    } else {
                        var totalItems = $(".selected-value").length;
                        if (newPercentage && !isNaN(newPercentage) && totalItems > 1) {
                            var subTotal = parseFloat($("#sub_total_text").text());

                            var newSubTotal = (subTotal * (newPercentage / 100)).toFixed(2);
                            percentageElement.closest('.selected-value').find('.sub_total_CSS').text('$' +
                                newSubTotal);

                            var newPercValue = parseFloat(newPercentage);
                            percentageElement.text(`${newPercValue.toFixed(2)}%`);

                            updateSelectedValueElements();

                        }
                    }


                }
            }, 1000)


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


        });

        function clearSeller() {

            selectedValues.empty();
            $('input[name="select"]').prop('checked', false).closest('label').show();
            updateHiddenInput();
            updateSelectedValueElements();
        }

        // --- end custom select box ---

        // --- Update Commission ---
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

        function updatedCommissionRecord() {

            let sale_id = $("#commission_update_id").val();

            $.ajax({

                url: "{{ route('location.update_get_sales', 'id') }}",
                type: 'GET',
                data: {
                    sale_id: sale_id
                },

                success: function(response) {

                    if (response.status == 'success') {


                        var sellerHTML = '';
                        response.commission.forEach(function(commission) {
                            var sellerName = commission.seller ? commission
                                .seller.user_name : 'Seller not found';
                            sellerHTML += `                                
                            <tr>
                                <td>${sellerName}</td>
                                <td>${commission.commission}</td>
                                <td>$${commission.amount}</td>
                            </tr>
                        `;
                        });

                        $('.seller_data').html(sellerHTML);

                        var updateSellerHTML = '';
                        var sellerIds = [];
                        response.commission.forEach(function(commission) {
                            var sellerName = commission.seller ? commission
                                .seller.user_name : 'Seller not found';

                            var sellerId = commission.seller ? commission
                                .seller.user_id : null;

                            if (sellerId) {
                                sellerIds.push(`"${sellerId}"`);
                            }
                            updateSellerHTML += `                                
                            <div class="selected-value" data-value="${sellerId}">
                                <button class="remove-value"> <i class="fa-solid fa-xmark fa-fw"></i> </button>
                                <span class="seller-name">${sellerName}</span>
                                <span class="percentage" contenteditable="true">${commission.commission}%</span>
                                <span class="sub_total_CSS">$${commission.amount}</span>
                            </div>                                      
                        `;
                        });

                        var sellerIdsValue = `[${sellerIds.join(",")}]`;
                        $('#selectedValues').val(sellerIdsValue);
                        $('#selected-values').html(updateSellerHTML);

                    } else {
                        console.log("No Record Found");

                    }
                },
                error: function(xhr, status, error) {
                    console.error('An error occurred:', error);
                }
            })
        }

        $("#commission_update").click(function() {

            totalPercentageAmount = 100;
            percentage_value_amount = 0
            $('.percentage').each(function () {
                var percentage_value = parseFloat($(this).text().replace('%', '').trim());
                percentage_value_amount += percentage_value;
            })

            var subtotal = $(document).find("#subtotal_text").text().replace('$', '').trim();
            var checkAmount = 0;
            $('.sub_total_CSS').each(function() {
                var value = parseFloat($(this).text().replace('$', '').trim());
                checkAmount += value;
            });
            if (subtotal == checkAmount && totalPercentageAmount == percentage_value_amount) {
                sellerCommissionPercentage()
                let sale_id = $("#commission_update_id").val();
                let seller = $('#selectedValues').val();
                let commission = $('#selectedCommission').val();
                let amount = $('#selectedAmount').val();

                var csrf = "{{ csrf_token() }}";
                $.ajax({

                    url: "{{ route('location.update_commission', 'id') }}",
                    type: 'POST',
                    data: {
                        sale_id: sale_id,
                        seller: seller,
                        commission: commission,
                        amount: amount,
                        _token: csrf
                    },
                    success: function(response) {

                        $("#Receipt_Information").modal('hide');
                        $("#comMsg").hide();
                    },
                    complete: function() {

                        setTimeout(() => {
                            dataTable.ajax.reload();
                            $("#Receipt_Information").modal('show');
                            updatedCommissionRecord()
                            $('#edit_btn_div').show();
                            $('#update_btn_div').hide();
                            $(".commission_table").show();
                            $("#update_commission").hide();
                        }, 500);

                    },
                    error: function(xhr, status, error) {
                        console.error('An error occurred:', error);
                    }
                })
            } else {
                $('#comMsg').text('Commission is higher or lower than subtotal please correct this').removeClass(
                    'd-none');
                return false;
            }
        })


        //  ------ datepicker -----

        $(function() {

            var start = moment();
            var end = moment();

            function cb(start, end) {
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            }

            $('#reportrange').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')]
                }
            }, cb);

            cb(start, end);

            $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
                let start_date = picker.startDate.format('YYYY-MM-DD');
                let end_date = picker.endDate.format('YYYY-MM-DD');

                $('#ini_start_date').prop("value", start_date);
                $('#ini_end_date').prop("value", end_date);
                dataTable.draw();
            });
        });

        //   datepicker end


        var userRole = $('.card_header').data('user-role');

        var dataTable = $('#sale_table').DataTable({
            "bFilter": userRole != 4,
            "bInfo": userRole != 4,
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
            processing: true,
            serverSide: true,

            'ajax': {
                type: 'POST',
                url: "{{ route('location.get_sales') }}",
                'data': function(data) {

                    var token = "{{ csrf_token() }}";
                    var location_id = $('#location_id').val();
                    var start_date = $('#ini_start_date').val();
                    var end_date = $('#ini_end_date').val();

                    data._token = token;
                    // data.location_id = location_id;
                    data.location_id = (location_id === "all") ? null : location_id;
                    data.start_date = start_date;
                    data.end_date = end_date;
                }
            },
            columns: [{
                    data: 'DT_RowIndex'
                },
                {
                    data: 'sale_invoice_no'
                },
                {
                    data: 'sale_date'
                },
                // {data: 'customer_name'},
                {
                    data: 'location_name'
                },
                {
                    data: 'sellers'
                },
                // {
                //     data: 'sale_sub_total'
                // },
                // {
                //     data: 'sale_tax_amount'
                // },
                {
                    data: 'sale_grand_total'
                },
                // {data: 'user_name'},
                {
                    data: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        function filter_options() {
            dataTable.draw();
        }

        $('#start_date').on('dp.change', function(e) {
            filter_options();
        })
        $('#end_date').on('dp.change', function(e) {
            filter_options();
        })

        // --- select2 ---

        $(".selectbox_location").select2({
            placeholder: "All Location"
        });
        $(".selectbox_customer").select2({
            placeholder: "Walk-in Customer",
        });
        $(".selectbox_seller").select2({
            placeholder: "Search Salesperson"
        });

        function confirm_msg(ev) {
            ev.preventDefault();

            var urlToRedirect = ev.currentTarget.getAttribute('href');

            Swal.fire({
                title: "Are you sure?",
                text: "You want to change status!",
                type: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, change it!",
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
