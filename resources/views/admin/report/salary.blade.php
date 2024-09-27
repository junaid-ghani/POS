
    
        {{-- <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Report</h4>
                    <h6>Salary</h6>
                </div>
            </div>
            <ul class="table-top-head">
                <li>
                    <a href="{{ route('admin.salary') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i
                            data-feather="rotate-ccw" class="feather-rotate-ccw"></i></a>
                </li>
                <li>
                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i
                            data-feather="chevron-up" class="feather-chevron-up"></i></a>
                </li>
            </ul>
        </div> --}}
        <div class="card table-list-card">
            <div class="card-body">
                <div class="table-top">

                    <div class="search-set">
                        <div class="search-input">
                            <a href="javascript:void(0);" class="btn btn-searchset"><i data-feather="search"
                                    class="feather-search"></i></a>
                        </div>
                    </div>

                    <div class="card mb-0" id="filter_inputs" style="display:flex; flex-direction:row; align-items:center;">
                        <div class="page-btn">
                            <button type="button" id="adjustment_btn" data-bs-toggle="modal" data-bs-target="#adjustments"
                                class="btn btn-dark" title="Adjustments">Adjustments</button>
                        </div>
                        <div class="card-body pb-0">

                            <div id="reportrange"
                                style=" cursor: pointer; padding: 8.5px 10px; border: 1px solid #dbe0e6; width: 100%; color:#5B6670; border-radius:5px;">
                                <i class="fa fa-calendar"></i>&nbsp;
                                <span></span> <i class="fa fa-caret-down"></i>
                            </div>
                            <input type="hidden" value="{{ $startOfMonth }}" id="ini_start_date">
                            <input type="hidden" value="{{ $today }}" id="ini_end_date">
                        </div>
                        <div class="page-btn">
                            <button type="button" class="btn btn-primary" title="Print">Print</button>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="salary_table" class="table" style="width: 100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Salesperson</th>
                                <th>Total Sales</th>
                                <th>Total Commission</th>
                                <th>Bonuses</th>
                                <th>Reimbursements</th>
                                <th>Deductions</th>
                                <th>Total Salary</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>$0</th>
                                <th>$0</th>
                                <th>$0</th>
                                <th>$0</th>
                                <th>$0</th>
                                <th>$0</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
        </div>


        {{-- Salary_Report Modal --}}
        <div class="modal fade" id="Salary_Report" data-bs-backdrop="static" aria-hidden="true">
            <div class="modal-dialog" style="max-width: 1000px ">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" id="close_personal_SalaryReport" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="report-header" style="display: flex;justify-content:space-between; align-items:center;">

                            <div class="report_heading">
                                <h4>Salary Report</h4>
                            </div>
                            <div class="dateAndPirnt d-flex align-items-center">
                                <div class="card mb-0" id="filter_inputs" style="display:block; padding:0 24px 0px 24px;">
                                    <div id="salaryDate"
                                        style=" cursor: pointer; padding: 8.5px 10px; border: 1px solid #dbe0e6; width: 100%; color:#5B6670; border-radius:5px;">
                                        <i class="fa fa-calendar"></i>&nbsp;
                                        <span></span> <i class="fa fa-caret-down"></i>
                                    </div>
                                    <input type="hidden" value="{{ $startOfMonth }}" id="salary_ini_start_date">
                                    <input type="hidden" value="{{ $today }}" id="salary_ini_end_date">
                                </div>
                                <div class="page-btn">
                                    <button type="button" class="btn btn-primary" title="Print">Print</button>
                                </div>
                            </div>
                        </div>

                        <div class="report-heading mt-3" style="display: flex;justify-content:center; align-items:center;">
                            <h4 id="salesperson_name">Salesperson Name</h4>
                        </div>

                        <div class="salesperson_div">
                            <input type="hidden" value="" id="current_seller_id">
                            <table id="seller_sales" class="table  table-responsive table-bordered mt-3"
                                style="width: 100%">
                                <thead class="text-center seller_sale_head">
                                    <tr>
                                        <th class="seller_sale_header">#</th>
                                        <th class="seller_sale_header">Date</th>
                                        <th class="seller_sale_header">Sales</th>
                                        <th class="seller_sale_header">Commission</th>
                                        <th class="seller_sale_header">Bonus</th>
                                        <th class="seller_sale_header">Salary</th>
                                        <th class="seller_sale_header">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center" id="seller_data">

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th class="seller_sale_header"></th>
                                        <th class="seller_sale_header"></th>
                                        <th class="seller_sale_header">$0</th>
                                        <th class="seller_sale_header">$0</th>
                                        <th class="seller_sale_header">$0</th>
                                        <th class="seller_sale_header total_salary_tex">$0</th>
                                        <th class="seller_sale_header"></th>
                                    </tr>
                                </tfoot>

                            </table>
                        </div>

                        <div class="reimbursements_deductions">

                            <div class="mt-3">
                                <h4>Reimbursements | $<span id="reimbursementsTotalAmount">0.00</span> </h4>
                            </div>

                            <table class="table table-responsive table-bordered mt-3 w-50">
                                <thead class="text-center">
                                    <tr>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center" id="reimbursements_data">

                                </tbody>
                            </table>

                            <div class="mt-3">
                                <h4>Deductions | $<span id="deductionTotalAmount">0.00</span> </h4>
                            </div>

                            <table class="table table-responsive table-bordered mt-3 w-50">
                                <thead class="text-center">
                                    <tr>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center" id="deduction_data">

                                </tbody>
                            </table>

                        </div>


                        <div class="d-flex justify-content-end btn-block mt-3">
                            <a class="btn btn-secondary grandTotalSalary" href="javascript:void(0);">Total Salary : $<span
                                    id="grandTotalSalary">0</span> </a>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        {{-- Adjustment Modal --}}
        <div class="modal fade" id="adjustments" data-bs-backdrop="static" aria-hidden="true">
            <div class="modal-dialog" style="max-width: 1000px ">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" style="margin: auto 420px">Adjustments</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            fdprocessedid="ypanee"></button>
                    </div>
                    <div class="modal-body">
                        <form id="adjustment_form" action="{{ route('admin.adjustment') }}" method="post">
                            @csrf
                            <div class="report_form">

                                {{-- <div class="input-block row inputBlock">
                                    <div class="col-4">
                                        <label for="bonus">Bonus:</label>
                                    </div>
                                    <div class="col-6">
                                        <input type="text" id="bonus" name="bonus" class="form-control"
                                            placeholder="Enter Bonus">
                                    </div>
                                </div> --}}


                                <div class="input-block row inputBlock">
                                    <div class="col-4">
                                        <label for="type">Type:</label>
                                    </div>
                                    <div class="col-6">
                                        <select class="form-control select" name="type" id="type">
                                            <option value="">Select Type</option>
                                            <option value="reimbursements">Reimbursements</option>
                                            <option value="deductions">Deductions</option>
                                        </select>
                                        <small id="typeError" class="text-danger" style="display: none;"></small>
                                    </div>
                                </div>
                                <div class="input-block row inputBlock">
                                    <div class="col-4">
                                        <label for="Date">Date:</label>
                                    </div>
                                    <div class="col-6">
                                        <input type="text" id="date" name="date"
                                            class="datetimepicker form-control" placeholder="Select Date">
                                        <small id="DateError" class="text-danger" style="display: none;"></small>
                                    </div>
                                </div>
                                <div class="input-block row inputBlock">
                                    <div class="col-4">
                                        <label for="reimbursement_deduction_amount">Amount:</label>
                                    </div>
                                    <div class="col-6">
                                        <input type="text" id="reimbursement_deduction_amount"
                                            name="reimbursement_deduction_amount" class="form-control"
                                            placeholder="Enter Amount">
                                        <small id="AmountError" class="text-danger" style="display: none;"></small>
                                    </div>
                                </div>

                                <div class="input-block row inputBlock">
                                    <div class="col-4">
                                        <label for="description">Description:</label>
                                    </div>
                                    <div class="col-6">
                                        <textarea class="form-control" name="description" id="description" cols="3" rows="2"></textarea>
                                        <small id="DescriptionError" class="text-danger" style="display: none;"></small>
                                    </div>
                                </div>

                                <div class="input-block row inputBlock">
                                    <div class="col-4">
                                        <label for="reason">Salespersons:</label>
                                    </div>
                                    <div class="col-6">
                                        <div class="table-top mb-1">
                                            {{-- <div class="search-set">
                                                <div class="search-input2">
                                                    <a href="javascript:void(0);" class="btn btn-searchset"><i
                                                            data-feather="search" class="feather-search"></i></a>
                                                </div>
                                            </div> --}}
                                            <div class="page-btn">
                                                <button type="button" class="btn btn-primary "
                                                    id="select-all-btn">Select All</button>
                                            </div>
                                        </div>

                                        <div class="salesPersons">
                                            <select id="salesPersons" name="salespersons[]" multiple="multiple">

                                            </select>
                                            <small id="SalespersonError" class="text-danger"
                                                style="display: none;"></small>

                                            {{-- <table id="salesPersons_table" class="table table-responsive"
                                                style="width: 100%">
                                                <thead>
                                                    <tr>
                                                        <th>Select</th>
                                                        <th>Salesperson</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table> --}}
                                        </div>
                                    </div>
                                </div>

                                <div class="input-block mt-5 row ">
                                    <div class="col-12 inputBlock">
                                        <button type="submit" name="submit" value="submit"
                                            class="btn btn-primary ">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

  

