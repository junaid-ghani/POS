<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Location\{
    LocationLoginController,
    LocationDashboardController,
    LocationProfileController,
    LocationLogoutController,
    LocationCustomerController,
    LocationSaleController,
    LocationStockController,
    LocationTransferProductController,
    LocationReportController,
    LocationInventoryLogController
};

// 
use App\Http\Controllers\Admin\{
    AdminLoginController,
    AdminDashboardController,
    AdminSaleController,
    AdminAssignProductController,
    AdminTransferProductController,
    AdminStockController,
    AdminInventoryLogController,
    AdminProductController,
    AdminCategoryController,
    AdminCustomerController,
    AdminLocationController,
    AdminUserController,
    AdminProfileController,
    AdminReportController,
    AdminLogoutController,
    AdminSettingController,
    AdminInventoryController,
    AdminItemController,
};


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['checklocationlogin'])->group(function () {

    Route::match(['get','post'],'/', [LocationLoginController::class, 'index'])->name('login');
    Route::post('/get_index', [LocationLoginController::class, 'get_index'])->name('get_index');

});

Route::middleware(['checklocation'])->group(function () {

    Route::prefix('location')->group(function () {

        Route::name('location.')->group(function () {
            
            //Dashboard
            Route::get('dashboard', [LocationDashboardController::class, 'index'])->name('dashboard');
            
            //Profile
            Route::match(['get','post'],'profile', [LocationProfileController::class, 'index'])->name('profile');

            //Logout
            Route::get('logout', [LocationLogoutController::class, 'index'])->name('logout');
            
            //Customer
            Route::get('customers', [LocationCustomerController::class, 'index'])->name('customers');
            Route::post('get_customers', [LocationCustomerController::class, 'get_customers'])->name('get_customers');
            Route::match(['get','post'],'add_customer', [LocationCustomerController::class, 'add_customer'])->name('add_customer');
            Route::match(['get','post'],'edit_customer', [LocationCustomerController::class, 'edit_customer'])->name('edit_customer');
            Route::match(['get','post'],'view_edit_customer', [LocationCustomerController::class, 'view_edit_customer'])->name('view_edit_customer');
            Route::match(['get','post'],'delete_customer/{id}', [LocationCustomerController::class, 'delete_customer'])->name('delete_customer');
            Route::get('customer_status/{id}/{status}', [LocationCustomerController::class, 'customer_status'])->name('customer_status');

            //Sale
            Route::get('sales', [LocationSaleController::class, 'index'])->name('sales');
            Route::post('get_sales', [LocationSaleController::class, 'get_sales'])->name('get_sales');
            Route::get('update_get_sales/{id}', [LocationSaleController::class, 'update_get_sales'])->name('update_get_sales');
            Route::post('update_sale_customer', [LocationSaleController::class, 'update_sale_customer'])->name('update_sale_customer');
            Route::match(['get','post'],'search_location', [LocationSaleController::class, 'search_location'])->name('search_location');
            Route::get('create_sale/{id}', [LocationSaleController::class, 'create_sale'])->name('create_sale');
            Route::post('add_sale', [LocationSaleController::class, 'add_sale'])->name('add_sale');            
            Route::post('add_to_cart', [LocationSaleController::class, 'add_to_cart'])->name('add_to_cart');
            Route::post('update_cart', [LocationSaleController::class, 'update_cart'])->name('update_cart');
            Route::post('used_update_cart', [LocationSaleController::class, 'used_update_cart'])->name('used_update_cart');
            Route::post('new_update_cart', [LocationSaleController::class, 'new_update_cart'])->name('new_update_cart');
            Route::post('remove_cart', [LocationSaleController::class, 'remove_cart'])->name('remove_cart');
            Route::get('clear_cart', [LocationSaleController::class, 'clear_cart'])->name('clear_cart');
            Route::get('reset_sale/{id}', [LocationSaleController::class, 'reset_sale'])->name('reset_sale');
            Route::post('location_verify_pin', [LocationSaleController::class, 'location_verify_pin'])->name('location_verify_pin');
            Route::get('getCustomers', [LocationSaleController::class, 'getCustomers'])->name('getCustomers');
            Route::post('pin_authenticate', [LocationSaleController::class, 'pin_authenticate'])->name('pin_authenticate');                        
            Route::get('get_approve_emp',[LocationSaleController::class, 'get_approve_emp'])->name('get_approve_emp');
            Route::delete('delete_sale/{id}', [LocationSaleController::class, 'delete_sale'])->name('delete_sale');
            Route::post('SendReceiptCopy', [LocationSaleController::class, 'SendReceiptCopy'])->name('SendReceiptCopy');            
            Route::post('update_commission/{id}', [LocationSaleController::class, 'update_commission'])->name('update_commission');
            // Route::get('saleGetCustomers', [LocationSaleController::class, 'saleGetCustomers'])->name('saleGetCustomers');              
            // Route::match(['get','post'],'sale_add_customer', [LocationSaleController::class, 'sale_add_customer'])->name('sale_add_customer');

            //Transfer Product
            Route::match(['get','post'],'transfer_products', [LocationTransferProductController::class, 'index'])->name('transfer_products');
            Route::post('select_transfer_products', [LocationTransferProductController::class, 'select_transfer_products'])->name('select_transfer_products');
            Route::post('select_to_location', [LocationTransferProductController::class, 'select_to_location'])->name('select_to_location');

            //Stock
            Route::get('stocks', [LocationStockController::class, 'index'])->name('stocks');
            Route::post('get_stocks', [LocationStockController::class, 'get_stocks'])->name('get_stocks');
            Route::post('update_stock', [LocationStockController::class, 'update_stock'])->name('update_stock');
            Route::get('delete_stock/{id}', [LocationStockController::class, 'delete_stock'])->name('delete_stock');
            Route::get('getProduct', [LocationStockController::class, 'getProduct'])->name('getProduct');            
            
            // Route::post('inventory_log', [LocationStockController::class, 'get_stocks'])->name('get_stocks');
            // Inventory Log
            Route::match(['get','post'],'inventory_log', [LocationInventoryLogController::class, 'index'])->name('inventory_log');            

            // report
            Route::get('summery',[LocationReportController::class, 'index'])->name('summery');
            Route::post('summery_requert', [LocationReportController::class, 'summery_requert'])->name('summery_requert');            
        });

    });

});

