<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

//Route::get('/dashboard', function () {
//    return view('dashboard');
//})->middleware(['auth'])->name('dashboard');


Route::group(['middleware'=>['auth', 'acl'], 'is'=>'admin'], function() {

    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

    Route::get('/dashboard', 'SuperAdmin\DashboardController@index')->name('home');

    Route::group(['prefix' => 'banners'], function () {
        Route::get('/', 'SuperAdmin\BannerController@index')->name('banners.list');
        Route::get('create', 'SuperAdmin\BannerController@create')->name('banners.create');
        Route::post('store', 'SuperAdmin\BannerController@store')->name('banners.store');
        Route::get('edit/{id}', 'SuperAdmin\BannerController@edit')->name('banners.edit');
        Route::post('update/{id}', 'SuperAdmin\BannerController@update')->name('banners.update');
        Route::get('delete/{id}', 'SuperAdmin\BannerController@delete')->name('banners.delete');
        Route::post('special-product/{id}', 'SuperAdmin\BannerController@specialProduct')->name('banners.special.product');
        Route::get('special-product-delete/{id}', 'SuperAdmin\BannerController@productDelete')->name('special.product.delete');

    });


    Route::group(['prefix'=>'homesection'], function(){
        Route::get('/','SuperAdmin\HomeSectionController@index')->name('homesection.list');
        //add banner
        Route::get('banner-create','SuperAdmin\HomeSectionController@bannercreate')->name('homesection.bannercreate');
        Route::post('banner-store','SuperAdmin\HomeSectionController@bannerstore')->name('homesection.bannerstore');
        Route::get('banner-edit/{id}','SuperAdmin\HomeSectionController@banneredit')->name('homesection.banneredit');
        Route::post('banner-update/{id}','SuperAdmin\HomeSectionController@bannerupdate')->name('homesection.bannerupdate');
        //super grocery day
        Route::get('super-grocery-create','SuperAdmin\HomeSectionController@supergrocerycreate')->name('homesection.supergrocerycreate');
        Route::post('super-grocery-store','SuperAdmin\HomeSectionController@supergrocerystore')->name('homesection.supergrocerystore');
        Route::get('productedit/{id}','SuperAdmin\HomeSectionController@productedit')->name('homesection.productedit');
        Route::post('productupdate/{id}','SuperAdmin\HomeSectionController@productupdate')->name('homesection.productupdate');
        Route::post('productimage/{id}','SuperAdmin\HomeSectionController@productImage')->name('homesection.productimage');
        Route::get('productdelete/{id}','SuperAdmin\HomeSectionController@productdelete')->name('homesection.productdelete');
        Route::get('sub-category-create','SuperAdmin\HomeSectionController@subcategorycreate')->name('homesection.subcategorycreate');
        Route::post('sub-category-store','SuperAdmin\HomeSectionController@subcategorystore')->name('homesection.subcategorystore');
        Route::get('sub-category-edit/{id}','SuperAdmin\HomeSectionController@subcategoryedit')->name('homesection.subcategoryedit');
        Route::post('sub-category-update/{id}','SuperAdmin\HomeSectionController@subcategoryupdate')->name('homesection.subcategoryupdate');
        Route::post('subcategoryimage/{id}','SuperAdmin\HomeSectionController@subcategoryimage')->name('homesection.subcategoryimage');
        Route::get('subdelete/{id}','SuperAdmin\HomeSectionController@subdelete')->name('homesection.subdelete');
        Route::get('home-section-delete/{id}','SuperAdmin\HomeSectionController@homesectiondelete')->name('homesection.homesectiondelete');

    });

    Route::group(['prefix'=>'category'], function(){
            Route::get('/','SuperAdmin\CategoryController@index')->name('category.list');
            Route::get('create','SuperAdmin\CategoryController@create')->name('category.create');
            Route::get('edit/{id}','SuperAdmin\CategoryController@edit')->name('category.edit');
            Route::post('store','SuperAdmin\CategoryController@store')->name('category.store');

            Route::post('update/{id}','SuperAdmin\CategoryController@update')->name('category.update');
    });

    Route::group(['prefix'=>'subcategory'], function(){

            Route::get('/','SuperAdmin\SubCategoryController@index')->name('subcategory.list');
            Route::get('create','SuperAdmin\SubCategoryController@create')->name('subcategory.create');
            Route::get('edit/{id}','SuperAdmin\SubCategoryController@edit')->name('subcategory.edit');
            Route::post('store','SuperAdmin\SubCategoryController@store')->name('subcategory.store');

            Route::post('update/{id}','SuperAdmin\SubCategoryController@update')->name('subcategory.update');

    });

    Route::group(['prefix'=>'product'], function(){

            Route::get('/','SuperAdmin\ProductController@index')->name('product.list');
            Route::get('create','SuperAdmin\ProductController@create')->name('product.create');
            Route::get('edit/{id}','SuperAdmin\ProductController@edit')->name('product.edit');
            Route::get('size-images','SuperAdmin\ProductController@allimages')->name('product.size.images');
            Route::get('bulk-upload','SuperAdmin\ProductController@bulk_upload_form')->name('product.bulk.form');
            Route::post('store','SuperAdmin\ProductController@store')->name('product.store');

            Route::post('update/{id}','SuperAdmin\ProductController@update')->name('product.update');
            Route::post('product-sizeprice/{id}','SuperAdmin\ProductController@sizeprice')->name('product.sizeprice');
            Route::post('size-update','SuperAdmin\ProductController@updatesizeprice')->name('product.size.update');
            Route::get('delete/{id}','SuperAdmin\ProductController@delete')->name('product.delete');
            Route::post('product-category-create/{id}','SuperAdmin\ProductController@productcategory')->name('product.category.create');


            Route::post('document/{id}','SuperAdmin\ProductController@document')->name('product.document');
            Route::post('bulk-upload','SuperAdmin\ProductController@bulk_upload')->name('product.bulk.upload');

        });


    Route::group(['prefix'=>'coupon'], function(){
        Route::get('/','SuperAdmin\CouponController@index')->name('coupon.list');
        Route::get('create','SuperAdmin\CouponController@create')->name('coupon.create');
        Route::get('edit/{id}','SuperAdmin\CouponController@edit')->name('coupon.edit');
        Route::post('store','SuperAdmin\CouponController@store')->name('coupon.store');
        Route::post('update/{id}','SuperAdmin\CouponController@update')->name('coupon.update');

    });

    Route::group(['prefix'=>'membership'], function(){

        Route::get('/','SuperAdmin\MembershipController@index')->name('membership.list');
        Route::get('create','SuperAdmin\MembershipController@create')->name('membership.create');
        Route::post('store','SuperAdmin\MembershipController@store')->name('membership.store');
        Route::get('edit/{id}','SuperAdmin\MembershipController@edit')->name('membership.edit');
        Route::post('update/{id}','SuperAdmin\MembershipController@update')->name('membership.update');

    });

    Route::group(['prefix'=>'news'], function(){
        Route::get('/','SuperAdmin\NewsUpdateController@index')->name('news.list');
        Route::get('create','SuperAdmin\NewsUpdateController@create')->name('news.create');
        Route::post('store','SuperAdmin\NewsUpdateController@store')->name('news.store');
        Route::get('edit/{id}','SuperAdmin\NewsUpdateController@edit')->name('news.edit');
        Route::post('update/{id}','SuperAdmin\NewsUpdateController@update')->name('news.update');

    });

    Route::group(['prefix'=>'story'], function(){
        Route::get('/','SuperAdmin\StoryController@index')->name('story.list');
        Route::get('create','SuperAdmin\StoryController@create')->name('story.create');
        Route::post('store','SuperAdmin\StoryController@store')->name('story.store');
        Route::get('edit/{id}','SuperAdmin\StoryController@edit')->name('story.edit');
        Route::post('update/{id}','SuperAdmin\StoryController@update')->name('story.update');

    });

    Route::group(['prefix'=>'orders'], function(){

            Route::get('/','SuperAdmin\OrderController@index')->name('orders.list');
            Route::get('details/{id}','SuperAdmin\OrderController@details')->name('order.details');
            Route::post('invoice-update/{id}','SuperAdmin\InvoiceController@update')->name('order.invoice.update');

            Route::get('change-status/{id}','SuperAdmin\OrderController@changeStatus')->name('order.status.change');
            Route::get('change-payment-status/{id}','SuperAdmin\OrderController@changePaymentStatus')->name('payment.status.change');
            Route::post('changeRider/{id}','SuperAdmin\OrderController@changeRider')->name('rider.change');
            Route::get('add-cashback/{id}/{type}','SuperAdmin\OrderController@addCashback')->name('add.cashback');


    });

    Route::group(['prefix'=>'delivery'], function(){

        Route::get('/','SuperAdmin\DeliveryController@index')->name('delivery.list');


    });


    Route::group(['prefix'=>'reports'], function(){

        Route::get('sales-report', 'SuperAdmin\ReportDownloader@downloadSalesReport')->name('sales.report');

        Route::get('order-report', 'SuperAdmin\ReportDownloader@downloadOrderReport')->name('order.report');

    });


    Route::group(['prefix'=>'wallet'], function(){

        Route::post('add-remove-wallet-balance', 'SuperAdmin\WalletController@addremove')->name('wallet.add.remove');

        Route::get('get-wallet-balance/{id}', 'SuperAdmin\WalletController@getbalance')->name('user.wallet.balance');

        Route::get('get-wallet-history/{id}', 'SuperAdmin\WalletController@getWalletHistory')->name('user.wallet.history');

    });

    Route::group(['prefix'=>'area'], function(){
            Route::get('/','SuperAdmin\AreaController@index')->name('area.list');
            Route::get('create','SuperAdmin\AreaController@create')->name('area.create');
            Route::get('edit/{id}','SuperAdmin\AreaController@edit')->name('area.edit');

            Route::post('store','SuperAdmin\AreaController@store')->name('area.store');
            Route::post('update/{id}','SuperAdmin\AreaController@update')->name('area.update');

    });

    Route::group(['prefix'=>'customer'], function(){
            Route::get('/','SuperAdmin\CustomerController@index')->name('customer.list');
            Route::get('edit/{id}','SuperAdmin\CustomerController@edit')->name('customer.edit');


            Route::post('update/{id}','SuperAdmin\CustomerController@update')->name('customer.update');
            Route::post('send_message','SuperAdmin\CustomerController@send_message')->name('customer.send_message');


    });

    Route::group(['prefix'=>'notification'], function(){
            Route::get('create','SuperAdmin\NotificationController@create')->name('notification.create');
            Route::post('store','SuperAdmin\NotificationController@store')->name('notification.store');
    });



});

Route::get('invoice/{id}', 'SuperAdmin\InvoiceController@download')->name('download.invoice');

require __DIR__.'/auth.php';
