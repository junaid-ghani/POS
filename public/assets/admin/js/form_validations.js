$(document).ready(function () {    

    $('#login_form').validate({ 
        rules: {
            user_email: {
                required: true,
                email: true
            },
            user_password: {
                required: true
            }
        },
        messages :{
            user_email : {  
                required : '<small class="text-danger">Please Enter Email Address</small>',
                email : '<small class="text-danger">Please Enter Valid Email Address</small>'
            },
            user_password : {
                required : '<small class="text-danger">Please Enter Password</small>'
            }
        }
    });

    $('#forgot_password_form').validate({ 
        rules: {
            user_email: {
                required: true,
                email: true
            }
        },
        messages :{
            user_email : {  
                required : '<small class="text-danger">Please Enter Email Address</small>',
                email : '<small class="text-danger">Please Enter Valid Email Address</small>'
            }
        }
    });

    $('#profile_form').validate({ 
        rules: {
            user_name: {
                required: true
            },
            user_email: {
                required: true,
                email: true
            },
            user_phone: {
                required: true
            },
            user_password: {
                required: true
            }
        },
        messages :{
            user_name : {
                required : '<small class="text-danger">Please Enter Name</small>'
            },
            user_email : {  
                required : '<small class="text-danger">Please Enter Email Address</small>',
                email : '<small class="text-danger">Please Enter Valid Email Address</small>'
            },
            user_phone : {
                required : '<small class="text-danger">Please Enter Phone</small>'
            },
            user_password : {
                required : '<small class="text-danger">Please Enter Password</small>'
            }
        }
    });
    
    $('#search_location_form').validate({ 
        rules: {
            location: {
                required: true
            }
        },
        messages :{
            location : {
                required : '<small class="text-danger">Please Select Location</small>'
            }
        },
        errorPlacement: function(error, element) {

            if(element.attr("name") == "location") 
            {
                error.appendTo($("#location_error"));
            }
            else 
            {
                error.insertAfter(element);
            }
        }
    });


    $('#sale_form').validate({ 
        rules: {
            // sale_customer: {
            //     required: true
            // },
            "sale_sellers[]": {
                required: true
            }
        },
        messages :{
            // sale_customer : {
            //     required : '<small class="text-danger">Please Select Customer</small>'
            // },
            "sale_sellers[]" : {
                required : '<small class="text-danger">Please Select Sellers</small>'
            }
        },
        errorPlacement: function(error, element) {

            // if(element.attr("name") == "sale_customer") 
            // {
            //     error.appendTo($("#sale_customer_error"));
            // }
            // else 
            if(element.attr("name") == "sale_sellers[]") 
            {
                error.appendTo($("#sale_sellers_error"));
            }
            else 
            {
                error.insertAfter(element);
            }
        }
    });

    $('#assign_products_form').validate({ 
        rules: {
            stock_location: {
                required: true
            },
            stock_category: {
                required: true
            },
            "stock_product[]": {
                required: true
            }
        },
        messages :{
            stock_location : {
                required : '<small class="text-danger">Please Select Location</small>'
            },
            stock_category : {  
                required : '<small class="text-danger">Please Select Category</small>'
            },
            "stock_product[]" : {
                required : '<small class="text-danger">Please Select Product</small>'
            }
        },
        errorPlacement: function(error, element) {

            if(element.attr("name") == "stock_location") 
            {
                error.appendTo($("#stock_location_error"));
            }
            else if(element.attr("name") == "stock_category") 
            {
                error.appendTo($("#stock_category_error"));
            }
            else if(element.attr("name") == "stock_product[]") 
            {
                error.appendTo($("#stock_product_error"));
            }
            else 
            {
                error.insertAfter(element);
            }
        }
    });

    $('#transfer_products_form').validate({ 
        rules: {
            from_location: {
                required: true
            },
            to_location: {
                required: true
            }
        },
        messages :{
            from_location : {
                required : '<small class="text-danger">Please Select From Location</small>'
            },
            to_location : {  
                required : '<small class="text-danger">Please Select To Location</small>'
            }
        },
        errorPlacement: function(error, element) {

            if(element.attr("name") == "from_location") 
            {
                error.appendTo($("#from_location_error"));
            }
            else if(element.attr("name") == "to_location") 
            {
                error.appendTo($("#to_location_error"));
            }
            else 
            {
                error.insertAfter(element);
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
        messages :{
            customer_name : {
                required : '<small class="text-danger">Please Enter Customer</small>'
            },
            customer_email : {  
                required : '<small class="text-danger">Please Enter Email Address</small>',
                email : '<small class="text-danger">Please Enter Valid Email Address</small>'
            },
            // customer_phone : {
            //     required : '<small class="text-danger">Please Enter Phone</small>'
            // },
            customer_gender : {
                required : '<small class="text-danger">Please Select Gender</small>'
            },
            customer_dob : {
                required : '<small class="text-danger">Please Select Date of Birth</small>'
            },
            customer_address : {
                required : '<small class="text-danger">Please Enter Address</small>'
            }
        },
        errorPlacement: function(error, element) {

            if(element.attr("name") == "customer_gender") 
            {
                error.appendTo($("#customer_gender_error"));
            }
            else 
            {
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
        messages :{
            customer_name : {
                required : '<small class="text-danger">Please Enter Customer</small>'
            },
            customer_email : {  
                required : '<small class="text-danger">Please Enter Email Address</small>',
                email : '<small class="text-danger">Please Enter Valid Email Address</small>'
            },
            customer_phone : {
                required : '<small class="text-danger">Please Enter Phone</small>',
                regex: '<small class="text-danger">Please Enter Valid Phone Number (e.g., (123) 456-7890)</small>'

            },
            customer_gender : {
                required : '<small class="text-danger">Please Select Gender</small>'
            },
            customer_dob : {
                required : '<small class="text-danger">Please Select Date of Birth</small>'
            },
            customer_address : {
                required : '<small class="text-danger">Please Enter Address</small>'
            }
        },
        errorPlacement: function(error, element) {

            if(element.attr("name") == "customer_gender") 
            {
                error.appendTo($("#customer_gender_error"));
            }
            else 
            {
                error.insertAfter(element);
            }
        }
    });

    $.validator.addMethod("regex", function(value, element, regexpr) {
        return this.optional(element) || regexpr.test(value);
    }, "Invalid format.");

    $('#category_form').validate({ 
        rules: {
            category_name: {
                required: true
            }
        },
        messages :{
            category_name : {
                required : '<small class="text-danger">Please Enter Category</small>'
            }
        }
    });

    $('#edit_category_form').validate({ 
        rules: {
            category_name: {
                required: true
            }
        },
        messages :{
            category_name : {
                required : '<small class="text-danger">Please Enter Category</small>'
            }
        }
    });

    $('#product_form').validate({ 
        rules: {
            product_category: {
                required: true
            },
            product_name: {
                required: true
            },
            product_code: {
                required: true
            },
            product_cost_price: {
                required: true
            },
            product_min_price: {
                required: true
            },
            product_retail_price: {
                required: true
            },
            product_priority: {
                required: true
            }
        },
        messages :{
            product_category : {
                required : '<small class="text-danger">Please Select Category</small>'
            },
            product_name : {
                required : '<small class="text-danger">Please Enter Product</small>'
            },
            product_code : {  
                required : '<small class="text-danger">Please Enter Code</small>'
            },
            product_cost_price : {
                required : '<small class="text-danger">Please Enter Cost Price</small>'
            },
            product_min_price : {
                required : '<small class="text-danger">Please Enter Minimum Price</small>'
            },
            product_retail_price : {
                required : '<small class="text-danger">Please Enter Retail Price</small>'
            },
            product_priority: {
                required: "<small style='color:red'>Please Select Priority"
            }
        },
        errorPlacement: function(error, element) {

            if(element.attr("name") == "product_category") 
            {
                error.appendTo($("#product_category_error"));
            } 
            else if(element.attr("name") == "product_priority") 
            {
                error.appendTo($("#product_priority_error"));
            } 
            else 
            {
                error.insertAfter(element);
            }
        }
    });

    $('#location_form').validate({ 
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
        messages :{
            location_name : {
                required : '<small class="text-danger">Please Enter Location</small>'
            },
            location_tax : {
                required : '<small class="text-danger">Please Enter Tax</small>'
            },
            location_password : {
                required : '<small class="text-danger">Please Enter Password</small>'
            }
        }
    });

    $('#edit_location_form').validate({ 
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
        messages :{
            location_name : {
                required : '<small class="text-danger">Please Enter Location</small>'
            },
            location_tax : {
                required : '<small class="text-danger">Please Enter Tax</small>'
            },
            location_password : {
                required : '<small class="text-danger">Please Enter Password</small>'
            }
        }
    });

    $('#user_form').validate({ 
        rules: {
            user_name: {
                required: true
            },
            user_role: {
                required: true
            },
            user_email: {
                // required: true,
                email: true
            },
            user_phone: {
                required: true
            },
            user_password: {
                // required: true
            },
            user_salary_type: {
                required: true
            },
            pin: {
                required: true,              
            },
            user_commission: {
                required: true
            },
            "commissions[FROM][1]": {
                required: true
            },
            "commissions[TO][1]": {
                required: true
            },
            "commissions[AMOUNT][1]": {
                required: true
            },
            "commissions[FROM][2]": {
                required: true
            },
            "commissions[TO][2]": {
                required: true
            },
            "commissions[AMOUNT][2]": {
                required: true
            },            
            "commissions[AMOUNT][3]": {
        
                required: function(element) {
                    return $('#enable_disableCheckbox').is(':checked');
                }
            }

        },
     

        messages :{
            user_name : {
                required : '<small class="text-danger">Please Enter Name</small>'
            },
            user_role : {
                required : '<small class="text-danger">Please Select Role</small>'
            },
            user_email : {  
                // required : '<small class="text-danger">Please Enter Email Address</small>',
                email : '<small class="text-danger">Please Enter Valid Email Address</small>'
            },
            user_phone : {
                required : '<small class="text-danger">Please Enter Phone</small>'
            },
            user_password : {
                // required : '<small class="text-danger">Please Enter Password</small>'
            },
            user_salary_type : {
                required : '<small class="text-danger">Please Select Salary Type</small>'
            },
            pin : {
                required : '<small class="text-danger">Please Enter Pin</small>'              
            },
            user_commission: {
                required: "<small style='color:red'>Please Enter Commission"
            },
            "commissions[FROM][1]": {
                required: "<small style='color:red'>Please Enter From"
            },
            "commissions[TO][1]": {
                required: "<small style='color:red'>Please Enter To"
            },
            "commissions[AMOUNT][1]": {
                required: "<small style='color:red'>Please Enter Commission"
            },
            "commissions[FROM][2]": {
                required: "<small style='color:red'>Please Enter From"
            },
            "commissions[TO][2]": {
                required: "<small style='color:red'>Please Enter To"
            },
            "commissions[AMOUNT][2]": {
                required: "<small style='color:red'>Please Enter Commission"
            },
            "commissions[AMOUNT][3]": {
                required: "<small style='color:red'>Please Enter Commission"
            }
        },
        errorPlacement: function(error, element) {

            if(element.attr("name") == "user_role") 
            {
                error.appendTo($("#user_role_error"));
            } 
            else if(element.attr("name") == "user_salary_type") 
            {
                error.appendTo($("#user_salary_type_error"));
            } 
            else 
            {
                error.insertAfter(element);
            }
        }
    });


    $('#edit_user_form').validate({ 
        rules: {
            user_name: {
                required: true
            },
            user_role: {
                required: true
            },
            user_email: {
                // required: true,
                email: true
            },
            user_phone: {
                required: true
            },
            user_password: {
                // required: true
            },
            user_salary_type: {
                required: true
            },
            pin: {
                required: true,              
            },
            user_commission: {
                required: true
            },
            "commissions[FROM][1]": {
                required: true
            },
            "commissions[TO][1]": {
                required: true
            },
            "commissions[AMOUNT][1]": {
                required: true
            },
            "commissions[FROM][2]": {
                required: true
            },
            "commissions[TO][2]": {
                required: true
            },
            "commissions[AMOUNT][2]": {
                required: true
            },            
            "commissions[AMOUNT][3]": {
        
                required: function(element) {
                    return $('#enable_disableCheckbox_edit').is(':checked');
                }
            }

        },
     

        messages :{
            user_name : {
                required : '<small class="text-danger">Please Enter Name</small>'
            },
            user_role : {
                required : '<small class="text-danger">Please Select Role</small>'
            },
            user_email : {  
                // required : '<small class="text-danger">Please Enter Email Address</small>',
                email : '<small class="text-danger">Please Enter Valid Email Address</small>'
            },
            user_phone : {
                required : '<small class="text-danger">Please Enter Phone</small>'
            },
            user_password : {
                // required : '<small class="text-danger">Please Enter Password</small>'
            },
            user_salary_type : {
                required : '<small class="text-danger">Please Select Salary Type</small>'
            },
            pin : {
                required : '<small class="text-danger">Please Enter Pin</small>'              
            },
            user_commission: {
                required: "<small style='color:red'>Please Enter Commission"
            },
            "commissions[FROM][1]": {
                required: "<small style='color:red'>Please Enter From"
            },
            "commissions[TO][1]": {
                required: "<small style='color:red'>Please Enter To"
            },
            "commissions[AMOUNT][1]": {
                required: "<small style='color:red'>Please Enter Commission"
            },
            "commissions[FROM][2]": {
                required: "<small style='color:red'>Please Enter From"
            },
            "commissions[TO][2]": {
                required: "<small style='color:red'>Please Enter To"
            },
            "commissions[AMOUNT][2]": {
                required: "<small style='color:red'>Please Enter Commission"
            },
            "commissions[AMOUNT][3]": {
                required: "<small style='color:red'>Please Enter Commission"
            }
        },
        errorPlacement: function(error, element) {

            if(element.attr("name") == "user_role") 
            {
                error.appendTo($("#user_role_error"));
            } 
            else if(element.attr("name") == "user_salary_type") 
            {
                error.appendTo($("#user_salary_type_error"));
            } 
            else 
            {
                error.insertAfter(element);
            }
        }
    });

});