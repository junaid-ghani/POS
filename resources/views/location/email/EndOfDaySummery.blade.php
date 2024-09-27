{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>End Of Day Summery</title>
    @include('admin.partials.style')   
</head>

<body>
  
<div class="receipt-container mt-5" id="print-receipt" style="width: 375px; display: none">
    <div class="modal-body">
        <div class="info text-start">
            <h5>End of Day Summary: <span id="location_name">{{$summery_data['location_name']}}</span> </h5>  
        </div>
        @php
            $today = Carbon\Carbon::now()->format('m-d-Y');
            $print_date = Carbon\Carbon::now()->format('m-d-Y g:i A');
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
                            <span>Closed By: </span><span id="user_name">{{$summery_data['user_name']}}</span>
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
                            <strong>Net: </strong><strong id="net">${{$summery_data['totalSales'] ?? '0.00'}}</strong>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12">
                        <div class="invoice-user-name" style="margin-top: 0px;">
                            <strong>Tax: </strong><strong id="tax">${{$summery_data['totalTax'] ?? '0.00'}}</strong>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12">
                        <div class="invoice-user-name" style="margin-top: 0px;">
                            <strong>Gross: </strong><strong id="gross">${{$summery_data['gross_amount'] ?? '0.00'}}</strong>
                        </div>
                    </div>

                    <div class="col-sm-12 col-md-12 " >
                        <div class="invoice-user-name" style="margin-top: 10px;">
                            <strong>Cash: <strong id="Cash">${{$summery_data['totalCashAmount'] ?? '0.00'}}</strong></strong>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12">
                        <div class="invoice-user-name" style="margin-top: 0px;">
                            <strong>Credit Cards: <strong id="credit_card">${{$summery_data['totalCreditAmount'] ?? '0.00'}}</strong></strong>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12">
                        <div class="invoice-user-name" style="margin-top: 0px;">
                            <strong>Checks: <strong id="check">${{$summery_data['totalCheckAmount'] ?? '0.00'}}</strong></strong>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12">
                        <div class="invoice-user-name" style="margin-top: 0px;">
                            <strong>Payment Apps: <strong id="payment_app">${{$summery_data['totalPaymentAmount'] ?? '0.00'}}</strong></strong>
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
                            <span>Printed: {{$print_date}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html> --}}


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>End Of Day Summary</title>

    <style>
        body {
            font-family: "Nunito", sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 0;
        }

        .my_color {
            color: #000;
        }

        .main-shaow-container {
            padding: 20px;
            max-width: 420px;
            margin: auto;
        }

        .receipt-container {
            max-width: 300px;
            margin: 20px auto;
            background-color: #FFFFFF;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e3e2e2;
            /* box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); */
        }

        .text-center {
            text-align: center;
        }

        .tax-invoice {
            border-top: 1px dashed #5B6670;
        }

        .invoice-user-name {
            margin: 10px 0;
            color: #092C4C;
            line-height: 1.5;
            font-size: 14px;
            font-family: "Nunito", sans-serif;
        }

        p {
            margin: 5px 0 0 0;
            font-size: 14px;
            color: #555555;
            line-height: 1.5;
            font-family: "Nunito", sans-serif;
        }

        h5 {
            color: #1B2850;
            font-family: "Nunito", sans-serif;
            font-weight: 700;
            margin-bottom: 0;
            margin-top: 5px;
            font-size: 16px;
            line-height: 1.2;
        }

        h6 {
            margin: 10px 0;
            font-weight: 600;
            position: relative;
            color: #1B2850;
            font-family: "Nunito", sans-serif;
            font-size: 16px;
            line-height: 1.2;
        }

        .modal-body {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th {
            padding: 8px;
        }

        table th {
            color: #092C4C;
            width: auto;
            min-width: auto;
            padding: 5px;
            border-top: 1px dashed #5B6670;
            border-bottom: 1px dashed #5B6670;
            text-align: left;
            line-height: 1.5;
        }

        table td {
            font-size: 14px;
            color: #555555;
            line-height: 1.5;
            font-family: "Nunito", sans-serif;
            padding: 5px;
        }

        .text-end {
            text-align: right;
        }

        .invoice-bar {
            margin-top: 10px;
        }

        tfoot tr,tbody tr {
            font-weight: bold;
        }

        .footer-message {
            margin-top: 3px;
            color: #555;
        }

        .dashed-border {
            border-top: 1px dashed #5B6670;
            border-bottom: 1px dashed #5B6670;
        }

        .receipt_date {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-direction: row;
        }
    </style>
</head>
<body>
    <div class="main-shaow-container">
        <div class="receipt-container" id="print-receipt">
            <div class="modal-body">
                @php
                    $today = Carbon\Carbon::now()->format('m-d-Y');
                    $print_date = Carbon\Carbon::now()->format('m-d-Y g:i A');
                @endphp
                <div class=" info">
                    <h5>Day-End Report: <span>{{$summery_data['location_name']}}</span> </h5>
                    <p class="mb-0">Date: <span>{{$today}}</span> </p>
                    <p class="mb-0">Closed By: {{$summery_data['user_name']}}</p>
                    <p></p>
                </div>

                <div class="tax-invoice">
                    <div class="row receipt_date">
                        <div class="col-sm-12 col-md-6">
                            <div class="invoice-user-name"><span>Payment Methods: </span></div>
                        </div>
                    </div>
                </div>

                <table>

                    <tbody class="dashed-border">

                        <tr>
                            <td colspan="3">Net: <span>${{$summery_data['totalSales'] ?? '0.00'}}</span></td>
                        </tr>

                        <tr>
                            <td colspan="3">Tax: <span>${{$summery_data['totalTax'] ?? '0.00'}}</span></td>
                        </tr>
                        <tr>
                            <td colspan="3">Gross: <span>${{$summery_data['gross_amount'] ?? '0.00'}}</span></td>
                        </tr>
                        <tr>
                            <td colspan="3">Cash: <span>${{$summery_data['totalCashAmount'] ?? '0.00'}}</span></td>
                        </tr>
                        <tr>
                            <td colspan="3">Credit Cards: <span>${{$summery_data['totalCreditAmount'] ?? '0.00'}}</span></td>
                        </tr>

                        <tr>
                            <td colspan="3">Checks: <span>${{$summery_data['totalCheckAmount'] ?? '0.00'}}</span></td>
                        </tr>

                        <tr>
                            <td colspan="3">Payment Apps: <span>${{$summery_data['totalPaymentAmount'] ?? '0.00'}}</span></td>
                        </tr>

                    </tbody>
                </table>

                <div class="invoice-bar">
                    <span>Printed: <span id="printed"> {{ $summery_data['currentDateTime']}}</span> </span>
                </div>
            </div>
        </div>
    </div>
</body>
=
</html>