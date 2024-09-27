@extends('location.layouts.app')
@section('title', config('constants.site_title') . ' | Summery')
@section('contents')

<div class="receipt-container mt-5" id="print-receipt" style="width: 375px; display: none">
    <div class="modal-body">
        <div class="info text-start">
            <h5>Day-End Report: <span id="location_name"></span> </h5>  
        </div>
        @php
            $today = Carbon\Carbon::now()->format('m-d-Y');
            // $print_date = Carbon\Carbon::now()->format('m-d-Y g:i A');
        @endphp
        <div class="info text-start" >
            <div class="tax-invoice">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="invoice-user-name" style="margin-top: 10px;">
                            <span>Date: </span><span id="today">{{$today}}</span>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12">
                        <div class="invoice-user-name" style="margin-top: 0px;">
                            <span>Closed By: </span><span id="user_name"></span>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12" style="border-top: 1px dashed #5B6670;">
                        <div class="invoice-user-name" style="margin-top: 10px;">
                            <span>Payment Methods: </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="info text-start" >
            <div class="tax-invoice">
                <div class="row">
                    <div class="col-sm-12 col-md-12" style="border-top: 1px dashed #5B6670;"> 
                        <div class="invoice-user-name" style="margin-top: 10px;">
                            <strong>Net: </strong><strong id="net">$</strong>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12">
                        <div class="invoice-user-name" style="margin-top: 0px;">
                            <strong>Tax: </strong><strong id="tax">$</strong>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12">
                        <div class="invoice-user-name" style="margin-top: 0px;">
                            <strong>Gross: </strong><strong id="gross">$</strong>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-12 " >
                        <div class="invoice-user-name" style="margin-top: 10px;">
                            <strong>Cash: <strong id="Cash">$</strong></strong>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12">
                        <div class="invoice-user-name" style="margin-top: 0px;">
                            <strong>Credit Cards: <strong id="credit_card">$</strong></strong>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12">
                        <div class="invoice-user-name" style="margin-top: 0px;">
                            <strong>Checks: <strong id="check">$</strong></strong>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12">
                        <div class="invoice-user-name" style="margin-top: 0px;">
                            <strong>Payment Apps: <strong id="payment_app">$</strong></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="info text-start" style="margin-top: 10px;">
            <div class="tax-invoice">
                <div class="row">
                    <div class="col-sm-12 col-md-12"  style="border-top: 1px dashed #5B6670;">
                        <div class="invoice-user-name" style="margin-top: 5px;">
                            <span>Printed: <span id="printed"></span> </span>
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
            var currentDateTime = new Date().toLocaleString('en-US', { timeZone: userTimeZone });
            $("#printed").text(currentDateTime);
    
            Swal.fire({
                title: "Confirm",
                text: "Are you sure you want to print end of day summary? Alert will be sent to manager(s)",
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

                            let csrf = "{{ csrf_token() }}";
                            $.ajax({
                                url: "{{ route('location.summery_requert') }}",
                                type: "POST",
                                data: {
                                    
                                    currentDateTime:currentDateTime,
                                    _token: csrf,                      
                                },                    
                                success: function(response) {
                                  
                                    if (response.status == "success") {

                                        Swal.fire({
                                            title: "Good job!",
                                            text: "Report was succesfully Send To Manager(s)",
                                            icon: "success"
                                        });

                                        $("#print-receipt").show();

                                        let user = response.user;
                                        let sales = response.sales;

                                        $("#location_name").text(response.location.location_name);
                                        $("#user_name").text(user.user_name);
                                        $("#net").text('$'+parseFloat(sales.total_sales || 0).toFixed(2));
                                        $("#tax").text('$'+parseFloat(sales.total_tax || 0).toFixed(2));
                                        $("#gross").text('$'+parseFloat(response.gross_amount || 0).toFixed(2));
                                        
                                        $("#Cash").text('$'+parseFloat(sales.total_cash_amount || 0).toFixed(2));
                                        $("#credit_card").text('$'+parseFloat(sales.total_craditCard_amount || 0).toFixed(2));
                                        $("#check").text('$'+parseFloat(sales.total_check_amount || 0).toFixed(2));
                                        $("#payment_app").text('$'+parseFloat(sales.total_payment_amount || 0).toFixed(2));                                     
                                    }
                                },
                            });
                        } else{
                            
                            $(document).ready(function() {
                                var referrer =  document.referrer;
                                window.location.replace(referrer);

                            });
                        }
                       
            })

        
    </script>
@endsection