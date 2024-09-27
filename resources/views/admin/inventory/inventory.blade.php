@extends('admin.layouts.app')
@section('title', config('constants.site_title') . ' | Inventory')
@section('contents')
  
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Inventory</h4>
                    <h6>Manage Inventory</h6>
                </div>
            </div>
            <ul class="table-top-head">
                <li>
                    <a  href="{{ route('admin.inventory') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i data-feather="rotate-ccw" class="feather-rotate-ccw"></i></a>
                </li>
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i data-feather="chevron-up" class="feather-chevron-up"></i></a>
                </li>
            </ul>
        </div>


        <div class="card">

            <div class="card-body" style="padding: 2rem;">
                <ul class="nav nav-tabs mb-3 tab-style-2 p-0" id="myTab-3" role="tablist">

                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="manage_inventory-tab" data-bs-toggle="tab"
                            data-curent_tab="manage_inventory" data-bs-target="#manage_inventory-tab-pane" type="button"
                            role="tab" aria-controls="manage_inventory-tab-pane" aria-selected="false" tabindex="-1"><i
                                class="fa-solid fa-gear me-2 align-middle d-inline-block"></i>Manage Inventory</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link  " id="transfer-tab" data-bs-toggle="tab"
                            data-curent_tab="transfer"
                            data-bs-target="#transfer-tab-pane" type="button" role="tab"
                            aria-controls="transfer-tab-pane" aria-selected="true"><i
                                class="fa-brands fa-slack me-2  align-middle d-inline-block"></i>Transfers</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="assign_product-tab" data-bs-toggle="tab"
                            data-curent_tab="assign_product" data-bs-target="#assign_product-tab-pane" type="button"
                            role="tab" aria-controls="assign_product-tab-pane" aria-selected="false" tabindex="-1"><i
                                class="fa-solid fa-receipt  me-2 align-middle d-inline-block"></i>Assign Items</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="inventory_log-tab" data-bs-toggle="tab"
                            data-curent_tab="inventory_log" data-bs-target="#inventory_log-tab-pane"
                            type="button" role="tab" aria-controls="inventory_log-tab-pane"
                            aria-selected="false" tabindex="-1"><i
                                class="fa-solid fa-bell me-2 align-middle d-inline-block"></i>Inventory Log</button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent2">

                    <div class="setting_tabs tab-pane fade active show" id="manage_inventory-tab-pane" role="tabpanel"  aria-labelledby="general-settings-tab" tabindex="0">
                        {{-- <h2>Manage Inventory</h2> --}}
                        @include('admin.stock.stocks')
                    </div>

                    <div class="setting_tabs tab-pane fade p-0 border-bottom-0 " id="transfer-tab-pane" role="tabpanel" aria-labelledby="business-information-tab" tabindex="0">
                        @include('admin.transfer_product.transfer_products')
                    </div>

                    <div class="setting_tabs tab-pane fade" id="assign_product-tab-pane" role="tabpanel" aria-labelledby="receipt-settings-tab" tabindex="0">
                        {{-- <h2>Assign Products</h2> --}}
                        @include('admin.assign_product.assign_products')
                    </div>

                    <div class="setting_tabs tab-pane fade" id="inventory_log-tab-pane" role="tabpanel" aria-labelledby="notifications-tab" tabindex="0">
                        {{-- <h2>Inventory Log</h2> --}}
                        @include('admin.inventory_log.inventory_log')
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
