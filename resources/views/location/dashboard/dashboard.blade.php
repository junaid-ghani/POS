@extends('location.layouts.app')
@section('title',config('constants.site_title').' | Dashboard')
@section('contents')
<div class="content">
    <div class="row">
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="dash-count">
                <div class="dash-counts">
                    <h4>100</h4>
                    <h5>Customers</h5>
                </div>
                <div class="dash-imgs">
                    <i data-feather="user"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="dash-count das1">
                <div class="dash-counts">
                    <h4>110</h4>
                    <h5>Suppliers</h5>
                </div>
                <div class="dash-imgs">
                    <i data-feather="user-check"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="dash-count das2">
                <div class="dash-counts">
                    <h4>150</h4>
                    <h5>Purchase Invoice</h5>
                </div>
                <div class="dash-imgs">
                    <img src="{{ asset(config('constants.admin_path').'img/icons/file-text-icon-01.svg') }}" class="img-fluid" alt="icon">
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12 d-flex">
            <div class="dash-count das3">
                <div class="dash-counts">
                    <h4>170</h4>
                    <h5>Sales Invoice</h5>
                </div>
                <div class="dash-imgs">
                    <i data-feather="file"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-7 col-sm-12 col-12 d-flex">
            <div class="card flex-fill">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Purchase & Sales</h5>
                    <div class="graph-sets">
                        <ul class="mb-0">
                            <li>
                                <span>Sales</span>
                            </li>
                            <li>
                                <span>Purchase</span>
                            </li>
                        </ul>
                        <div class="dropdown dropdown-wraper">
                            <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                2023
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item">2023</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item">2022</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item">2021</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="sales_charts"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-5 col-sm-12 col-12 d-flex">
            <div class="card flex-fill default-cover mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Recent Products</h4>
                    <div class="view-all-link">
                        <a href="javascript:void(0);" class="view-all d-flex align-items-center">
                            View All<span class="ps-2 d-flex align-items-center"><i data-feather="arrow-right" class="feather-16"></i></span>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive dataview">
                        <table class="table dashboard-recent-products">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Products</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td class="productimgname">
                                        <a href="javascript:void(0)" class="product-img">
                                            <img src="{{ asset(config('constants.admin_path').'img/products/stock-img-01.png') }}" alt="product">
                                        </a>
                                        <a href="javascript:void(0)">Lenevo 3rd Generation</a>
                                    </td>
                                    <td>$12500</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td class="productimgname">
                                        <a href="javascript:void(0)" class="product-img">
                                            <img src="{{ asset(config('constants.admin_path').'img/products/stock-img-06.png') }}" alt="product">
                                        </a>
                                        <a href="javascript:void(0)">Bold V3.2</a>
                                    </td>
                                    <td>$1600</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td class="productimgname">
                                        <a href="javascript:void(0)" class="product-img">
                                            <img src="{{ asset(config('constants.admin_path').'img/products/stock-img-02.png') }}" alt="product">
                                        </a>
                                        <a href="javascript:void(0)">Nike Jordan</a>
                                    </td>
                                    <td>$2000</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td class="productimgname">
                                        <a href="javascript:void(0)" class="product-img">
                                            <img src="{{ asset(config('constants.admin_path').'img/products/stock-img-03.png') }}" alt="product">
                                        </a>
                                        <a href="javascript:void(0)">Apple Series 5 Watch</a>
                                    </td>
                                    <td>$800</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection