@extends('admin.layouts.app')
@section('title', config('constants.site_title') . ' | Add User')
@section('contents')
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Users</h4>
                    <h6>Add User</h6>
                </div>
            </div>
            <ul class="table-top-head">
                <li>
                    <div class="page-btn">
                        <a href="{{ route('admin.users') }}" class="btn btn-secondary"><i data-feather="arrow-left"
                                class="me-2"></i>Back</a>
                    </div>
                </li>
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i
                            data-feather="chevron-up" class="feather-chevron-up"></i></a>
                </li>
            </ul>
        </div>
        <form id="user_form" action="{{ route('admin.add_user') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="new-employee-field">
                        <div class="card-title-head">
                            <h6><span><i data-feather="info" class="feather-edit"></i></span>User Information</h6>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-6 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="user_name" class="form-control"
                                        value="{{ old('user_name') }}" autocomplete="off">
                                    @if ($errors->has('user_name'))
                                        <small class="text-danger">{{ $errors->first('user_name') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Role</label>
                                    <select name="user_role" class="form-control selectbox">
                                        <option value="">Select</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->role_id }}">{{ $role->role_name }}</option>
                                        @endforeach
                                    </select>
                                    <div id="user_role_error"></div>
                                    @if ($errors->has('user_role'))
                                        <small class="text-danger">{{ $errors->first('user_role') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="text" name="user_email" class="form-control"
                                        value="{{ old('user_email') }}" autocomplete="off">
                                    @if ($errors->has('user_email'))
                                        <small class="text-danger">{{ $errors->first('user_email') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="input-blocks mb-md-0 mb-sm-3">
                                    <label>Password</label>
                                    <div class="pass-group">
                                        <input type="password" name="user_password" class="pass-input"
                                            value="{{ old('user_password') }}" autocomplete="off">
                                        <span class="fas toggle-password fa-eye-slash"></span>
                                    </div>
                                    @if ($errors->has('user_password'))
                                        <small class="text-danger">{{ $errors->first('user_password') }}</small>
                                    @endif
                                </div>
                            </div>
                            {{-- <div class="col-lg-6 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="user_phone" class="form-control" value="{{ old('user_phone') }}" autocomplete="off">
                                @if ($errors->has('user_phone'))
                                <small class="text-danger">{{ $errors->first('user_phone') }}</small>
                                @endif
                            </div>
                        </div> --}}
                            <div class="col-lg-6 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Pay Structure</label>
                                    <select name="user_salary_type" id="user_salary_type" class="form-control selectbox" onchange="select_type()">
                                        <option value="">Select</option>
                                        <option value="1">None</option>
                                        <option value="2">Fixed Commission</option>
                                        <option value="3">Commission Steps</option>
                                    </select>
                                    <div id="user_salary_type_error"></div>
                                    @if ($errors->has('user_salary_type'))
                                        <small class="text-danger">{{ $errors->first('user_salary_type') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">PIN</label>
                                    <input type="password" name="pin" id="pin" class="form-control " value="{{ old('pin') }}" autocomplete="off">   
                                    <small id="pinError" style="color:red; display:none;">Please Enter Only Digits.</small>
                                    @if ($errors->has('pin'))
                                        <small class="text-danger phpPinError">{{ $errors->first('pin') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3" id="step_commission_div" style="display: none">
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0" style="width: 50%;">
                                        <thead>
                                            <tr>
                                                <th>From</th>
                                                <th>To</th>
                                                <th>Commission (%)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="mb-3">
                                                        <input type="number" name="commissions[FROM][1]"
                                                            class="form-control" readonly value="0.00" placeholder="0.00"
                                                            autocomplete="off">                                                        
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="mb-3">
                                                        <input type="number" name="commissions[TO][1]" id="step1_to" class="form-control" placeholder="Enter To" autocomplete="off">
                                                        <small class="text-danger " style="display:none" id="step1_to_error"></small>
                                                        
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="mb-3">
                                                        <input type="number" name="commissions[AMOUNT][1]"
                                                            class="form-control user_commission" placeholder="Enter Commission (%)"  autocomplete="off">
                                                        <small class="text-danger user_commission_error"></small>
                                                    </div>
                                                </td>
                                            </tr>


                                            <tr>
                                                <td>
                                                    <div class="mb-3">
                                                        <input type="number" name="commissions[FROM][2]" class="form-control" readonly id="step2_from" value="" placeholder="0.00" autocomplete="off">

                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="mb-3">
                                                        <input type="number" name="commissions[TO][2]" class="form-control " id="step2_to" readonly value="99999.99" placeholder="99999.99" autocomplete="off">
                                                        {{-- <small class="text-danger user_commission_error_step2"></small> --}}
                                                        <small class="text-danger " style="display:none" id="step2_to_error"></small>

                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="mb-3">
                                                        <input type="number" name="commissions[AMOUNT][2]"
                                                            class="form-control user_commission" placeholder="Enter Commission (%)"
                                                            autocomplete="off">
                                                            <small class="text-danger user_commission_error"></small>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="3">
                                                    <div class="mb-0">
                                                        <input type="checkbox" id="enable_disableCheckbox"> <span class="enable_disable"></span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr id="step_three_div" style="display: none;">
                                                <td>
                                                    <div class="mb-3">
                                                        <input type="number" name="commissions[FROM][3]" class="form-control" readonly disabled value="" id="step3_from" placeholder="0.00" autocomplete="off">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="mb-3">
                                                        <input type="number" name="commissions[TO][3]" id="step3_to" class="form-control " disabled readonly value="99999.99" placeholder="99999.99"  autocomplete="off">
                                                        {{-- <small class="text-danger user_commission_error_step2"></small> --}}
                                                        <small class="text-danger " style="display:none" id="step3_to_error"></small>
                                                    </div>
                                                </td>
                                              
                                                <td>
                                                    <div class="mb-3">
                                                        <input type="number" name="commissions[AMOUNT][3]"
                                                            class="form-control user_commission" disabled placeholder="Enter Commission (%)"
                                                            autocomplete="off">
                                                        <small class="text-danger user_commission_error"></small>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{-- <div class="col-lg-6 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Image</label>
                                <div>
                                    <img src="{{ asset(config('constants.admin_path').'img/no_image.png') }}" class="img-thumbnail" id="user_image_src" style="height: 100px" alt="">
                                </div>
                                <input type="file" name="user_image" id="user_image" class="form-control">
                                @if ($errors->has('user_image'))
                                <small class="text-danger">{{ $errors->first('user_image') }}</small>
                                @endif
                            </div>
                        </div> --}}
                            <div class="col-lg-6 col-md-6" id="fixed_commission_div" style="display: none">
                                <div class="mb-3">
                                    <label class="form-label">Fixed Commission (%)</label>
                                    <input type="number" name="user_commission" class="form-control user_commission" id="user_commission_id" value="{{ old('user_commission') }}" autocomplete="off">
                                    <small class="text-danger user_commission_error"></small>
                                    @if ($errors->has('user_commission'))
                                        <small class="text-danger user_commission_error">{{ $errors->first('user_commission') }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-end mb-3">
                <button type="submit" name="submit" class="btn btn-submit" value="submit">Save</button>
            </div>
        </form>
    </div>
@endsection
@section('custom_script')
    <script>
        
        $(document).ready(function() {

            $("#step1_to").on('input', function () {
                let step1_to_raw = $("#step1_to").val();
                let step1_to = parseFloat(step1_to_raw);
                
                if (!isNaN(step1_to)) {
                    $("#step1_to").blur(function () {
                        $("#step1_to").val(step1_to.toFixed(2));
                        let step2_from = parseFloat(step1_to) + 0.01;
                        $("#step2_from").prop("value", step2_from.toFixed(2));
                    });

                } else {
                    $("#step2_from").prop("value", "");
                }
            });

            $("#step2_to").on('input', function () {

                let step2_to_raw = $("#step2_to").val();
                let step2_to = parseFloat(step2_to_raw);
                
                if (!isNaN(step2_to)) {
                    $("#step2_to").blur(function () {
                        $("#step2_to").val(step2_to.toFixed(2));
                        let step3_from = parseFloat(step2_to) + 0.01;
                        $("#step3_from").prop("value", step3_from.toFixed(2));
                    })
                } else {
                    $("#step3_from").prop("value", "");
                }
            });

            $(".enable_disable").text('Add an Extra Step');
            $('#enable_disableCheckbox').change(function() {

                if ($('#enable_disableCheckbox').is(':checked')) {
                    
                    $('#step_three_div').show();
                    $(".enable_disable").text('Delete Extra Step');
                    $("#step1_to").val(null);
                    $("#step2_to").val('').prop('readonly', false);
                    $("#step2_to").prop('placeholder','Enter To');

                    $('input[name^="commissions[FROM][3]"]').prop('disabled',false);
                    $('input[name^="commissions[TO][3]"]').prop('disabled',false);
                    $('input[name^="commissions[AMOUNT][3]"]').prop('disabled',false);
                    
                } else {
                    
                    $('#step_three_div').hide();
                    $('#step3_from').val('0.00').prop('placeholder', '0.00');
                    $(".enable_disable").text('Add an Extra Step');
                    $("#step2_to").val(99999.99).prop('readonly', true);
                    $("#step2_to").prop('placeholder','99999.99');
                    $("#step2_to").off('blur');

                    
                    $('input[name^="commissions[FROM][3]"]').prop('disabled',true);
                    $('input[name^="commissions[TO][3]"]').prop('disabled',true);
                    $('input[name^="commissions[AMOUNT][3]"]').prop('disabled',true);
                }
            });
            
            $('.user_commission').on('input', function() {
                var value = $(this).val();
                if (value < 1 || value > 100) {
                    // $(this).val('');
                    $(this).parent().find('.user_commission_error').html('Please enter a commission % value between 1-100');
                    $('button[name="submit"]').attr('disabled',true);
                }else{
                    $(this).parent().find('.user_commission_error').html(null);
                    $('button[name="submit"]').attr('disabled',false);
                }
            });
            // $('.commission_step2').on('input', function() {
                
            //     var value = $(this).val();
            //     if (value > 99999.99) {
            //         $(this).val('');
            //         $('.user_commission_error_step2').html('value Should be less than 99999.99');
            //     }
            // });
        });

        function select_type() {
            
            var salary_type = $("#user_salary_type").val();            

            $("#fixed_commission_div").hide();
            $("#step_commission_div").hide();

            if (salary_type == 2) {
                $("#fixed_commission_div").show();
                setTimeout(() => {                    
                    $('#user_commission_id').focus();                
                }, 500);
            }

            if (salary_type == 3) {
                $("#step_commission_div").show();
            }
        }

        // user_image.onchange = evt => {
        //     const [file] = user_image.files
        //     if (file) {
        //         user_image_src.src = URL.createObjectURL(file)
        //     }
        // }

        function validateInputs() {
            let step1_to = parseFloat($("#step1_to").val());
            let step2_to = parseFloat($("#step2_to").val());
            let step3_to = parseFloat($("#step3_to").val());

            if (!isNaN(step1_to) && !isNaN(step2_to) && step1_to > step2_to) {
                $("#step1_to_error").text("Step 1 'To' value cannot be greater than Step 2 'To' value.").show();
                $("#step1_to").val('').focus(); 
            }else{
                
                $("#step1_to_error").hide()
            }

            if (!isNaN(step2_to) && !isNaN(step3_to) && step2_to > step3_to) {
                $("#step2_to_error").text("Step 2 'To' value cannot be greater than Step 3 'To' value.").show();
                $("#step2_to").val('').focus(); 
            }
            else
            {
                $("#step2_to_error").hide()
            }
        }

        // Validate on blur
        $("#step1_to").on('blur', validateInputs);
        $("#step2_to").on('blur', validateInputs);
        $("#step3_to").on('blur', validateInputs);

        $('#pin').on('input', function() {
            $('.phpPinError').hide();
            var pinInput = this.value;
            var pinError = document.getElementById('pinError');
            console.log(pinInput);
            if (/[^0-9]/.test(pinInput)) {
                pinError.style.display = 'inline';
            } else {
                pinError.style.display = 'none'; 
            }
        });
    </script>
@endsection
