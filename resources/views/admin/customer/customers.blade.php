@extends('admin.layouts.app')
@section('title',config('constants.site_title').' | Customers')
@section('contents')
<div class="content">
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4>Customers</h4>
                <h6>Manage Customers</h6>
            </div>
        </div>
        <ul class="table-top-head">
            <li>
                <a href="{{ route('admin.customers') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i data-feather="rotate-ccw" class="feather-rotate-ccw"></i></a>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i data-feather="chevron-up" class="feather-chevron-up"></i></a>
            </li>
        </ul>
        <div class="page-btn">
            {{-- {{ route('admin.add_customer') }} --}}
            <a href="javascript:void(0)"  data-bs-toggle="modal" data-bs-target="#add_customer_modal" class="btn btn-added"><i data-feather="plus-circle" class="me-2"></i>Create Customer</a>
        </div>
    </div>
    <div class="card table-list-card">
        <div class="card-body">
            <div class="table-top">
                <div class="search-set">
                    <div class="search-input">
                        <a href="javascript:void(0);" class="btn btn-searchset"><i data-feather="search" class="feather-search"></i></a>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table id="customer_table" class="table" style="width: 100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Salesperson</th>
                            <th>Total Spend</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add_customer_modal">
    <div class="modal-dialog modal-dialog-centered custom-modal-two" style="max-width: 1000px">
        <div class="modal-content">
            <div class="page-wrapper-new p-0">
                <div class="content">
                    <div class="modal-header border-0 custom-modal-header">
                        <div class="page-title">
                            <h4>Create Customer</h4>
                        </div>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body custom-modal-body">
                        @include('admin.customer.add_customer')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="edit_customer_modal">
    <div class="modal-dialog modal-dialog-centered custom-modal-two" style="max-width: 1000px">
        <div class="modal-content">
            <div class="page-wrapper-new p-0">
                <div class="content">
                    <div class="modal-header border-0 custom-modal-header">
                        <div class="page-title">
                            <h4>Edit Customer</h4>
                        </div>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body custom-modal-body">
                        <form id="edit_customer_form" action="{{ route('admin.edit_customer') }}"  method="post" enctype="multipart/form-data">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="customer_trans" data-bs-backdrop="static" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 1000px ">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Transactions</h3>
                <button type="button" class="btn-close" id="close_personal_SalaryReport" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body" id="customer_trans_cont">
                <div class="table-responsive">
                    <table id="transactionTable" class="table" style="width: 100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Receipt #</th>
                                <th>Timestamp</th>
                                <th>Location Name</th>
                                <th>Salesperson</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
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
                        {{-- <div class="input-block d-flex " style="width: 45%">
                            <div class="flex-grow-1">
                                <select name="sale_customer" class="form-control selectbox_customer_v selectbox_customer"> </select>
                            </div>
                            @if ($errors->has('sale_customer'))
                                <small class="text-danger">{{ $errors->first('sale_customer') }}</small>
                            @endif
                            <div id="sale_customer_error"></div>
                            <button type="button" id="Add_New_Customer" class="btn btn-primary" style="min-height:0; height: 40px;" >Add Customer</button>
                        </div> --}}

                        <div class="row commision_section align-items-center">
                            <div id="sale_customer_error"></div>
                            <div class="commision_heading col-6">
                                <h5>Customer </h5>
                            </div>
                        </div>
                        <div class="" style="margin: 20px 0">
                            <table name="sale_customer"
                                class="table table-bordered selectbox_customer_v selectbox_customer"
                                id="customer_selectbox"></table>
                        </div>

                        <div class="row commision_section align-items-center">
                            <div class="commision_heading col-6">
                                <h5>Commission</h5>
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
        </div>
    </div>
