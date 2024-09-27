@extends('admin.layouts.app')
@section('title', config('constants.site_title') . ' | Settings')
@section('contents')
    <style>
        .payment_row {
            pointer-events: none;
        }

        .payment_row .form-check-input:checked {
            background-color: #f3c190;
            border: #f3c190;
        }

        .payment_row label {
            color: #5B6670;
        }

        .disabled_input {
            background-color: #00000024 !important;
            border-color: #00000036 !important;
            color: #5B6670 !important;
            pointer-events: none !important;
        }

        .notification_append_div {
            display: flex;
            flex-direction: column-reverse;
        }
    </style>
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Settings</h4>
                    <h6>Manage Settings</h6>
                </div>
            </div>
            <ul class="table-top-head">
                <li>
                    <a href="https://hellodev.site/POS/public/admin/setting" data-bs-toggle="tooltip" data-bs-placement="top"
                        aria-label="Refresh" data-bs-original-title="Refresh"><svg xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-rotate-ccw">
                            <polyline points="1 4 1 10 7 10"></polyline>
                            <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
                        </svg></a>
                </li>
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" id="collapse-header" aria-label="Collapse"
                        data-bs-original-title="Collapse" class=""><svg xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-chevron-up">
                            <polyline points="18 15 12 9 6 15"></polyline>
                        </svg></a>
                </li>
            </ul>
        </div>


        <div class="card">

            <div class="card-body" style="padding: 2rem;">
                <ul class="nav nav-tabs mb-3 tab-style-2 p-0" id="myTab-3" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link  " id="business_information_setting-tab" data-bs-toggle="tab"
                            data-curent_tab="business_information_setting"
                            data-bs-target="#business_information_setting-tab-pane" type="button" role="tab"
                            aria-controls="business_information_setting-tab-pane" aria-selected="true"><i
                                class="fa-brands fa-slack me-2  align-middle d-inline-block"></i> Business
                            Information</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="general_setting-tab" data-bs-toggle="tab"
                            data-curent_tab="general_setting" data-bs-target="#general_setting-tab-pane" type="button"
                            role="tab" aria-controls="general_setting-tab-pane" aria-selected="false" tabindex="-1"><i
                                class="fa-solid fa-gear me-2 align-middle d-inline-block"></i>General Settings</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="notification_setting-tab" data-bs-toggle="tab"
                            data-curent_tab="notification_setting" data-bs-target="#notification_setting-tab-pane"
                            type="button" role="tab" aria-controls="notification_setting-tab-pane"
                            aria-selected="false" tabindex="-1"><i
                                class="fa-solid fa-bell me-2 align-middle d-inline-block"></i>Notifications</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="receipt_setting-tab" data-bs-toggle="tab"
                            data-curent_tab="receipt_setting" data-bs-target="#receipt_setting-tab-pane" type="button"
                            role="tab" aria-controls="receipt_setting-tab-pane" aria-selected="false" tabindex="-1"><i
                                class="fa-solid fa-receipt  me-2 align-middle d-inline-block"></i>Receipt Settings</button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent2">
                    <div class="setting_tabs tab-pane fade p-0 border-bottom-0 "
                        id="business_information_setting-tab-pane" role="tabpanel"
                        aria-labelledby="business-information-tab" tabindex="0">
                        <form action="{{ route('admin.setting') }}" method="post" class="mt-4">
                            @csrf
                            <div class="row mb-3">
                                <label class="col-lg-3 col-form-label" for="business_id">Business ID:</label>
                                <div class="col-lg-9">
                                    <input type="hidden" name="setting_id" value="{{ $setting->setting_id }}">
                                    <input type="number" class="form-control" value="{{ $setting->business_id }}"
                                        name="business_id" autocomplete="off" readonly>
                                    @if ($errors->has('business_id'))
                                        <small class="text-danger">{{ $errors->first('business_id') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-lg-3 col-form-label" for="business_name">Business Legal Name:</label>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control" value="{{ $setting->business_name }}"
                                        name="business_name" autocomplete="off" readonly>
                                    @if ($errors->has('business_name'))
                                        <small class="text-danger">{{ $errors->first('business_name') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-lg-3 col-form-label" for="company_name">Company Name:</label>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control" value="{{ $setting->company_name }}"
                                        name="company_name" autocomplete="off">
                                    @if ($errors->has('company_name'))
                                        <small class="text-danger">{{ $errors->first('company_name') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-lg-3 col-form-label" for="company_email">Company Email:</label>
                                <div class="col-lg-9">
                                    <input type="email" class="form-control" value="{{ $setting->company_email }}"
                                        name="company_email" autocomplete="off">
                                    @if ($errors->has('company_email'))
                                        <small class="text-danger">{{ $errors->first('company_email') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-lg-3 col-form-label" for="company_password">Company Password:</label>
                                <div class="col-lg-9">
                                    <input type="password" class="form-control" value="{{ $setting->company_password }}"
                                        name="company_password" autocomplete="off">
                                    @if ($errors->has('company_password'))
                                        <small class="text-danger">{{ $errors->first('company_password') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-lg-3 col-form-label" for="contact_name">Contact Name:</label>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control" value="{{ $setting->contact_name }}"
                                        name="contact_name" autocomplete="off" readonly>
                                    @if ($errors->has('contact_name'))
                                        <small class="text-danger">{{ $errors->first('contact_name') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-lg-3 col-form-label" for="contact_address">Contact Address:</label>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control" value="{{ $setting->contact_address }}"
                                        name="contact_address" autocomplete="off">
                                    @if ($errors->has('contact_address'))
                                        <small class="text-danger">{{ $errors->first('contact_address') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-lg-3 col-form-label" for="contact_phone">Contact Phone:</label>
                                <div class="col-lg-9">
                                    <input type="number" class="form-control" value="{{ $setting->contact_phone }}"
                                        name="contact_phone" autocomplete="off">
                                    @if ($errors->has('contact_phone'))
                                        <small class="text-danger">{{ $errors->first('contact_phone') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-lg-3 col-form-label" for="contact_email">Contact Email:</label>
                                <div class="col-lg-9">
                                    <input type="email" class="form-control" value="{{ $setting->contact_email }}"
                                        name="contact_email" autocomplete="off">
                                    @if ($errors->has('contact_email'))
                                        <small class="text-danger">{{ $errors->first('contact_email') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" name="business_information_setting"
                                    value="business_information_setting" value="submit"
                                    class="btn btn-primary Updated_changes">Save</button>
                            </div>
                        </form>
                    </div>

                    <div class="setting_tabs tab-pane fade active show" id="general_setting-tab-pane" role="tabpanel"
                        aria-labelledby="general-settings-tab" tabindex="0">
                        <div class="mt-4">
                            <form action="{{ route('admin.setting') }}" method="post" class="mt-4"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="setting_id" class="setting_id" value="{{ $setting->setting_id }}">

                                <div class="card-title">Inventory</div>

                                <div class="form-check form-check-md d-flex align-items-center">
                                    <input class="form-check-input" type="checkbox" value="{{ $setting->allow_transfer }}" id="allow_transfer" name="allow_transfer" @if ($setting->allow_transfer == '1') checked @endif>
                                    <label class="form-check-label" for="allow_transfer">Allow Transfers</label>
                                </div>
                                <div class="form-check form-check-md d-flex align-items-center">
                                    <input class="form-check-input" type="checkbox" value="{{ $setting->allow_changes }}" id="allow_changes" name="allow_changes" @if ($setting->allow_changes == '1') checked @endif>
                                    <label class="form-check-label" for="allow_changes">Allow Changes</label>
                                </div>

                                <div class="card-title mt-3">Transactions</div>
                                <div class="form-check form-check-md d-flex align-items-center">
                                    <input class="form-check-input" type="checkbox" value="{{ $setting->allow_check }}"
                                        id="allow_check" name="allow_check"
                                        @if ($setting->allow_check == '1') checked @endif>
                                    <label class="form-check-label" for="allow_check">Allow Checks as a Payment
                                        Method</label>
                                </div>
                                <div class="form-check form-check-md d-flex align-items-center">
                                    <input class="form-check-input" type="checkbox"
                                        value="{{ $setting->allow_payment_app }}" id="allow_payment_app"
                                        name="allow_payment_app" @if ($setting->allow_payment_app == '1') checked @endif>
                                    <label class="form-check-label" for="allow_payment_app">Allow Payment Apps as a
                                        Payment Method</label>
                                </div>

                                <div class="payment_app" style="width: 45%; margin:20px;">
                                    <div class="form-check-md row mb-3">
                                        <div
                                            class="col-4 payment_apps @if ($setting->allow_payment_app == '0') payment_row @endif ">
                                            <div class="form-check">
                                                <input class="form-check-input payment_app_checkbox" type="checkbox"
                                                    value="{{ $setting->allow_zella }}" id="allow_zella"
                                                    name="allow_zella" @if ($setting->allow_zella == '1') checked @endif>
                                                <label class="form-check-label" for="allow_zella">Zelle</label>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="profile-pic-upload mb-0">
                                                <div class="new-employee-field">
                                                    <div class="mb-0">
                                                        <div class="image-upload mb-0">
                                                            <input type="file" name="zella_file">
                                                            <div class="image-uploads">
                                                                <h4><i class="fa-solid fa-arrow-up-from-bracket"></i>
                                                                    Upload Photo</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="new-logo" style="width: 40%;">
                                                <a href="#">
                                                    @if (!empty($setting->zella_file))
                                                        <img src="{{ asset(config('constants.admin_path') . 'uploads/setting/' . $setting->zella_file) }}"
                                                            alt="img">
                                                    @else
                                                        <img src="{{ asset(config('constants.admin_path') . 'img/no_image.jpeg') }}"
                                                            alt="img">
                                                    @endif
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-check-md row mb-3">
                                        <div
                                            class="col-4 payment_apps @if ($setting->allow_payment_app == '0') payment_row @endif ">
                                            <div class="form-check">
                                                <input class="form-check-input payment_app_checkbox" type="checkbox"
                                                    value="{{ $setting->allow_paypal }}" id="allow_paypal"
                                                    name="allow_paypal" @if ($setting->allow_paypal == '1') checked @endif>
                                                <label class="form-check-label" for="allow_paypal">PayPal</label>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="profile-pic-upload mb-0">
                                                <div class="new-employee-field">
                                                    <div class="mb-0">
                                                        <div class="image-upload mb-0">
                                                            <input type="file" name="paypal_file">
                                                            <div class="image-uploads">
                                                                <h4><i class="fa-solid fa-arrow-up-from-bracket"></i>
                                                                    Upload Photo</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="new-logo" style="width: 40%;">
                                                <a href="#">
                                                    @if (!empty($setting->paypal_file))
                                                        <img src="{{ asset(config('constants.admin_path') . 'uploads/setting/' . $setting->paypal_file) }}"
                                                            alt="img">
                                                    @else
                                                        <img src="{{ asset(config('constants.admin_path') . 'img/no_image.jpeg') }}"
                                                            alt="img">
                                                    @endif
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-check-md row mb-3">
                                        <div
                                            class="col-4 payment_apps @if ($setting->allow_payment_app == '0') payment_row @endif ">
                                            <div class="form-check">
                                                <input class="form-check-input payment_app_checkbox" type="checkbox"
                                                    value="{{ $setting->allow_venmo }}" id="allow_venmo"
                                                    name="allow_venmo" @if ($setting->allow_venmo == '1') checked @endif>
                                                <label class="form-check-label" for="allow_venmo">Venmo</label>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="profile-pic-upload mb-0">
                                                <div class="new-employee-field">
                                                    <div class="mb-0">
                                                        <div class="image-upload mb-0">
                                                            <input type="file" name="venmo_file">
                                                            <div class="image-uploads">
                                                                <h4><i class="fa-solid fa-arrow-up-from-bracket"></i>
                                                                    Upload Photo</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="new-logo" style="width: 40%;">
                                                <a href="#">
                                                    @if (!empty($setting->venmo_file))
                                                        <img src="{{ asset(config('constants.admin_path') . 'uploads/setting/' . $setting->venmo_file) }}"
                                                            alt="img">
                                                    @else
                                                        <img src="{{ asset(config('constants.admin_path') . 'img/no_image.jpeg') }}"
                                                            alt="img">
                                                    @endif
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-check-md row mb-3">
                                        <div
                                            class="col-4 payment_apps @if ($setting->allow_payment_app == '0') payment_row @endif">
                                            <div class="form-check ">
                                                <input class="form-check-input payment_app_checkbox" type="checkbox"
                                                    value="{{ $setting->allow_cash_app }}" id="allow_cash_app"
                                                    name="allow_cash_app"
                                                    @if ($setting->allow_cash_app == '1') checked @endif>
                                                <label class="form-check-label" for="allow_cash_app">Cash App</label>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="profile-pic-upload mb-0">
                                                <div class="new-employee-field">
                                                    <div class="mb-0">
                                                        <div class="image-upload mb-0">
                                                            <input type="file" name="cash_app_file">
                                                            <div class="image-uploads">
                                                                <h4><i class="fa-solid fa-arrow-up-from-bracket"></i>
                                                                    Upload
                                                                    Photo</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="new-logo" style="width: 40%;">
                                                <a href="#">
                                                    @if (!empty($setting->cash_app_file))
                                                        <img src="{{ asset(config('constants.admin_path') . 'uploads/setting/' . $setting->cash_app_file) }}"
                                                            alt="img">
                                                    @else
                                                        <img src="{{ asset(config('constants.admin_path') . 'img/no_image.jpeg') }}"
                                                            alt="img">
                                                    @endif
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-check-md row mb-3">
                                        <div class="col-12">
                                            <small id="payment_app_error" class="text-danger"
                                                style=" display: none;"></small>
                                        </div>
                                    </div>
                                </div>

                                <div class="sale" style="width: 25%;">
                                    <label for="" class="form-label">Sales Approval Request</label>
                                    <select name="sales_approval_request_user[]" class="form-control selectbox_user"
                                        id="selectbox_user" multiple>

                                    </select>
                                </div>
                                <div class="text-end">
                                    <button type="submit" value="general_setting" name="general_setting"
                                        id="general_setting_btn" class="btn btn-primary Updated_changes">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="setting_tabs tab-pane fade" id="notification_setting-tab-pane" role="tabpanel"
                        aria-labelledby="notifications-tab" tabindex="0">
                        <form action="{{ route('admin.add_notification_item') }}" method="post" class="mt-4">
                            @csrf
                            <div class="notification_dropdown_div" style="width: 32%;">
                                <select name="notification_id[]" id="add_notification"
                                    class="form-control  notification_dropdown" multiple>
                                    <option value="">Add Notification</option>
                                    @foreach ($notification as $val)
                                        @php
                                            $isSelected = $notificationItem->contains(
                                                'notification_id',
                                                $val->notification_id,
                                            );
                                        @endphp
                                        <option @if ($isSelected) selected @endif
                                            value="{{ $val->notification_id }}">{{ $val->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="row mt-3 align-items-center notification_header" style="display: none;">
                                <div class="col-lg-2">
                                    <h6>Notification</h6>
                                </div>
                                <div class="col-lg-2">
                                    <h6>Trigger</h6>
                                </div>
                                <div class="col-lg-3">
                                    <h6>Manager(s)</h6>
                                </div>
                            </div>

                            <div class="notification_append_div"></div>

                            <div class="text-end">
                                <button type="submit" name="notification_setting" value="notification_setting" class="btn btn-primary Updated_changes">Save</button>
                            </div>
                        </form>
                    </div>

                    <div class="setting_tabs tab-pane fade" id="receipt_setting-tab-pane" role="tabpanel"
                        aria-labelledby="receipt-settings-tab" tabindex="0">
                        <form action="{{ route('admin.setting') }}" method="post" class="mt-4">
                            @csrf
                            <input type="hidden" name="setting_id" value="{{ $setting->setting_id }}">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="receipt_header">
                                        <div class="card-title"><u>Receipt Header</u></div>
                                        <div class="form-check form-check-md d-flex align-items-center">
                                            <input class="form-check-input" type="checkbox" id="showAddressCheckbox"
                                                name="show_address" value="{{ $setting->show_address }}"
                                                @if ($setting->show_address == '1') checked @endif>
                                            <label class="form-check-label" for="showAddressCheckbox">Show Address</label>
                                        </div>
                                        <div class="form-check form-check-md d-flex align-items-center">
                                            <input class="form-check-input" type="checkbox" id="showPhoneCheckbox"
                                                name="show_phone" value="{{ $setting->show_phone }}"
                                                @if ($setting->show_phone == '1') checked @endif>
                                            <label class="form-check-label" for="showPhoneCheckbox">Show Phone</label>
                                        </div>
                                        <div class="form-check form-check-md d-flex align-items-center">
                                            <input class="form-check-input" type="checkbox" id="showEmailCheckbox"
                                                name="show_email" value="{{ $setting->show_email }}"
                                                @if ($setting->show_email == '1') checked @endif>
                                            <label class="form-check-label" for="showEmailCheckbox">Show Email</label>
                                        </div>

                                    </div>
                                    <div class="receipt_footer mt-4">
                                        <div class="card-title"><u> Receipt Footer </u></div>
                                        <div class="form-check form-check-md d-flex align-items-center">
                                            <input class="form-check-input" type="checkbox" id="returnPolicyCheckbox"
                                                name="show_return_policy" value="{{ $setting->show_return_policy }}"
                                                @if ($setting->show_return_policy == '1') checked @endif>
                                            <label class="form-check-label" for="returnPolicyCheckbox">Return
                                                Policy</label>
                                            <input type="hidden" name="returnPolicy" id="returnPolicyInput"
                                                value="">

                                        </div>
                                        <textarea id="returnPolicyText" rows="2" cols="3" class="form-control"> {{ $setting->show_return_policy_value }}</textarea>
                                        <div class="form-check form-check-md d-flex align-items-center">
                                            <input class="form-check-input" type="checkbox" id="footerMessageCheckbox"
                                                name="show_footer_message" value="{{ $setting->show_footer_message }}"
                                                @if ($setting->show_footer_message == '1') checked @endif>
                                            <label class="form-check-label" for="footerMessageCheckbox">Footer
                                                Message</label>
                                            <input type="hidden" name="footerMessage" id="footerMessageInput"
                                                value="">
                                        </div>
                                        <textarea id="footerMessageText" rows="2" cols="3" class="form-control">{{ $setting->show_footer_message_value }}</textarea>
                                    </div>
                                </div>
                                <div class="col-lg-6">

                                    <div class="receipt-container" id="print-receipt">
                                        <div class="modal-body">
                                            <div class="text-center info text-center">
                                                <h5>{{ $setting->company_name }}</h5>
                                                <div class="tax-invoice">
                                                    <h6 class="text-center">{{ $location->location_name }}</h6>
                                                </div>
                                                <p class="mb-0" id="previewAddress" style="display: none;">
                                                    {{ $location->location_address }}<br />{{ $location->location_city . ' , ' . $location->location_state . ' ' . $location->location_zip }}
                                                </p>
                                                <p class="mb-0" id="previewPhone" style="display: none;">
                                                    {{ $location->location_phone_number }}</p>
                                                <p id="previewEmail" style="display: none; margin-bottom:10px; "><a
                                                        href="mailto:{{ $setting->company_email }}">{{ $setting->company_email }}</a>
                                                </p>

                                            </div>
                                            <div class="text-center info text-center"
                                                style="border-top: 1px dashed #5B6670;">
                                                <div class="tax-invoice">
                                                    <div class="row">
                                                        <div class="col-sm-12 col-md-6">
                                                            <div class="invoice-user-name" style="margin-top: 10px;">
                                                                <span>Receipt #: </span><span id="receipt-number">
                                                                    000123</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-6">
                                                            <div class="invoice-user-name" style="margin-top: 10px;">
                                                                <span>Date: </span><span id="date">2024-06-25
                                                                    15:16:40</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <table class="table-borderless w-100 table-fit">
                                                <thead>
                                                    <tr>
                                                        <th>Item</th>
                                                        <th>Price</th>
                                                        <th>Qty</th>
                                                        <th class="text-end">Total</th>
                                                    </tr>
                                                </thead>
                                                {{-- <tbody >
                                                    <tr>
                                                        <td>Red Nike Laser</td>
                                                        <td>$50</td>
                                                        <td>3</td>
                                                        <td class="text-end">$150</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Iphone 14</td>
                                                        <td>$50</td>
                                                        <td>2</td>
                                                        <td class="text-end">$100</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Apple Series 8</td>
                                                        <td>$50</td>
                                                        <td>3</td>
                                                        <td class="text-end">$150</td>
                                                    </tr>
                                                    
                                                </tbody> --}}
                                                <tfoot style="border-top: 1px dashed #5B6670;">
                                                    <tr>
                                                        <td colspan="3"><strong>Subtotal</strong></td>
                                                        <td class="text-end"></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3"><strong>Tax</strong></td>
                                                        <td class="text-end"></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3"><strong>Total</strong></td>
                                                        <td class="text-end"><strong>0</strong></td>
                                                    </tr>
                                                </tfoot>

                                            </table>

                                            <div class="text-center invoice-bar">
                                                <div class="salesperson">
                                                    <p style="margin: 0 !important; text-align: left;">Sales Representative(s): 
                                                        <strong>John</strong> </p>
                                                </div>
                                                <p>Payment Method(s): <strong> Credit Card</strong> </p>
                                                <P id="previewReturnPolicy" style="display: none;"></p>
                                                <p id="previewFooterMessage" style="display: none;"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" value="receipt_setting" name="receipt_setting"
                                    class="btn btn-primary Updated_changes">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom_script')
    <script>
        $(document).ready(function() {

            // --- active current ---   

            let savedTab = localStorage.getItem('currentTab');

            if (savedTab) {

                $('.nav-link').removeClass('active');
                $(document).find('.tab-pane').removeClass('show active');

                $(`[data-curent_tab="${savedTab}"]`).addClass('active');
                $(`#${savedTab}-tab-pane`).addClass('show active');
            }


            // --- General Setting ---

            $("#general_setting_btn").on("click", function(e) {

                if ($("#allow_payment_app").is(':checked')) {

                    if (!$(".payment_app_checkbox:checked").length) {

                        $("#payment_app_error").text("Please select any of the applicable Payment Apps").show();
                        e.preventDefault();
                    } else {
                        $("#payment_app_error").hide();
                    }

                }
            })

            TransactionPaymentApp()

            function TransactionPaymentApp() {

                if ($("#allow_payment_app").is(':checked')) {

                    $(".payment_apps").removeClass('payment_row');

                } else {
                    $(".payment_apps").addClass('payment_row');
                }
            }

            $("#allow_payment_app").change(function() {
                TransactionPaymentApp()
            })

            $.ajax({

                url: "{{ route('admin.getUser') }}",
                type: 'GET',
                data: {
                    setting_id: $(".setting_id").val(),
                    _token: "{{ csrf_token() }}",
                },
                success: function(response) {

                    if (response.status == 'success') {

                        var selectedIds = response.request_user.map(item => item.user_id);;

                        let selectBox = $('.selectbox_user');
                        selectBox.empty();
                        response.users.forEach(function(user) {

                            var selected = selectedIds.includes(user.user_id) ? 'selected' : '';
                            selectBox.append('<option ' + selected + '  value="' + user
                                .user_id + '">' + user.user_name + '</option>');
                        });

                    }
                }
            })

            // --- notification setting ---

            function handleNotificationChange() {

                $(".notification_header").show();
                let selectedNotification = $('#add_notification').val();
                let notificationAppendDiv = '';

                $('.notification_append_div').empty();

                selectedNotification.forEach(function(value) {
                    let label, inputType, inputAttributes;

                    switch (value) {
                        case '1':
                            label = 'First Sale';
                            inputType = 'text';
                            inputAttributes =
                                `class="form-control disabled_input notification_trigger_${value}" readonly`;
                            break;
                        case '2':
                        case '3':
                            label = (value === '2') ? 'Large Sale' :
                                'Total Location Sales';
                            inputType = 'number';
                            inputAttributes = `class="form-control decimals notification_trigger_${value}"`;
                            break;
                        case '4':
                            label = 'End of Day Summary';
                            inputType = 'text';
                            inputAttributes =
                                `class="form-control disabled_input notification_trigger_${value}" readonly `;
                            break;
                        case '5':
                            label = 'Long Break';
                            inputType = 'number';
                            inputAttributes = `class="form-control integer_field notification_trigger_${value} "`;
                            break;
                        default:
                            return;
                    }

                    notificationAppendDiv += `
                        <div class="row mt-3 align-items-center">
                            <label class="col-lg-2 col-form-label" for="">${label}</label>
                            <div class="col-lg-2">
                                <input type="${inputType}" ${inputAttributes} name="trigger_value[]" autocomplete="off">
                                <div class="invalid-feedback">Please enter a Number greater than 0.</div>
                            </div>
                            <div class="col-lg-3">
                                <select name="user_id_${value}[]" class="form-control selectbox_user2 selectbox_user_${value}" multiple>
                                </select>
                            </div>
                        </div>
                    `;                    
                });

                $(".notification_append_div").prepend(notificationAppendDiv);

                $(".selectbox_user").select2();
                $(".selectbox_user2").select2();

                // Format decimal values
                $('.decimals').on('blur', function() {
                    var value = $(this).val();
                    if (value !== '') {
                        var floatValue = parseFloat(value);
                        if (!isNaN(floatValue)) {
                            $(this).val(floatValue.toFixed(2));
                        }
                    }
                });

                // Validate integer input fields
                $(".integer_field").on("input", function(e) {
                    let value = $(this).val();

                    if (parseFloat(value) <= 0 || isNaN(value)) {
                        $(this).addClass("is-invalid");
                        $(this).next(".invalid-feedback").text("Please enter a value greater than 0.");
                        e.preventDefault();
                    } else {
                        $(this).removeClass("is-invalid");
                        $(this).next(".invalid-feedback").text("");
                    }
                });


                $.ajax({
                    url: "{{ route('admin.getNotificationUser') }}",
                    type: 'GET',
                    data: {
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                           
                            let selectBox = $(document).find('.selectbox_user2');
                            selectBox.empty();
                            response.users.forEach(function(user) {
                                selectBox.append('<option value="' + user.user_id + '">' + user.user_name + '</option>');
                            });

                            if (response.notificationItem && response.notificationItem.length > 0) {
                                response.notificationItem.forEach(function(item) {

                                    let userIdsArray = JSON.parse(item.user_ids).map(id => parseInt(id));

                                    let selectedNotificationUser = response.users.filter(user =>
                                        userIdsArray.includes(user.user_id)
                                    );
                                    
                                    let selectedNotificationUserIds = selectedNotificationUser.map(user => user.user_id);

                                    let selectBoxClass = `.selectbox_user_${item.notification_id}`;   
                                    let notification_trigger_Class = `.notification_trigger_${item.notification_id}`;   
                                    
                                    $(notification_trigger_Class).val(item.trigger_value)

                                    $(selectBoxClass).empty();

                                    response.users.forEach(function(user) {

                                        let selected = selectedNotificationUserIds.includes(user.user_id) ? 'selected' : '';                                    
                                        $(selectBoxClass).append('<option ' + selected + ' value="' + user.user_id + '">' + user.user_name + '</option>');
                                        
                                    });
                                });
                            }

                          
                        }
                    }
                });
            }

            handleNotificationChange();

            $(document).on('change', '#add_notification', function() {
                handleNotificationChange();
            });


            $(".selectbox_user").select2();

            $(".notification_dropdown").select2({
                placeholder: "Add Notification"
            });


            // --- receipt Setting ---

            function updatePreview() {
                // Show/Hide Address
                if ($('#showAddressCheckbox').is(':checked')) {
                    $('#previewAddress').show();
                    // $('#showAddressInput').val('123 Main St, Austin, TX 78746');
                } else {
                    $('#previewAddress').hide();
                    // $('#showAddressInput').val('');
                }

                // Show/Hide Phone
                if ($('#showPhoneCheckbox').is(':checked')) {
                    $('#previewPhone').show();
                    // $('#showPhoneInput').val('(512)-123-4444');
                } else {
                    $('#previewPhone').hide();
                    // $('#showPhoneInput').val('');
                }

                // Show/Hide Email
                if ($('#showEmailCheckbox').is(':checked')) {
                    $('#previewEmail').show();
                    // $('#showEmailInput').val('help@flexpos.com');
                } else {
                    $('#previewEmail').hide();
                    // $('#showEmailInput').val('');
                }

                // Show/Hide Return Policy
                if ($('#returnPolicyCheckbox').is(':checked')) {
                    var policyText = $('#returnPolicyText').val();
                    $('#previewReturnPolicy').text(policyText);
                    $('#previewReturnPolicy').show();
                    $('#returnPolicyInput').val(policyText);
                } else {
                    $('#previewReturnPolicy').hide();
                    $('#returnPolicyInput').val('');
                }

                // Show/Hide Footer Message
                if ($('#footerMessageCheckbox').is(':checked')) {
                    var footerText = $('#footerMessageText').val();
                    $('#previewFooterMessage').text(footerText);
                    $('#previewFooterMessage').show();
                    $('#footerMessageInput').val(footerText);
                } else {
                    $('#previewFooterMessage').hide();
                    $('#footerMessageInput').val('');
                    
                }
            }

            // Initialize preview on page load
            updatePreview();

            // Update preview on checkbox change
            $('.form-check-input').change(function() {
                updatePreview();
            });

            // Update preview on textarea input
            $('textarea').on('input', function() {
                updatePreview();
            });

        });

        $(".Updated_changes").on("click", function() {
            let currentTab = $(".nav-link.active").attr("data-curent_tab");
            localStorage.setItem('currentTab', currentTab);
        });
    </script>
@endsection
