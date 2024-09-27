$(document).ready(function () {

    $('#login_form').validate({
        rules: {
            location_name: {
                required: true
            },
            location_password: {
                required: true
            }
        },
        messages: {
            location_name: {
                required: '<small class="text-danger">Please Select Location</small>'
            },
            location_password: {
                required: '<small class="text-danger">Please Enter Password</small>'
            }
        },
        errorPlacement: function (error, element) {

            if (element.attr("name") == "location_name") {
                error.appendTo("#location_name_error");
            }
            else {
                error.insertAfter(element)
            }
        }
    });

    $('#profile_form').validate({
        rules: {
            location_name: {
                required: true
            },
            location_tax: {
                required: true
            },
            location_password: {
                required: true
            }
        },
        messages: {
            location_name: {
                required: '<small class="text-danger">Please Enter Location</small>'
            },
            location_tax: {
                required: '<small class="text-danger">Please Enter Tax</small>'
            },
            location_password: {
                required: '<small class="text-danger">Please Enter Password</small>'
            }
        }
    });

    $('#transfer_products_form').validate({
        rules: {
            to_location: {
                required: true
            }
        },
        messages: {
            to_location: {
                required: '<small class="text-danger">Please Select To Location</small>'
            }           
        }
    });

    $('#customer_form').validate({
        rules: {
            customer_name: {
                required: true
            },
            customer_email: {
                required: true,
                email: true
            },
            // customer_phone: {
            //     required: true
            // },
            customer_gender: {
                required: true
            },
            customer_dob: {
                required: true
            },
            customer_address: {
                required: true
            }
        },
        messages: {
            customer_name: {
                required: '<small class="text-danger">Please Enter Customer</small>'
            },
            customer_email: {
                required: '<small class="text-danger">Please Enter Email Address</small>',
                email: '<small class="text-danger">Please Enter Valid Email Address</small>'
            },
            // customer_phone : {
            //     required : '<small class="text-danger">Please Enter Phone</small>'
            // },
            customer_gender: {
                required: '<small class="text-danger">Please Select Gender</small>'
            },
            customer_dob: {
                required: '<small class="text-danger">Please Select Date of Birth</small>'
            },
            customer_address: {
                required: '<small class="text-danger">Please Enter Address</small>'
            }
        },
        errorPlacement: function (error, element) {

            if (element.attr("name") == "customer_gender") {
                error.appendTo($("#customer_gender_error"));
            }
            else {
                error.insertAfter(element);
            }
        }
    });

    $('#edit_customer_form').validate({
        rules: {
            customer_name: {
                required: true
            },
            customer_email: {
                required: true,
                email: true
            },
            customer_phone: {
                required: true,
                regex: /^\(\d{3}\) \d{3}-\d{4}$/
            },
            customer_gender: {
                required: true
            },
            customer_dob: {
                required: true
            },
            customer_address: {
                required: true
            }
        },
        messages: {
            customer_name: {
                required: '<small class="text-danger">Please Enter Customer</small>'
            },
            customer_email: {
                required: '<small class="text-danger">Please Enter Email Address</small>',
                email: '<small class="text-danger">Please Enter Valid Email Address</small>'
            },
            customer_phone: {
                required: '<small class="text-danger">Please Enter Phone</small>',
                regex: '<small class="text-danger">Please Enter Valid Phone Number (e.g., (123) 456-7890)</small>'

            },
            customer_gender: {
                required: '<small class="text-danger">Please Select Gender</small>'
            },
            customer_dob: {
                required: '<small class="text-danger">Please Select Date of Birth</small>'
            },
            customer_address: {
                required: '<small class="text-danger">Please Enter Address</small>'
            }
        },
        errorPlacement: function (error, element) {

            if (element.attr("name") == "customer_gender") {
                error.appendTo($("#customer_gender_error"));
            }
            else {
                error.insertAfter(element);
            }
        }
    });

    $.validator.addMethod("regex", function (value, element, regexpr) {
        return this.optional(element) || regexpr.test(value);
    }, "Invalid format.");


});