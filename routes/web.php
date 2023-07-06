<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/signin', function () {
    return view('signin');
});

Route::post('/loginMy', 'SecurityController@signin')->name('loginMy');

//User Management
Route::post('/saveUser', 'UserController@save')->name('saveUser');
Route::post('/saveCustomer', 'UserController@saveCustomer')->name('saveCustomer');

Route::post('/resetPassword', 'UserController@resetPassword')->name('resetPassword');

Route::get('/sign-up', function () {
    return view('sign-up', ['title' => 'Sign Up']);
});


Route::get('/forgot-password', function () {
    return view('forgot_password.forgot-password', ['title' => 'Forgot Password']);
});

Route::group(['middleware' => 'auth', 'prefix' => ''], function () {
    Route::get('/index', 'DashboardController@index')->name('index');
    Route::get('/logout', 'SecurityController@logoutNow')->name('logout');
    Route::post('/activateDeactivate', 'CommonController@activateDeactivate')->name('activateDeactivate');
    Route::post('/view-order-items', 'OrderController@viewOrderItems')->name('view-order-items');
    Route::get('print_invoice/{id}', 'OrderController@printInvoice')->name('print_invoice');
    Route::post('/complete-task', 'OrderController@completeTask')->name('complete-task');
    Route::post('/pending-task', 'OrderController@pendingTask')->name('pending-task');
    Route::post('/complete-order', 'OrderController@completeOrder')->name('complete-order');
    Route::post('/get-order-details', 'OrderController@getOrderDetails')->name('get-order-details');

    Route::get('/profile', 'UserController@profile')->name('profile');
    Route::post('/updateProfile', 'UserController@updateProfile')->name('updateProfile');
    Route::get('/inactiveAccount', 'UserController@inactiveAccount')->name('inactiveAccount');

    Route::group(['middleware' => 'driver-admin', 'prefix' => ''], function () {
        Route::get('/tasks', 'OrderController@tasks')->name('tasks');
        Route::get('/completed-tasks', 'OrderController@completedTasks')->name('completed-tasks');
        Route::get('/pending-tasks', 'OrderController@pendingTasks')->name('pending-tasks');
    });

    Route::group(['middleware' => 'customer-admin', 'prefix' => ''], function () {

        //order
        Route::get('/delivery-orders', 'BookingController@deliveryOrder')->name('delivery-orders');
        Route::post('/add-to-cart', 'BookingController@addToCart')->name('add-to-cart');
        Route::get('/cart', 'BookingController@cart')->name('cart');
        Route::post('/change-qty', 'BookingController@changeQty')->name('change-qty');
        Route::get('/get-total-cost', 'BookingController@getTotalCost')->name('get-total-cost');
        Route::post('/delete-chart-record', 'BookingController@deleteChartRecord')->name('delete-chart-record');
        Route::post('/pay-delivery-order', 'BookingController@payDeliveryOrder')->name('pay-delivery-order');

        //catering order
        Route::get('/catering-orders', 'BookingController@cateringOrders')->name('catering-orders');
        Route::get('/place-catering-order', 'BookingController@placeCateringOrder')->name('place-catering-order');
        Route::post('/get-total-cost-with-extra', 'BookingController@getTotalCostWithExtra')->name('get-total-cost-with-extra');
        Route::post('/pay-catering-order', 'BookingController@payCateringOrder')->name('pay-catering-order');
        Route::post('/editCatering', 'BookingController@editCatering')->name('editCatering');
        Route::post('/deleteCatering', 'BookingController@deleteCatering')->name('deleteCatering');
                
        //reservation order
        Route::get('/reservation-orders', 'BookingController@reservationOrders')->name('reservation-orders');
        Route::post('/get-product-details', 'BookingController@getProductDetails')->name('get-product-details');
        Route::get('/get-reservation-table', 'BookingController@getReservationTable')->name('get-reservation-table');
        Route::post('/add-reservation-item', 'BookingController@addReservationItem')->name('add-reservation-item');
        Route::post('/save-reservation', 'BookingController@saveReservation')->name('save-reservation');
        
        

        Route::get('/pending-order-customer', 'OrderController@pendingOrderCustomer')->name('pending-order-customer');
        Route::get('/accepted-order-customer', 'OrderController@acceptedOrderCustomer')->name('accepted-order-customer');
        Route::get('/completed-order-customer', 'OrderController@completedOrderCustomer')->name('completed-order-customer');
        Route::get('/canceled-order-customer', 'OrderController@canceledOrderCustomer')->name('canceled-order-customer');


        Route::post('/cancel-order', 'OrderController@cancelOrder')->name('cancel-order');
       
    });



    Route::group(['middleware' => 'admin', 'prefix' => ''], function () {

        //users
        Route::get('/add-employee', 'UserController@addemployeeIndex')->name('add-customer');
        Route::post('/saveUserByAdmin', 'UserController@saveUserByAdmin')->name('saveUserByAdmin');
        Route::get('/add-user', 'UserController@addUser')->name('add-user');
        Route::get('/view-users', 'UserController@viewUsersIndex')->name('view-users');
        Route::post('/updatePassword', 'UserController@updatePassword')->name('updatePassword');
        Route::post('/updateUser', 'UserController@updateUser')->name('updateUser');
        Route::post('/getUserById', 'UserController@getUserById')->name('getUserById');


        Route::get('/categories', 'CategoryController@categoriesIndex')->name('categories');
        Route::post('/saveCategory', 'CategoryController@store')->name('saveCategory');
        Route::post('/updateCategory', 'CategoryController@update')->name('updateCategory');
        Route::post('/getByCategoryId', 'CategoryController@getById')->name('getByCategoryId');

        //Product
        Route::get('/products', 'ProductController@productsIndex')->name('products');
        Route::post('/saveProduct', 'ProductController@store')->name('saveProduct');
        Route::post('/saveItemImage', 'ProductController@imageStore')->name('saveItemImage');
        Route::get('/search-product', 'ProductController@search')->name('search-product');
        Route::post('/viewProduct', 'ProductController@viewProduct')->name('viewProduct');
        Route::post('/getProductById', 'ProductController@getById')->name('getProductById');
        Route::post('/updateProduct', 'ProductController@update')->name('updateProduct');
        Route::post('/updateItemImage', 'ProductController@imageUpdate')->name('updateItemImage');

 
        //PO
        Route::get('/add-po', 'PurchaseOrderController@addPoIndex')->name('add-po');
        Route::post('/getPOTempTableData', 'PurchaseOrderController@getPOTempTableData')->name('getPOTempTableData');
        Route::post('/saveTempPO', 'PurchaseOrderController@saveTempPO')->name('saveTempPO');
        Route::post('/deleteTempPO', 'PurchaseOrderController@deleteTempPO')->name('deleteTempPO');
        Route::post('/savePO', 'PurchaseOrderController@store')->name('savePO');
        Route::get('/pending-po', 'PurchaseOrderController@pendingPoIndex')->name('pending-po');
        Route::get('/cancel-po', 'PurchaseOrderController@cancelPoIndex')->name('cancel-po');
        Route::post('/viewPOById', 'PurchaseOrderController@getById')->name('viewPOById');
        Route::post('/getPOByID', 'PurchaseOrderController@getPOByID')->name('getPOByID');
        Route::post('/updateTempPO', 'PurchaseOrderController@updateTempPO')->name('updateTempPO');
        Route::get('/search-pending-po', 'PurchaseOrderController@searchPendingPo')->name('search-pending-po');
        Route::post('/approvedPO', 'PurchaseOrderController@approvedPO')->name('approvedPO');
        Route::post('/cancelPO', 'PurchaseOrderController@cancelPO')->name('cancelPO');
        Route::get('/approved-po', 'PurchaseOrderController@approvedPoIndex')->name('approved-po');
        Route::get('/search-approved-po', 'PurchaseOrderController@searchApprovedPo')->name('search-approved-po');
        Route::get('/completed-po', 'PurchaseOrderController@completedPoIndex')->name('completed-po');
        Route::get('/search-completed-po', 'PurchaseOrderController@searchCompletedPo')->name('search-completed-po');

        //reports
        Route::get('/order-report', 'ReportController@orderReport')->name('order-report');
        Route::get('/customer-report', 'ReportController@customerReport')->name('customer-report');
        Route::get('/supplier-report', 'ReportController@supplierReport')->name('supplier-report');
        Route::get('/product-report', 'ReportController@productReport')->name('product-report');

        //Supplier
        Route::get('/suppliers', 'SupplierController@suppliersIndex')->name('suppliers');
        Route::post('/saveSupplier', 'SupplierController@store')->name('saveSupplier');
        Route::post('viewSupplier', 'SupplierController@viewTableData')->name('viewSupplier');
        Route::post('getSupplierById', 'SupplierController@getById')->name('getSupplierById');
        Route::post('updateSupplier',  'SupplierController@update')->name('updateSupplier');
        Route::post('deleteSupplier',  'SupplierController@deleteSupplier')->name('deleteSupplier');
        
 
        //Packages
        Route::get('/packages', 'PackageController@packagesIndex')->name('packages');
        Route::post('/savePackage', 'PackageController@store')->name('savePackage');
        Route::post('viewPackage', 'PackageController@viewTableData')->name('viewPackage');
        Route::post('getPackageById', 'PackageController@getById')->name('getPackageById');
        Route::post('updatePackage',  'PackageController@update')->name('updatePackage');

        //GRN
        Route::get('/add-grn', 'GRNController@addGrnIndex')->name('add-grn');
        Route::post('/getProducts', 'GRNController@getProducts')->name('getProducts');
        Route::post('/getTempTableData', 'GRNController@getTempTableData')->name('getTempTableData');
        Route::post('/saveTempProduct', 'GRNController@tempStore')->name('saveTempProduct');

        Route::post('/deleteGrnTemp', 'GRNController@deleteGrnTemp')->name('deleteGrnTemp');
        Route::post('saveGrn', 'GRNController@store')->name('saveGrn');
        Route::get('grn-history', 'GRNController@grnHistoryIndex')->name('grn-history');
        Route::get('search-grn-history', 'GRNController@search')->name('search-grn-history');
        Route::post('/getMoreByGrnID', 'GRNController@getMoreByGrnID')->name('getMoreByGrnID');
        Route::post('getGrnByID', 'GRNController@getByID')->name('getGrnByID');
        Route::post('getPOByDetails', 'GRNController@getPOByDetails')->name('getPOByDetails');
        Route::post('getTempProductId', 'GRNController@getTempProductId')->name('getTempProductId');
        Route::post('updateTempProduct', 'GRNController@updateTempProduct')->name('updateTempProduct');


        //stock maintain
        Route::get('/delivery-order-lists', 'DeliveryOrderController@deliveryOrderLists')->name('delivery-order-lists');
        Route::get('/delivery-order', 'DeliveryOrderController@deliveryOrder')->name('delivery-order');
        Route::get('/fetch-ingredient-table', 'DeliveryOrderController@ingredientTable')->name('delivery-order');
        Route::post('/get-available-qty', 'DeliveryOrderController@getAvailableQty')->name('get-available-qty');
        Route::post('/save-ingredient', 'DeliveryOrderController@saveIngredient')->name('save-ingredient');
        Route::post('/save-delivery-order', 'DeliveryOrderController@saveDeliveryOrder')->name('save-delivery-order');
        Route::post('/edit-delivery-order', 'DeliveryOrderController@editDeliveryOrder')->name('edit-delivery-order');
        Route::post('/deleteOrderItems', 'DeliveryOrderController@deleteOrderItems')->name('deleteOrderItems');
        Route::post('/updateOrderItem',  'DeliveryOrderController@updateOrderItem')->name('updateOrderItem');

        Route::get('/catering-order-lists', 'CateringOrderController@dcateringOrderLists')->name('catering-order-lists');
        Route::post('/delete-catering-ingredient', 'CateringOrderController@deleteCateringIngredient')->name('delete-catering-ingredient');
        Route::get('/catering-order', 'CateringOrderController@cateringOrder')->name('catering-order');
        Route::get('/fetch-catering-ingredient-table', 'CateringOrderController@ingredientTable')->name('fetch-catering-ingredient-table');
        Route::post('/get-available-catering-qty', 'CateringOrderController@getAvailableQty')->name('get-available-catering-qty');
        Route::post('save-catering-ingredient', 'CateringOrderController@saveIngredient')->name('save-catering-ingredient');
        Route::post('/save-catering-order', 'CateringOrderController@saveCateringOrder')->name('save-catering-order');
       
    });

    Route::group(['middleware' => 'admin_sales_person', 'prefix' => ''], function () {
        //order 
        Route::get('/pending-orders', 'OrderController@pendingOrders')->name('pending-orders');
        Route::get('/accepted-orders', 'OrderController@acceptedOrders')->name('accepted-orders');
        Route::get('/completed-orders', 'OrderController@completedOrders')->name('completed-orders');
        Route::get('/canceled-orders', 'OrderController@cancelOrders')->name('canceled-orders');
       

        Route::post('/approved-order', 'OrderController@approvedOrder')->name('approved-order');
        Route::post('/assign-driver', 'OrderController@assignDriver')->name('assign-driver');
    });
});