</div>
@endsection
@section('custom_script')
<script>
var dataTable =	$('#customer_table').DataTable({
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
        initComplete: (settings, json)=>{
            $('.dataTables_filter').appendTo('#tableSearch');
            $('.dataTables_filter').appendTo('.search-input');
		},	
	    processing: true,
	    serverSide: true,
	    'ajax': {
	          type : 'POST',
	          url : "{{ route('admin.get_customers') }}",
	          'data': function(data){

	              var token = "{{ csrf_token() }}";
	              data._token = token;

	          }
	    },
	    columns: [
	        {data: 'DT_RowIndex'},
	        {data: 'customer_name'},
			{data: 'customer_email'},
            {data: 'customer_phone'},
            {data: 'seller'},
            {data: 'total_spend'},
			{data: 'action',orderable: false, searchable: false}
	    ]
});

function edit_customer(customer_id) {
    var csrf = "{{ csrf_token() }}";

    $.ajax({
        url: "{{ route('admin.view_edit_customer') }}",
        type: "post",
        data: "customer_id=" + customer_id + '&_token=' + csrf,
        success: function(data2) {

            $('#edit_customer_modal').modal('show');
            $("#edit_customer_form").html(data2);
        }
    });
}


function confirm_msg(ev)
{
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
    }).then(function (t) {
            
        if(t.value)
        {
            window.location.href = urlToRedirect;
        }
    });
}
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

$(document).on('click','.customer_trans',function(){
    var id = $(this).data('id');
    $.ajax({
        url:"{{ route('admin.customer_transactions', '') }}/" + id,
        type:"GET",
        data:{id},
        success:function(response){
             $('#transactionTable').DataTable().clear().destroy();
            // Initialize DataTable with AJAX call
            $('#transactionTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('admin/customer_transactions') }}/" + id, 
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false }, 
                    { data: 'sale_invoice_no', name: 'sale_invoice_no' },
                    { data: 'sale_date', name: 'sale_date' },
                    { data: 'location_name', name: 'location_name' },
                    { data: 'sellers', name: 'sellers' },
                    { data: 'sale_grand_total', name: 'sale_grand_total' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
            $('#customer_trans').modal('show');
        },
    })
});

$(document).on('click', ".sale_action", function() {
            let sale_id = $(this).data('id');
            $.ajax({

                url: "{{ route('admin.update_get_sales', 'id') }}",
                type: 'GET',
                data: {
                    sale_id: sale_id
                },
                success: function(response) {

                    if (response.status == 'success') {

                        // ---- Customer ----

                        let selectBox = $('table.selectbox_customer_v');
                        selectBox.empty();

                        if (response.customers.length > 0) {;
                            response.customers.forEach(function(customer) {
                                if(response.sale.sale_customer == customer.customer_id){
                                    console.log(customer);
                                    selectBox.append('<tbody><tr><td>' + customer.customer_name +'</td></tr></tbody>');
                                }
                                
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
                            paymentDetails.push('Cash');
                        }
                        if (sale.sale_pay_cc_transaction != null) {
                            paymentDetails.push(sale.sale_pay_cc_transaction + ' - ' + sale
                                .sale_pay_cc_last_no);
                        }
                        if (sale.sale_pay_pa_transaction != null) {
                            paymentDetails.push(sale.sale_pay_pa_transaction);
                        }
                        if (sale.sale_pay_check_transaction != null && sale.sale_pay_check_no != null) {

                            paymentDetails.push(sale.sale_pay_check_transaction + ' - ' + sale
                                .sale_pay_check_no);

                        } else {
                            paymentDetails.push(sale.sale_pay_check_no);
                        }

                        // ---paymentAmount
                        if (sale.sale_pay_camount != null) {
                            paymentAmount.push('$' + sale.sale_pay_camount);
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
                        $('#Receipt_Information').modal('show');

                        // var paymentMethodsList = paymentMethods.join(', ');
                        // var paymentDetailList = paymentDetails.join(', ');
                        // var paymentAmountList = paymentAmount.join(', ');

                        // PayemntHTML += `                                
                    //             <tr>
                    //                 <td>${paymentMethodsList}</td>
                    //                 <td>${paymentDetailList}</td>
                    //                 <td>${paymentAmountList}</td>
                    //             </tr>
                    //         `

                        // $('#payment_data').html(PayemntHTML);


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
</script>
@endsection