Route::middleware(['checkuserlogin'])->group(function () {

    Route::match(['get','post'],'admin', [AdminLoginController::class, 'index'])->name('admin');
    Route::match(['get','post'],'admin_forgot_password', [AdminLoginController::class, 'forgot_password'])->name('admin_forgot_password');

});

Route::middleware(['checkuser'])->group(function () {

    Route::prefix('admin')->group(function () {

        Route::name('admin.')->group(function () {
            
            //Dashboard
            Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

            //Sale

            Route::get('sales', [AdminSaleController::class, 'index'])->name('sales');
            Route::post('get_sales', [AdminSaleController::class, 'get_sales'])->name('get_sales');
            Route::match(['get','post'],'search_location', [AdminSaleController::class, 'search_location'])->name('search_location');            
            Route::post('update_sale_customer', [AdminSaleController::class, 'update_sale_customer'])->name('update_sale_customer');
            Route::get('create_sale/{id}', [AdminSaleController::class, 'create_sale'])->name('create_sale');
            Route::post('add_sale', [AdminSaleController::class, 'add_sale'])->name('add_sale');            
            Route::post('add_to_cart', [AdminSaleController::class, 'add_to_cart'])->name('add_to_cart');
            Route::post('update_cart', [AdminSaleController::class, 'update_cart'])->name('update_cart');
            Route::post('remove_cart', [AdminSaleController::class, 'remove_cart'])->name('remove_cart');
            Route::get('clear_cart', [AdminSaleController::class, 'clear_cart'])->name('clear_cart');
            Route::get('reset_sale/{id}', [AdminSaleController::class, 'reset_sale'])->name('reset_sale');
            Route::post('verify_pin', [AdminSaleController::class, 'verify_pin'])->name('verify_pin');            
            Route::get('update_get_sales/{id}', [AdminSaleController::class, 'update_get_sales'])->name('update_get_sales');
            Route::post('SendReceiptCopy', [AdminSaleController::class, 'SendReceiptCopy'])->name('SendReceiptCopy');
            Route::delete('delete_sale/{id}', [AdminSaleController::class, 'delete_sale'])->name('delete_sale');
            Route::post('update_commission/{id}', [AdminSaleController::class, 'update_commission'])->name('update_commission');
            Route::get('saleGetCustomers', [AdminSaleController::class, 'saleGetCustomers'])->name('saleGetCustomers');              
            Route::match(['get','post'],'sale_add_customer', [AdminSaleController::class, 'sale_add_customer'])->name('sale_add_customer');
            
            // report
            // Route::match(['get','post'],'report', [AdminReportController::class, 'report'])->name('report');
            Route::get('salary',[AdminReportController::class, 'index'])->name('salary');
            Route::post('get_salaries', [AdminReportController::class, 'get_salaries'])->name('get_salaries');
            Route::match(['get','post'],'get_salary/{id}', [AdminReportController::class, 'get_salary'])->name('get_salary');
            Route::post('adjustment', [AdminReportController::class, 'adjustment'])->name('adjustment');            
            Route::get('adjustment_saleperson', [AdminReportController::class, 'adjustment_saleperson'])->name('adjustment_saleperson');
            Route::match(['get','post'],'get_adjustment/{id}', [AdminReportController::class, 'get_adjustment'])->name('get_adjustment');            
            // Route::get('getBonus', [AdminReportController::class, 'getBonus'])->name('getBonus');
            Route::post('bonus', [AdminReportController::class, 'bonus'])->name('bonus');            
            Route::delete('delete_bonus', [AdminReportController::class, 'delete_bonus'])->name('delete_bonus');

            
            // Manage Inventery
            Route::match(['get','post'],'inventory', [AdminInventoryController::class, 'index'])->name('inventory');            

            //Assign Product
            Route::match(['get','post'],'assign_products', [AdminAssignProductController::class, 'index'])->name('assign_products');            
            Route::post('select_assign_products', [AdminAssignProductController::class, 'select_assign_products'])->name('select_assign_products');
            Route::post('select_products', [AdminAssignProductController::class, 'select_products'])->name('select_products');

            //Transfer Product
            Route::match(['get','post'],'transfer_products', [AdminTransferProductController::class, 'index'])->name('transfer_products');
            Route::post('select_transfer_products', [AdminTransferProductController::class, 'select_transfer_products'])->name('select_transfer_products');
            Route::post('select_to_location', [AdminTransferProductController::class, 'select_to_location'])->name('select_to_location');

            //Stock
            Route::get('stocks', [AdminStockController::class, 'index'])->name('stocks');
            Route::post('get_stocks', [AdminStockController::class, 'get_stocks'])->name('get_stocks');
            Route::post('update_stock', [AdminStockController::class, 'update_stock'])->name('update_stock');
            Route::get('delete_stock/{id}', [AdminStockController::class, 'delete_stock'])->name('delete_stock');

            // inventory_log
            Route::match(['get','post'],'get_inventory_log', [AdminInventoryLogController::class, 'get_inventory_log'])->name('get_inventory_log');            
            Route::get('get_inventory_log_item/{id}', [AdminInventoryLogController::class, 'get_inventory_log_item'])->name('get_inventory_log_item');
            Route::post('fix_inventory_log_item', [AdminInventoryLogController::class, 'fix_inventory_log_item'])->name('fix_inventory_log_item');

            // manage product and category
            Route::match(['get','post'],'items', [AdminItemController::class, 'index'])->name('items');

            //Product
            Route::get('products', [AdminProductController::class, 'index'])->name('products');
            Route::post('get_products', [AdminProductController::class, 'get_products'])->name('get_products');
            Route::match(['get','post'],'add_product', [AdminProductController::class, 'add_product'])->name('add_product');
            Route::post('edit_product', [AdminProductController::class, 'edit_product'])->name('edit_product');
            Route::post('view_edit_product', [AdminProductController::class, 'view_edit_product'])->name('view_edit_product');
            Route::match(['get','post'],'delete_product/{id}', [AdminProductController::class, 'delete_product'])->name('delete_product');
            Route::get('view_product/{id}', [AdminProductController::class, 'view_product'])->name('view_product');
            Route::get('product_status/{id}/{status}', [AdminProductController::class, 'product_status'])->name('product_status');

            //Route::match(['get','post'],'products_location', [AdminProductController::class, 'products_location'])->name('products_location');
            //Route::post('select_transfer_products', [AdminTransferProductController::class, 'select_transfer_products'])->name('select_transfer_products');
           // Route::post('select_to_location', [AdminTransferProductController::class, 'select_to_location'])->name('select_to_location');

            //Category
            Route::get('category', [AdminCategoryController::class, 'index'])->name('category');
            Route::post('get_category', [AdminCategoryController::class, 'get_category'])->name('get_category');
            Route::post('add_category', [AdminCategoryController::class, 'add_category'])->name('add_category');
            Route::post('edit_category', [AdminCategoryController::class, 'edit_category'])->name('edit_category');
            Route::match(['get','post'],'delete_category/{id}', [AdminCategoryController::class, 'delete_category'])->name('delete_category');
            Route::post('get_category_details', [AdminCategoryController::class, 'get_category_details'])->name('get_category_details');
            Route::get('category_status/{id}/{status}', [AdminCategoryController::class, 'category_status'])->name('category_status');

            //Customer
            Route::get('customers', [AdminCustomerController::class, 'index'])->name('customers');
            Route::post('get_customers', [AdminCustomerController::class, 'get_customers'])->name('get_customers');
            Route::match(['get','post'],'add_customer', [AdminCustomerController::class, 'add_customer'])->name('add_customer');
           
            Route::match(['get', 'post'], 'edit_customer', [AdminCustomerController::class, 'edit_customer'])->name('edit_customer');
            Route::match(['get', 'post'], 'view_edit_customer', [AdminCustomerController::class, 'view_edit_customer'])->name('view_edit_customer');

            Route::match(['get','post'],'delete_customer/{id}', [AdminCustomerController::class, 'delete_customer'])->name('delete_customer');
            Route::get('customer_status/{id}/{status}', [AdminCustomerController::class, 'customer_status'])->name('customer_status');
            Route::get('customer_transactions/{id}', [AdminCustomerController::class, 'customer_transactions'])->name('customer_transactions');

            //Location
            Route::get('locations', [AdminLocationController::class, 'index'])->name('locations');
            Route::post('get_locations', [AdminLocationController::class, 'get_locations'])->name('get_locations');
            Route::match(['get','post'],'add_location', [AdminLocationController::class, 'add_location'])->name('add_location');
            Route::match(['get','post'],'edit_location', [AdminLocationController::class, 'edit_location'])->name('edit_location');
            Route::match(['get','post'],'view_edit_location', [AdminLocationController::class, 'view_edit_location'])->name('view_edit_location');
            Route::get('location_status/{id}/{status}', [AdminLocationController::class, 'location_status'])->name('location_status');

            //User
            Route::get('users', [AdminUserController::class, 'index'])->name('users');
            Route::post('get_users', [AdminUserController::class, 'get_users'])->name('get_users');
            Route::match(['get','post'],'add_user', [AdminUserController::class, 'add_user'])->name('add_user');
            Route::match(['get','post'],'edit_user', [AdminUserController::class, 'edit_user'])->name('edit_user');
            Route::match(['get','post'],'view_edit_user', [AdminUserController::class, 'view_edit_user'])->name('view_edit_user');
            Route::get('user_status/{id}/{status}', [AdminUserController::class, 'user_status'])->name('user_status');
            
            //Profile
            Route::match(['get','post'],'profile', [AdminProfileController::class, 'index'])->name('profile');
           
            //Settings
            Route::match(['get','post'],'setting', [AdminSettingController::class, 'setting'])->name('setting');
            Route::get('getUser', [AdminSettingController::class, 'getUser'])->name('getUser');
            Route::get('getNotificationUser', [AdminSettingController::class, 'getNotificationUser'])->name('getNotificationUser');
            Route::match(['get','post'],'add_notification_item', [AdminSettingController::class, 'add_notification_item'])->name('add_notification_item');

            //Logout
            Route::get('logout', [AdminLogoutController::class, 'index'])->name('logout');

            


        });

    });

});

Route::get('approve_emp_view/{id}', [AdminSaleController::class, 'approve_emp_view'])->name('approve_emp_view');
Route::get('approve_emp/{id}', [AdminSaleController::class, 'approve_emp'])->name('approve_emp');
Route::get('request_sale_messgae/{id}', [AdminSaleController::class, 'request_sale_messgae'])->name('request_sale_messgae');            