@section('custom_script')

    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <script type="text/javascript">
        $(document).ready(function() {

            // --- Salary Modal ---

            var seller_sales_datatable = '';
            $(document).on('click', ".seller_salary", function() {
                let seller_id = $(this).data('seller_id');
                $("#current_seller_id").val(seller_id);
                var start_date = $('#salary_ini_start_date').val();
                var end_date = $('#salary_ini_end_date').val();

                if ($.fn.DataTable.isDataTable('#seller_sales')) {
                    $('#seller_sales').DataTable().destroy();
                }

                seller_sales_datatable = $('#seller_sales').DataTable({
                    dom: 'lrt',
                    "bFilter": true,
                    "bPaginate": false,
                    "bInfo": false,
                    "sDom": 'fBtlpi',
                    processing: true,
                    serverSide: true,
                    'ajax': {
                        type: 'POST',
                        url: "{{ route('admin.get_salary', 'seller_id') }}",
                        'data': function(data) {
                            var token = "{{ csrf_token() }}";
                            data._token = token;
                            var start_date = $('#salary_ini_start_date').val();
                            var end_date = $('#salary_ini_end_date').val();
                            data.start_date = start_date;
                            data.end_date = end_date;
                            data.seller_id = seller_id;
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex'
                        },
                        {
                            data: 'date'
                        },
                        {
                            data: 'total_sales'
                        },
                        {
                            data: 'total_commission'
                        },
                        {
                            data: 'bonus'
                        },
                        {
                            data: 'total_salary',
                            createdCell: function(td, cellData, rowData, row, col) {
                                $(td).addClass('salaires');
                            }
                        },
                        {
                            data: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ],
                    footerCallback: function(row, data, start, end, display) {
                        var api = this.api();

                        // Function to convert string to float and handle empty strings
                        var floatVal = function(i) {
                            return typeof i === 'string' ?
                                parseFloat(i.replace(/[\$,]/g, '')) :
                                typeof i === 'number' ? i : 0;
                        };

                        // Calculate the total for each column
                        var total_sales = api
                            .column(2, {
                                page: 'current'
                            })
                            .data()
                            .reduce(function(a, b) {
                                return floatVal(a) + floatVal(b);
                            }, 0);

                        var total_commission = api
                            .column(3, {
                                page: 'current'
                            })
                            .data()
                            .reduce(function(a, b) {
                                return floatVal(a) + floatVal(b);
                            }, 0);

                        var bonus = 0;
                        api.column(4, {
                            page: 'current'
                        }).nodes().to$().find('input').each(function() {
                            var inputVal = $(this).val().replace('$', '');
                            bonus += floatVal(inputVal);
                        });

                        var total_salary = api
                            .column(5, {
                                page: 'current'
                            })
                            .data()
                            .reduce(function(a, b) {
                                return floatVal(a) + floatVal(b);
                            }, 0);

                        // Update footer
                        $(api.column(2).footer()).html('$' + total_sales.toFixed(2));
                        $(api.column(3).footer()).html('$' + total_commission.toFixed(2));
                        $(api.column(4).footer()).html('$' + bonus.toFixed(2));
                        $(api.column(5).footer()).html('$' + total_salary.toFixed(2));
                    }
                });

                fetchAdjustmentData(seller_id, start_date, end_date);

            });

            function fetchAdjustmentData(seller_id, start_date, end_date) {
                $.ajax({
                    url: "{{ route('admin.get_adjustment', 'seller_id') }}",
                    type: 'GET',
                    data: {
                        seller_id: seller_id,
                        start_date: start_date,
                        end_date: end_date
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            $("#salesperson_name").text(response.seller[0].user_name);

                            setTimeout(() => {
                                var total_salaries = 0;
                                $('.salaires').each(function() {
                                    var salary = $(this).text().replace(/\$/g, ' ')
                                        .trim();
                                    total_salaries += parseFloat(salary);
                                });

                                var total_salary = parseFloat(total_salaries.toFixed(2));
                                var reimbursement_TotalAmount = parseFloat(response
                                    .reimbursement_TotalAmount);
                                var deduction_TotalAmount = parseFloat(response
                                    .deduction_TotalAmount);
                                var grandTotalSalary = total_salary +
                                    reimbursement_TotalAmount - deduction_TotalAmount;

                                $("#grandTotalSalary").text(grandTotalSalary.toFixed(2));
                            }, 500);
                            $("#reimbursementsTotalAmount").text(response.reimbursement_TotalAmount
                                .toFixed(2));
                            $("#deductionTotalAmount").text(response.deduction_TotalAmount.toFixed(2));

                            var reimbursementHTML = '';
                            var deductionHTML = '';

                            response.adjustments.forEach(function(adjustment) {
                                if (adjustment.type == 'reimbursements') {
                                    reimbursementHTML += `
                                                <tr>
                                                    <td>${adjustment.date}</td>
                                                    <td>${adjustment.description}</td>
                                                    <td>$${adjustment.reimbursement_deduction_amount}</td>
                                                </tr>
                                            `;
                                }
                                if (adjustment.type == 'deductions') {
                                    deductionHTML += `
                                                <tr>
                                                    <td>${adjustment.date}</td>
                                                    <td>${adjustment.description}</td>
                                                    <td>$${adjustment.reimbursement_deduction_amount}</td>
                                                </tr>
                                            `;
                                }
                            });
                            $('#reimbursements_data').html(reimbursementHTML);
                            $('#deduction_data').html(deductionHTML);
                        } else {
                            console.log('seller not found');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('An error occurred:', error);
                    }
                });
            }

            $(document).on("click", "#close_personal_SalaryReport", function() {

                $('#salaryDate').data('daterangepicker').setStartDate(moment().startOf('month'));
                $('#salaryDate').data('daterangepicker').setEndDate(moment().endOf('month'));

                $('#salary_ini_start_date').val(moment().startOf('month').format('YYYY-MM-DD'));
                $('#salary_ini_end_date').val(moment().endOf('month').format('YYYY-MM-DD'));

                seller_sales_datatable.draw();
            })

            // --- End Salary Modal ---

            // --- Bonus ---    

            $(document).on('click', ".edit_bonus", function() {

                $(this).hide();
                let update_bonus = $(this).parent().find('.update_bonus').show();
                $(this).closest('tr').find('.editBonusInput').removeAttr('readonly').select().css(
                    'user-select', 'all');
            })

            $(document).on('click', '.update_bonus', function() {

                let isValid = true;

                let bonus = $(this).closest('tr').find('.editBonusInput').val();
                let commission_id = $(this).data('commission_id');

                let bonusError = $(this).closest('tr').find('.bonusError');

                if (bonus === '' || bonus < 0) {
                    bonusError.text('Bonus is required and should be greater than or equal to 0').show();
                    isValid = false;
                } else {
                    bonusError.hide();
                }
                if (isValid) {

                    csrf = "{{ csrf_token() }}";
                    $.ajax({
                        url: "{{ route('admin.bonus') }}",
                        type: 'POST',
                        data: {
                            _token: csrf,
                            bonus: bonus,
                            commission_id: commission_id
                        },
                        beforeSend: function() {
                            $(this).text('updating..');
                        },
                        success: function(response) {
                            if (response.status === 'success') {

                                // let total_salary_tex =$(".total_salary_tex").text();
                                // console.log(total_salary_tex);                                

                                if ($.fn.DataTable.isDataTable('#seller_sales')) {
                                    var table = $('#seller_sales').DataTable();
                                    table.ajax.reload(null, false);
                                }
                                if ($.fn.DataTable.isDataTable('#salary_table')) {
                                    var table = $('#salary_table').DataTable();
                                    table.ajax.reload(null, false);
                                }

                                $('#Salary_Report').modal('show');

                                $(document).find(".editBonusInput").each(function() {
                                    let commission_id_input = $(this).data(
                                        'commission_id_input');

                                    if (commission_id_input == response.commission_id) {
                                        $(this).addClass('bonusInput').removeClass(
                                            'form-control').attr('readonly',
                                            'readonly').val("$" + response.bonus);
                                        let row = $(this).closest('tr');
                                        row.find(".update_bonus").hide();
                                        row.find(".edit_bonus").show();
                                    }
                                });

                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('An error occurred:', error);
                        }
                    });
                }
            });

            // --- End Bonus ---

            // --- Adjustment Modal ---
            $("#salesPersons").select2({
                placeholder: "Select Saleperson",
            });

            $('#select-all-btn').on('click', function() {
                if ($(this).text() === 'Select All') {

                    $('#salesPersons > option').prop("selected", true);
                    $('#salesPersons').trigger('change');
                    $(this).text('Deselect All');
                } else {

                    $('#salesPersons').val(null).trigger('change');
                    $(this).text('Select All');
                }
            });

            $('#adjustment_form').on('submit', function(e) {
                let isValid = true;

                if ($('#type').val() === '') {
                    $('#typeError').text('Type is required').show();
                    isValid = false;
                } else {
                    $('#typeError').hide();
                }

                if ($('#date').val() === '') {
                    $('#DateError').text('Date is required').show();
                    isValid = false;
                } else {
                    $('#DateError').hide();
                }
                if ($('#reimbursement_deduction_amount').val() === '') {
                    $('#AmountError').text('Amount is required').show();
                    isValid = false;
                } else {
                    $('#AmountError').hide();
                }
                if ($('#description').val() === '') {
                    $('#DescriptionError').text('Description is required').show();
                    isValid = false;
                } else {
                    $('#DescriptionError').hide();
                }
                // var isAnyChecked = $('.salesperson_id:checked').length > 0;

                if ($('#salesPersons').val() == '' || $('#salesPersons').val() == '[]') {

                    $('#SalespersonError').text('Sale Person is required').show();
                    isValid = false;

                } else {
                    $('#SalespersonError').hide();
                }

                if (!isValid) {
                    e.preventDefault();
                }
            });

            $("#adjustment_btn").click(function() {

                $.ajax({

                    url: "{{ route('admin.adjustment_saleperson') }}",
                    type: 'GET',
                    data: {

                        _token: "{{ csrf_token() }}",
                        start_date: $('#ini_start_date').val(),
                        end_date: $('#ini_end_date').val()
                    },
                    success: function(response) {

                        if (response.status == 'success') {

                            let selectBox = $('#salesPersons');
                            selectBox.empty();
                            response.seleperson.forEach(function(seleperson) {
                                selectBox.append('<option value="' +
                                    seleperson
                                    .user_id + '">' + seleperson
                                    .user_name +
                                    '</option>');
                            });

                        }
                    }
                })

            })
            // --- End Adjustment Modal ---

            // --- Salaries Datepicker ---
            $(function() {

                var start = moment().startOf('month');
                var end = moment().endOf('month');

                function cb(start, end) {
                    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format(
                        'MMMM D, YYYY'));
                }

                $('#reportrange').daterangepicker({
                    startDate: start,
                    endDate: end,
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment()
                            .subtract(1, 'month').endOf('month')
                        ]
                    }
                }, cb);

                cb(start, end);

                $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
                    let start_date = picker.startDate.format('YYYY-MM-DD');
                    let end_date = picker.endDate.format('YYYY-MM-DD');

                    var start = new Date(start_date);
                    var end = new Date(end_date);

                    $('#ini_start_date').prop("value", start_date);
                    $('#ini_end_date').prop("value", end_date);
                    $('#salary_ini_start_date').prop("value", start_date);
                    $('#salary_ini_end_date').prop("value", end_date);
                    // Trigger salaryDate update
                    $(document).find('#salaryDate').data('daterangepicker').setStartDate(start);
                    $(document).find('#salaryDate').data('daterangepicker').setEndDate(end);

                    $(document).find('#salaryDate span').html(
                        picker.startDate.format('MMMM D, YYYY') + ' - ' + picker.endDate.format(
                            'MMMM D, YYYY')
                    );
                    dataTable.draw();
                });
            });
            // --- End Salaries Datepicker ---

            // --- SalePerson Salary Datepicker ---
            $(function() {

                var start = moment().startOf('month');
                var end = moment().endOf('month');

                function cb(start, end) {
                    $(document).find('#salaryDate span').html(start.format('MMMM D, YYYY') + ' - ' + end
                        .format('MMMM D, YYYY'));
                }

                $(document).find('#salaryDate').daterangepicker({
                    startDate: start,
                    endDate: end,
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment()
                            .subtract(1, 'month').endOf('month')
                        ]
                    }
                }, cb);

                cb(start, end);

                $(document).find('#salaryDate').on('apply.daterangepicker', function(ev, picker) {
                    let start_date = picker.startDate.format('YYYY-MM-DD');
                    let end_date = picker.endDate.format('YYYY-MM-DD');

                    $(document).find('#salary_ini_start_date').prop("value", start_date);
                    $(document).find('#salary_ini_end_date').prop("value", end_date);
                    seller_sales_datatable.draw();

                    let seller_id = $(document).find("#current_seller_id").val();

                    fetchAdjustmentData(seller_id, start_date, end_date);
                });

                // $(document).on('click', '.seller_salary' ,function() {
                //     $(document).find('#salaryDate').click();
                // });

            });
            // --- End SalePerson Salary Datepicker --- 

            // --- Salaries Table ---
            var dataTable = $('#salary_table').DataTable({

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
                initComplete: (settings, json) => {
                    $('#salary_table_filter').appendTo('#tableSearch');
                    $('#salary_table_filter').appendTo('.search-input');
                },
                processing: true,
                serverSide: true,

                'ajax': {
                    type: 'POST',
                    url: "{{ route('admin.get_salaries') }}",
                    'data': function(data) {

                        var token = "{{ csrf_token() }}";
                        var start_date = $('#ini_start_date').val();
                        var end_date = $('#ini_end_date').val();
                        data._token = token;
                        data.start_date = start_date;
                        data.end_date = end_date;
                    }
                },
                columns: [{
                        data: 'DT_RowIndex'
                    },
                    {
                        data: 'user_name'
                    },
                    {
                        data: 'total_sales'
                    },
                    {
                        data: 'total_commission'
                    },
                    {
                        data: 'bonuses'
                    },
                    {
                        data: 'reimbursements'
                    },
                    {
                        data: 'deductions'
                    },
                    {
                        data: 'total_Salary'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();

                    // Function to convert string to float and handle empty strings
                    var floatVal = function(i) {
                        return typeof i === 'string' ?
                            parseFloat(i.replace(/[\$,]/g, '')) :
                            typeof i === 'number' ? i : 0;
                    };

                    // Calculate the total for each column
                    var totalSales = api
                        .column(2, {
                            page: 'current'
                        })
                        .data()
                        .reduce(function(a, b) {
                            return floatVal(a) + floatVal(b);
                        }, 0);

                    var totalCommission = api
                        .column(3, {
                            page: 'current'
                        })
                        .data()
                        .reduce(function(a, b) {
                            return floatVal(a) + floatVal(b);
                        }, 0);

                    var totalBonuses = api
                        .column(4, {
                            page: 'current'
                        })
                        .data()
                        .reduce(function(a, b) {
                            return floatVal(a) + floatVal(b);
                        }, 0);

                    var totalReimbursements = api
                        .column(5, {
                            page: 'current'
                        })
                        .data()
                        .reduce(function(a, b) {
                            return floatVal(a) + floatVal(b);
                        }, 0);

                    var totalDeductions = api
                        .column(6, {
                            page: 'current'
                        })
                        .data()
                        .reduce(function(a, b) {
                            return floatVal(a) + floatVal(b);
                        }, 0);

                    var totalSalaries = api
                        .column(7, {
                            page: 'current'
                        })
                        .data()
                        .reduce(function(a, b) {
                            return floatVal(a) + floatVal(b);
                        }, 0);

                    // Update footer
                    $(api.column(2).footer()).html('$' + totalSales.toFixed(2));
                    $(api.column(3).footer()).html('$' + totalCommission.toFixed(2));
                    $(api.column(4).footer()).html('$' + totalBonuses.toFixed(2));
                    $(api.column(5).footer()).html('$' + totalReimbursements.toFixed(2));
                    $(api.column(6).footer()).html('$' + totalDeductions.toFixed(2));
                    $(api.column(7).footer()).html('$' + totalSalaries.toFixed(2));
                }
            });
            // --- End Salaries Table ---

        });


        // --- Extra Code Here ---
        function filter_options() {
            dataTable.draw();
        }
        $('#start_date').on('dp.change', function(e) {
            filter_options();
        })
        $('#end_date').on('dp.change', function(e) {
            filter_options();
        })

        function confirm_msg(ev) {
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
            }).then(function(t) {

                if (t.value) {
                    window.location.href = urlToRedirect;
                }
            });
        }
    </script>
@endsection
