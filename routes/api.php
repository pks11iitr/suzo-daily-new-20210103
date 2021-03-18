<?php

use Illuminate\Http\Request;
$api = app('Dingo\Api\Routing\Router');

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//$api->post('login', 'MobileApps\Auth\LoginController@login');
$api->post('login-with-otp', 'MobileApps\Auth\LoginController@loginWithOtp');
$api->post('register', 'MobileApps\Auth\RegisterController@register');
//$api->post('forgot', 'MobileApps\Auth\ForgotPasswordController@forgot');
$api->post('verify-otp', 'MobileApps\Auth\OtpController@verify');
$api->post('resend-otp', 'MobileApps\Auth\OtpController@resend');
//$api->post('update-password', 'MobileApps\Auth\ForgotPasswordController@updatePassword');
$api->post('fb-login', 'MobileApps\Auth\LoginController@facebookLogin');
$api->post('gmail-login', 'MobileApps\Auth\LoginController@gmailLogin');
//test comment again


$api->get('configurations', 'MobileApps\ConfigurationController@getFilters');

$api->group(['middleware' => ['customer-api-auth']], function ($api) {
    $api->get('customer-address', 'MobileApps\Api\CustomerAddressController@getcustomeraddress');
    $api->post('add-customer-address', 'MobileApps\Api\CustomerAddressController@addcustomeraddress');
    $api->post('add-cart', 'MobileApps\Api\CartController@addcart');
    $api->get('get-cart', 'MobileApps\Api\CartController@getCartDetails');
    $api->post('update-customer-address/{id}', 'MobileApps\Api\CustomerAddressController@addressupdate');
    $api->get('get-address-detail/{id}', 'MobileApps\Api\CustomerAddressController@getaddressdetail');
    $api->post('initiate-order', 'MobileApps\Api\OrderController@initiateOrder');
    $api->get('get-payment-info/{id}', 'MobileApps\Api\PaymentController@getPaymentInfo');
    $api->post('initiate-payment/{order_id}', 'MobileApps\Api\PaymentController@initiatePayment');
    $api->post('verify-payment', 'MobileApps\Api\PaymentController@verifyPayment');

    //$api->get('stores-list', 'MobileApps\Api\StoreController@index');
    $api->get('get-profile', 'MobileApps\Api\ProfileController@index');
    $api->post('update-profile', 'MobileApps\Api\ProfileController@update');
    //$api->get('store-details/{id}', 'MobileApps\Api\StoreController@details');
    $api->get('customer-balance', 'MobileApps\Api\WalletController@userbalance');
    $api->get('wallet-history', 'MobileApps\Api\WalletController@index');
    $api->post('recharge','MobileApps\Api\WalletController@addMoney');
    $api->post('verify-recharge','MobileApps\Api\WalletController@verifyRecharge');
//

});


$api->group(['middleware' => 'mycart'], function ($api) {
    $api->get('home', 'MobileApps\Api\HomeController@index');
    $api->get('products/{cat_id}/{subcat_id?}', 'MobileApps\Api\ProductController@products');
    $api->get('special-products/{cat_id}', 'MobileApps\Api\ProductController@specialproducts');
    $api->get('section-products/{section_id}', 'MobileApps\Api\ProductController@sectionproducts');
    $api->get('search-products/{search}', 'MobileApps\Api\ProductController@search_products');
    $api->get('product-details/{product_id}', 'MobileApps\Api\ProductController@product_detail');
});

$api->get('category-list', 'MobileApps\Api\CategoryController@index');
$api->get('subcategory-list/{id}', 'MobileApps\Api\CategoryController@subcategory');
$api->get('membership-list', 'MobileApps\Api\MemberShipController@index');

//privacy-policy url
$api->get('privacy-policy', 'SuperAdmin\UrlController@privacy');
//loginbanner
$api->get('login-banner', 'MobileApps\Api\HomeController@login_Banner');
$api->get('active-address/{id}', 'MobileApps\Api\CustomerAddressController@deliveryaddressactive');

