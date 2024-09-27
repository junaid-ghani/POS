@extends('admin.layouts.app')
@section('title', config('constants.site_title') . ' | Report')
@section('contents')
  
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Report</h4>
                    <h6>Manage Report</h6>
                </div>
            </div>
            <ul class="table-top-head">
                <ul class="table-top-head">
                    <li>
                        <a href="{{ route('admin.salary') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i data-feather="rotate-ccw" class="feather-rotate-ccw"></i></a>
                    </li>
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i data-feather="chevron-up" class="feather-chevron-up"></i></a>
                    </li>
                </ul>
            </ul>
        </div>


        <div class="card">

            <div class="card-body" style="padding: 2rem;">
                <ul class="nav nav-tabs mb-3 tab-style-2 p-0" id="myTab-3" role="tablist">

                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="Salary-tab" data-bs-toggle="tab"
                            data-curent_tab="Salary" data-bs-target="#Salary-tab-pane" type="button"
                            role="tab" aria-controls="Salary-tab-pane" aria-selected="false" tabindex="-1"><i
                                class="fa-solid fa-gear me-2 align-middle d-inline-block"></i>Salary</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link  " id="Product-tab" data-bs-toggle="tab"
                            data-curent_tab="Product"
                            data-bs-target="#Product-tab-pane" type="button" role="tab"
                            aria-controls="Product-tab-pane" aria-selected="true"><i
                                class="fa-brands fa-slack me-2  align-middle d-inline-block"></i>Products</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="Payment_Method-tab" data-bs-toggle="tab"
                            data-curent_tab="Payment_Method" data-bs-target="#Payment_Method-tab-pane" type="button"
                            role="tab" aria-controls="Payment_Method-tab-pane" aria-selected="false" tabindex="-1"><i
                                class="fa-solid fa-receipt  me-2 align-middle d-inline-block"></i>Payment Methods</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="Expense-tab" data-bs-toggle="tab"
                            data-curent_tab="Expense" data-bs-target="#Expense-tab-pane"
                            type="button" role="tab" aria-controls="Expense-tab-pane"
                            aria-selected="false" tabindex="-1"><i
                                class="fa-solid fa-bell me-2 align-middle d-inline-block"></i>Expenses</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="Profit_Loss-tab" data-bs-toggle="tab"
                            data-curent_tab="Profit_Loss" data-bs-target="#Profit_Loss-tab-pane"
                            type="button" role="tab" aria-controls="Profit_Loss-tab-pane"
                            aria-selected="false" tabindex="-1"><i
                                class="fa-solid fa-chart-line me-2 align-middle d-inline-block"></i>Profit and Loss</button>

                    </li>
                </ul>
                <div class="tab-content" id="myTabContent2">

                    <div class="setting_tabs tab-pane fade active show" id="Salary-tab-pane" role="tabpanel"  aria-labelledby="general-settings-tab" tabindex="0">
                        {{-- <h2>Manage Inventory</h2> --}}
                        @include('admin.report.salary')
                    </div>

                    <div class="setting_tabs tab-pane fade p-0 border-bottom-0 " id="Product-tab-pane" role="tabpanel" aria-labelledby="business-information-tab" tabindex="0">
                        {{-- @include('admin.transfer_product.transfer_products') --}}
                        <h2>Products</h2>
                    </div>

                    <div class="setting_tabs tab-pane fade" id="Payment_Method-tab-pane" role="tabpanel" aria-labelledby="receipt-settings-tab" tabindex="0">
                        {{-- <h2>Assign Products</h2> --}}
                        {{-- @include('admin.assign_product.assign_products') --}}
                        <h2>Payment Methods</h2>
                    </div>

                    <div class="setting_tabs tab-pane fade" id="Expense-tab-pane" role="tabpanel" aria-labelledby="notifications-tab" tabindex="0">
                        <h2>Expenses</h2>
                    </div>
                    <div class="Profit_Loss tab-pane fade" id="Profit_Loss-tab-pane" role="tabpanel" aria-labelledby="notifications-tab" tabindex="0">
                        <h2>Profit and Loss</h2>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script>

        
        // $(document).ready(function() {

        //     // --- active current ---   

        //     let savedTab = localStorage.getItem('currentTab');

        //     if (savedTab) {

        //         $('.nav-link').removeClass('active');
        //         $(document).find('.tab-pane').removeClass('show active');

        //         $(`[data-curent_tab="${savedTab}"]`).addClass('active');
        //         $(`#${savedTab}-tab-pane`).addClass('show active');
        //     }

        // });

        // $(".Updated_changes").on("click", function() {
        //     let currentTab = $(".nav-link.active").attr("data-curent_tab");
        //     localStorage.setItem('currentTab', currentTab);
        // });

        
    </script>
@endsection
