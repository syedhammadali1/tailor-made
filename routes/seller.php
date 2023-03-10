<?php

use App\Http\Controllers\AizUploadController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Seller\AddressController;
use App\Http\Controllers\Seller\CouponController;
use App\Http\Controllers\Seller\ShopController;
use App\Http\Controllers\Seller\DashboardController;
use App\Http\Controllers\Seller\ProductController;
use App\Http\Controllers\Seller\ProductBulkUploadController;
use App\Http\Controllers\Seller\DigitalProductController;
use App\Http\Controllers\Seller\InvoiceController;
use App\Http\Controllers\Seller\OrderController;
use App\Http\Controllers\Seller\PaymentController;
use App\Http\Controllers\Seller\ProfileController;
use App\Http\Controllers\Seller\ReviewController;
use App\Http\Controllers\Seller\SellerWithdrawRequestController;
use App\Http\Controllers\Seller\CommissionHistoryController;
use App\Http\Controllers\Seller\ConversationController;
use App\Http\Controllers\Seller\NotificationController;
use App\Http\Controllers\Seller\SupportTicketController;
use App\Http\Controllers\Seller\SellerRequestController;
use App\Http\Controllers\MeasurerController;
use App\Http\Controllers\ProductForumController;


Route::match(['get', 'post'], 'measurer/register-measurer', [MeasurerController::class, 'register_measurer'])->name('measurer.register-measurer');


