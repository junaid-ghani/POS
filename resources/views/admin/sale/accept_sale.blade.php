@section('title', config('constants.site_title') . ' | Approve Sale')
<head>
    <title>POS System | Approve Sale</title>
    @include('admin.partials.style')
</head>

@if($employee_request->status == 0)
    <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; margin-top: 5%;" >
        <div class="col-6 ">
            <div class="row align-items-center mt-2 mb-5">
                <div class="col-12 d-flex justify-content-center align-items-center">
                    <h4>Sale Approval Request</h4>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-12 d-flex justify-content-between align-items-center">
                    
                    {{-- @dd($employee_request_sale) --}}
                    <h5>Salesperson: {{ $employee_request_sale->requestUser->user_name }}</h5>
                    @php
                        $formated_date =  date('d M, Y h:i A', strtotime($employee_request_sale->request_sale_added_on))                 
                    @endphp
                    <h5>{{ $formated_date }}</h5>
                </div>
            </div>
            <table class="table table-bordered product_table mt-2">
                <thead class="text-center">
                    <tr><th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                </tr></thead>
                <tbody class="text-center" id="product_data">                                

                    @foreach ($employee_request_sale->requestSale as $item)
                        <tr>
                            <td>{{$item->getRequestProduct->product_name}}</td>
                            <td>${{$item->price}}</td>
                            <td>{{$item->quantity}}</td>
                            <td>${{$item->total}}</td>
                        </tr>
                    @endforeach
                    
                </tbody>
                <tfoot>
                    @if ($employee_request_sale->discount != '0.00')
                    <tr>
                        <th colspan="3" class="text-end">Discounts</th>
                        <th class="text-center" id="discount_text">${{ $employee_request_sale->discount }}</th>
                    </tr>    
                    @endif
                    
                    <tr>
                        <th colspan="3" class="text-end">Subtotal</th>
                        <th class="text-center" id="subtotal_text">${{ $employee_request_sale->sub_total }}</th>
                    </tr>
                    <tr>
                        <th colspan="3" class="text-end">Tax</th>
                        <th class="text-center" id="tax_text">${{ $employee_request_sale->tax }}</th>
                    </tr>
                    <tr>
                        <th colspan="3" class="text-end">Total</th>
                        <th class="text-center" id="total_text">${{ $employee_request_sale->total }}</th>
                    </tr>
                    <tr>
                        <th colspan="3" class="text-end text-danger">Below Minimun</th>
                        <th class="text-center text-danger" id="total_text">$-{{ $employee_request_sale->loss_amount }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="mt-2" style="display: flex; justify-content: center;">
        <a href="{{ route('approve_emp',['id'=>$id , 'action' => 'approved']); }}" class="btn btn-login active" style="margin: 0 30px;">Approve</a>
        <a href="{{ route('approve_emp',['id'=>$id , 'action' => 'rejected']); }}" class="btn btn-login active">Reject</a>
    </div>
@else
<div class="row align-items-center mt-2 mb-5">
    <div class="col-12 d-flex justify-content-center align-items-center">
            <div class="alert alert-danger ">
                An action has already been taken for this transaction
            </div>
    </div>
</div>
@endif