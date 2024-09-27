<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
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
            max-width: 400px;
            margin: 0px auto;
            background-color: #FFFFFF;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e3e2e2;
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
        }

        .text-end {
            text-align: right;
        }

        .invoice-bar {
            margin-top: 10px;
        }

        tfoot tr {
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
        }
    </style>
</head>

<body>

    <div class="main-shaow-container">
        <div class="receipt-container" id="print-receipt">
            <div class="modal-body">
                <div class="text-center info">
                    <h5>{{ $settings->company_name }}</h5>
                    <h6>{{ $receipt_data['sale_location'] }}</h6>

                    @if ($settings->show_address == 1)
                        <p class="mb-0">{{ $receipt_data['location_address'] }} <br>
                            {{ $receipt_data['location_city'] }},{{ $receipt_data['location_state'] }} </p>
                    @endif

                    @if ($settings->show_phone == 1)
                        <p class="mb-0">{{ $receipt_data['location_phone_number'] }}</p>
                    @endif

                    @if ($settings->show_email == 1)
                        <a style="color:#555555; text-decoration: none;">
                            <p> {{ $settings->company_email }} </p>
                        </a>
                    @endif
                    <p></p>
                </div>

                <div class="tax-invoice">
                    <div class="row receipt_date">
                        <div class="col-sm-12 col-md-6">
                            <div class="invoice-user-name"><span>Receipt #:
                                    {{ $receipt_data['sale_invoice_no'] }}</span></div>
                        </div>
                        <div class="col-sm-12 col-md-6" style="margin-left:95px;">
                            <div class="invoice-user-name"><span>Date: {{ $receipt_data['sale_added_on'] }}</span></div>
                        </div>
                    </div>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th class="text-end">Total</th>
                        </tr>

                    </thead>
                    <tbody>
                        @foreach ($receipt_data['sale_items'] as $item)
                            @php
                                $product = \App\Models\Product::where('product_status', 1)
                                    ->where('product_id', $item->sale_item_product)
                                    ->first();
                            @endphp
                            <tr>
                                <td>{{ $product->product_name }}</td>
                                <td>{{ $item->sale_item_quantity }}</td>
                                <td>${{ number_format((int)$item->sale_item_price, 2) }}</td>
                                <td class="text-end">${{ number_format((int)$item->sale_item_total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="dashed-border">
                        @if ($receipt_data['sale_discount'] != null)
                            <tr>
                                <td colspan="3">Discount</td>
                                <td class="text-end">${{ $receipt_data['sale_discount_amount'] }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td colspan="3">Subtotal</td>
                            <td class="text-end">${{ $receipt_data['sale_sub_total'] }}</td>
                        </tr>
                        <tr>
                            <td colspan="3">Tax</td>
                            <td class="text-end">${{ $receipt_data['sale_tax'] }}</td>
                        </tr>
                        <tr>
                            <td colspan="3"><strong>Total</strong></td>
                            <td class="text-end"><strong>${{ $receipt_data['sale_grand_total'] }}</strong></td>
                        </tr>
                    </tfoot>
                </table>

                <div class="invoice-bar">
                    <p>Salesperson(s):

                        <strong>

                            @foreach ($receipt_data['users_for_email'] as $item)
                                {{ $item->user_name }}@if (!$loop->last)
                                    ,
                                @endif
                            @endforeach

                        </strong>
                    </p>
                </div>

                <div class="text-center invoice-bar">

                    @php
                        $paymentMethods = [];

                        if ($receipt_data['sale_pay_cash'] == 1) {
                            $paymentMethods[] = 'Cash';
                        }
                        if ($receipt_data['sale_pay_ccard'] == 1) {
                            $paymentMethods[] = 'Credit Card';
                        }
                        if ($receipt_data['sale_pay_payment_app'] == 1) {
                            $paymentMethods[] = 'Payment App';
                        }
                        if ($receipt_data['sale_pay_check'] == 1) {
                            $paymentMethods[] = 'Check';
                        }

                        $paymentMethodsList = implode(', ', $paymentMethods);

                    @endphp

                    @if (!empty($paymentMethodsList))
                        <p style="margin: 0 !important; text-align: left;">Payment Method(s):
                            <strong>{{ $paymentMethodsList }}</strong></p>
                        <br>
                    @endif

                    @if ($settings->show_return_policy == 1)
                        <p class="footer-message">{{ $settings->show_return_policy_value }}</p>
                    @endif

                    @if ($settings->show_footer_message == 1)
                        <p class="footer-message">{{ $settings->show_footer_message_value }}</p>
                    @endif

                </div>
            </div>
        </div>
    </div>

</body>

</html>