Route::group(['prefix' => 'seller', 'middleware' => ['seller', 'verified', 'user'], 'as' => 'seller.'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Product
    Route::controller(ProductController::class)->group(function () {
        Route::get('/products', 'index')->name('products');
        Route::get('/product/create', 'create')->name('products.create');
        Route::post('/products/store/', 'store')->name('products.store');
        Route::get('/product/{id}/edit', 'edit')->name('products.edit');
        Route::post('/products/update/{product}', 'update')->name('products.update');
        Route::get('/products/duplicate/{id}', 'duplicate')->name('products.duplicate');
        Route::post('/products/sku_combination', 'sku_combination')->name('products.sku_combination');
        Route::post('/products/sku_combination_edit', 'sku_combination_edit')->name('products.sku_combination_edit');
        Route::post('/products/add-more-choice-option', 'add_more_choice_option')->name('products.add-more-choice-option');
        Route::post('/products/seller/featured', 'updateFeatured')->name('products.featured');
        Route::post('/products/published', 'updatePublished')->name('products.published');
        Route::get('/products/destroy/{id}', 'destroy')->name('products.destroy');
    });

    // Product Bulk Upload
    Route::controller(ProductBulkUploadController::class)->group(function () {
        Route::get('/product-bulk-upload/index', 'index')->name('product_bulk_upload.index');
        Route::post('/product-bulk-upload/store', 'bulk_upload')->name('bulk_product_upload');
        Route::group(['prefix' => 'bulk-upload/download'], function() {
            Route::get('/category', 'App\Http\Controllers\ProductBulkUploadController@pdf_download_category')->name('pdf.download_category');
            Route::get('/brand', 'App\Http\Controllers\ProductBulkUploadController@pdf_download_brand')->name('pdf.download_brand');
        });
    });

    // Digital Product
    Route::controller(DigitalProductController::class)->group(function () {
        Route::get('/digitalproducts', 'index')->name('digitalproducts');
        Route::get('/digitalproducts/create', 'create')->name('digitalproducts.create');
        Route::post('/digitalproducts/store', 'store')->name('digitalproducts.store');
        Route::get('/digitalproducts/{id}/edit', 'edit')->name('digitalproducts.edit');
        Route::post('/digitalproducts/update/{id}', 'update')->name('digitalproducts.update');
        Route::get('/digitalproducts/destroy/{id}', 'destroy')->name('digitalproducts.destroy');
        Route::get('/digitalproducts/download/{id}', 'download')->name('digitalproducts.download');
    });

    //Upload
    Route::controller(AizUploadController::class)->group(function () {
        Route::any('/uploads', 'index')->name('uploaded-files.index');
        Route::any('/uploads/create', 'create')->name('uploads.create');
        Route::any('/uploads/file-info', 'file_info')->name('my_uploads.info');
        Route::get('/uploads/destroy/{id}', 'destroy')->name('my_uploads.destroy');
    });

    //Coupon
    Route::resource('coupon', CouponController::class);
    Route::controller(CouponController::class)->group(function () {
        Route::post('/coupon/get_form', 'get_coupon_form')->name('coupon.get_coupon_form');
        Route::post('/coupon/get_form_edit', 'get_coupon_form_edit')->name('coupon.get_coupon_form_edit');
        Route::get('/coupon/destroy/{id}', 'destroy')->name('coupon.destroy');
    });

    //Order
    Route::resource('orders', OrderController::class);
    Route::controller(OrderController::class)->group(function () {
        //Delivery Boy Assign
        Route::post('/orders/delivery-boy-assign', 'assign_delivery_boy')->name('orders.delivery-boy-assign');
        Route::post('/orders/update_delivery_status', 'update_delivery_status')->name('orders.update_delivery_status');
        Route::post('/orders/update_delivery_time', 'update_delivery_time')->name('orders.update_delivery_time');
        Route::post('/orders/update_payment_status', 'update_payment_status')->name('orders.update_payment_status');
    });



    Route::get('invoice/{order_id}',[InvoiceController::class, 'invoice_download'])->name('invoice.download');

    //Review
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews');

    // Product Forum
    Route::controller(ProductForumController::class)->group(function () {
        Route::get('/all-forums-seller', 'all_forum_seller')->name('forum');

        Route::post('/add-forums-seller', 'add_forum_seller')->name('forum.add');

    });

    //Shop
    Route::controller(ShopController::class)->group(function () {
        Route::get('/shop', 'index')->name('shop.index');
        Route::post('/shop/update', 'update')->name('shop.update');
        Route::get('/shop/apply_for_verification', 'verify_form')->name('shop.verify');
        Route::post('/shop/verification_info_store', 'verify_form_store')->name('shop.verify.store');
    });

    //Payments
    Route::resource('payments', PaymentController::class);

    // Profile Settings
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'index')->name('profile.index');
        Route::post('/profile/update/{id}', 'update')->name('profile.update');

        //custom-routes
        Route::get('/getProfile/{id}', 'getProfile')->name('profile.get');
    });

    // Address
    Route::resource('addresses', AddressController::class);
    Route::controller(AddressController::class)->group(function () {
        Route::post('/get-states', 'getStates')->name('get-state');
        Route::post('/get-cities', 'getCities')->name('get-city');
        Route::post('/address/update/{id}', 'update')->name('addresses.update');
        Route::get('/addresses/destroy/{id}', 'destroy')->name('addresses.destroy');
        Route::get('/addresses/set_default/{id}', 'set_default')->name('addresses.set_default');
    });

    // Money Withdraw Requests
    Route::controller(SellerWithdrawRequestController::class)->group(function () {
        Route::get('/money-withdraw-requests', 'index')->name('money_withdraw_requests.index');
        Route::post('/money-withdraw-request/store', 'store')->name('money_withdraw_request.store');
    });

    // Commission History
    Route::get('commission-history', [CommissionHistoryController::class, 'index'])->name('commission-history.index');

    //Conversations
    Route::controller(ConversationController::class)->group(function () {
        Route::get('/conversations', 'index')->name('conversations.index');
        Route::get('/conversations/show/{id}', 'show')->name('conversations.show');
        Route::post('conversations/refresh', 'refresh')->name('conversations.refresh');
        Route::post('conversations/message/store', 'message_store')->name('conversations.message_store');
    });

    // Support Ticket
    Route::controller(SupportTicketController::class)->group(function () {
        Route::get('/support_ticket', 'index')->name('support_ticket.index');
        Route::post('/support_ticket/store', 'store')->name('support_ticket.store');
        Route::get('/support_ticket/show/{id}', 'show')->name('support_ticket.show');
        Route::post('/support_ticket/reply', 'ticket_reply_store')->name('support_ticket.reply_store');
    });

    // Notifications
    Route::get('all-notification', [NotificationController::class, 'index'])->name('all-notification');

    Route::controller(SellerRequestController::class)->group(function () {
        Route::get('/requests', 'index')->name('requests.index');
        Route::get('/nearbyMeasurers/{id}', 'nearby_measurers')->name('requests.nearby_measurers');


        Route::get('measurer/appointment/show/{id}', 'appointment_show')->name('measurer.appointment.show');
        Route::post('membership/request', 'membership_request_store')->name('membership_request_store');
    });
    Route::controller(MeasurerController::class)->group(function () {



        Route::post('measurer/conversations/create', [MeasurerController::class, 'measurer_conversations_create'])->name('measurer.conversations.create');

        Route::match(['get', 'post'], 'measurer/conversations/show/{id}', [MeasurerController::class, 'measurer_conversations'])->name('measurer.conversations');

        Route::post('measurer/conversations/refresh', 'refresh')->name('measurer.conversations.refresh');
        Route::get('measurer/appointments/create', 'appointment_create')->name('measurer.appointment.create');

        Route::post('measurer/set/commission', 'set_commission')->name('measurer.appointment.commission');



    });

    Route::get('model_list', [HomeController::class, 'model_list'])->name('model_list');
    Route::get('single_model_gallery/{id}', [HomeController::class, 'single_model_gallery'])->name('single_model_gallery');
    Route::post('model_conversations_create/{model_id}', [HomeController::class, 'model_conversations_create'])->name('model_conversations_create');

    Route::match(['get', 'post'], 'model_conversations/{conversation_id}/{model_id}',  [HomeController::class, 'model_conversations'])->name('model_conversations');
    Route::post('model_appointment_create', [HomeController::class, 'model_appointment_create'])->name('model_appointment_create');
    Route::get('requests_to_be_model', [HomeController::class, 'requests_to_be_model'])->name('requests_to_be_model');

    Route::get('seller/nearbyMeasurers/customer/{id}', [HomeController::class, 'nearby_models'])->name('requests.nearby_models');

});

