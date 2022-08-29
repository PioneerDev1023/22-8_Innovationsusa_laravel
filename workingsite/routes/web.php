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

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Route::get('/', 'Controller@getWelcome')->middleware('cache.headers:private,max_age=3600');

Route::get('product/{id}', 'ProductController@show')->middleware('cache.headers:private,max_age=3600');
Route::get('product/{id}/{filter}', 'ProductController@filter')->middleware('cache.headers:private,max_age=3600');
Route::post('product/{id}', 'ProductController@show'); // all-wallcovering
Route::post('product/{id}/{filter}', 'ProductController@filter')->middleware('cache.headers:private,max_age=3600');

Route::get('item/{id}', 'ItemController@getItem')->name('pdp')->middleware('cache.headers:private,max_age=3600');
Route::get('item/{id}/{sku}', 'ItemController@getSku')->name('pdp-sku')->middleware('cache.headers:private,max_age=3600');

// yardage calculator
Route::get('yardage-calculator', 'ItemController@itemCalculator')->middleware('cache.headers:private,max_age=3600');
Route::get('yardage-calculator/{id}', 'ItemController@itemCalculator');
Route::get('yardage-calculator/{id}/{sku}', 'ItemController@skuCalculator');
Route::post('/download-image', 'AjaxController@downloadImage');

// login
// php artisan make:controller LoginController
//Route::get('login', 'LoginController@login');


// pdp AJAX
Route::post('/item/{id}', 'ItemController@getItemAjax');
Route::post('/item-filter/{id}', 'ItemController@getItemFilterAjax');

// carts AJAX
Route::post('item-add', 'CartController@itemAdd');
Route::post('cart-delete', 'CartController@delete');
Route::post('cart-update', 'CartController@update');

// footer nav newsletter signup AJAX
Route::post('newsletter-signup', 'AjaxController@newsletterSignup');
Route::post('gdpr', 'AjaxController@gdpr');
Route::post('newsletter-interstitial', 'AjaxController@newsletterInterstitialSeen');
Route::post('newsletter-interstitial-submit', 'AjaxController@newsletterInterstitialSubmit');
Route::get('newsletter-interstitial-content', 'ModalController@homeIntAd');

// nav search AJAX
Route::post('nav-search', 'AjaxController@navList');

Route::get('search/{id}', 'AjaxController@searchList');
Route::post('search/{id}', 'AjaxController@searchList');


// cart - sample
Route::get('cart/{id}', 'CartController@cartPage')->name('cart-home');
Route::get('cart/{id}/checkout', 'CartController@checkout');
Route::get('cart/{id}/ship', 'CartController@ship'); // catch error
Route::post('cart/{id}/ship', 'CartController@ship');
Route::get('cart/{id}/review', 'CartController@review'); // catch error
Route::post('cart/{id}/review', 'CartController@review');
Route::post('cart/sample/confirmation', 'CartController@sampleConfirmation');
Route::get('cart/shopping/confirmation', 'CartController@shoppingConfirmation');
// cart - shopping
Route::get('shopping-cart', 'CartController@shoppingPage')->name('shopping-home');

// get pay link from /home
Route::post('pay/{id}', 'CartController@payLink');
Route::post('export-invoice/{status}/{order_id}', 'HomeController@invoice');

// email test
// Route::get('sendhtmlemail','TestMailController@html_email');
//Route::get('sendbasicemail','TestMailController@basic_email');
//Route::get('sendattachmentemail','TestMailController@attachment_email');

// showrooms
Route::get('showrooms', 'ShowroomController@getShowroom')->middleware('cache.headers:private,max_age=3600');
Route::get('find-a-rep', 'ShowroomController@findARep');

// custom-labs
Route::get('custom-labs', 'CustomlabsController@getCustomLabs');
Route::get('custom-labs-start-your-project', 'CustomlabsController@startYourProject');

// aux pages
Route::get('new-collection', 'Controller@getNewcollection')->middleware('cache.headers:no_cache,private,max_age=600;etag');
Route::get('trending', 'Controller@getTrending')->middleware('cache.headers:private,max_age=3600');
Route::get('whats-new', 'Controller@getWhatsNew')->middleware('cache.headers:private,max_age=3600');
Route::get('let-us-shop-for-you', 'Controller@getLetUsShopForYou')->middleware('cache.headers:private,max_age=3600');
Route::get('customer-service', 'Controller@getCustomerService');
Route::post('customer-service', 'Controller@messageCustomerService')->name('customer-service');

Route::get('privacy-policy', 'Controller@getPrivacyPolicy')->middleware('cache.headers:private,max_age=3600');
Route::get('terms-conditions', 'Controller@getTermsConditions');
Route::get('/presentation-form-test', 'FormController@getForm');

Route::get('our-story', 'Controller@getOurStory')->middleware('cache.headers:private,max_age=3600');
Route::get('mentions', 'Controller@getmentions')->middleware('cache.headers:private,max_age=3600');

Route::get('presentation-request', 'Controller@getPresentation');

Route::get('new-collection-presentation-request', 'Controller@getNewCollectionRequest')->middleware('cache.headers:private,max_age=3600');
Auth::routes();


// account registration
Route::get('account-registration', 'RegistrationController@registerUser')->middleware('cache.headers:private,max_age=3600');
Route::post('register-guest', '\App\Http\Controllers\Auth\RegisterController@registerGuest');

Route::get('presentation-request-submitted', 'Controller@getPresFormSubmit');
// login
Route::get('home', 'HomeController@index')->name('home');
Route::get('home/recent-orders', 'HomeController@recentOrders')->name('recentOrders');
Route::get('home/price-list', 'HomeController@priceList')->name('priceList');
Route::get('home/my-account', 'HomeController@myAccount')->name('myAccount');
Route::post('home/update-account', 'HomeController@updateAccount')->name('updateClient');

// logout
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get('videos', 'VideoController@getVideos')->middleware('cache.headers:private,max_age=3600');
Route::get('videos/{slug}', 'VideoController@getVideo')->middleware('cache.headers:private,max_age=3600');
Route::get('videos/{slug}/modal', 'VideoController@getVideoModal')->middleware('cache.headers:private,max_age=3600');

Route::get('collections', 'CollectionController@showCollections')->middleware('cache.headers:private,max_age=3600');
Route::get('collections/{slug}', 'CollectionController@showCollection')->middleware('cache.headers:private,max_age=3600');
Route::get('/feeds/{type}', 'RssFeedController@generateRssFeed')->name('feeds');

Route::get('/press-release', 'PressReleaseController@showReleases')->middleware('cache.headers:private,max_age=3600');
Route::get('press-release/{slug}', 'PressReleaseController@showRelease');

Route::get('/blog', 'BlogController@showBlogs');
Route::get('blog/{slug}', 'BlogController@showCategory');
Route::get('blog/{slug}/{blog}', 'BlogController@showBlog');
Route::get('/product-csv-form', 'ProductController@showCSVForm');
Route::post('/update-csv-product', 'ProductController@updateCSVProduct');

Route::get('catalogs', 'CatalogController@getCatalogs')->middleware('cache.headers:private,max_age=3600');
Route::get('catalogs/{slug}', 'CatalogController@getCatalog')->middleware('cache.headers:private,max_age=3600');
Route::get('catalogs/{slug}/modal', 'CatalogController@getCatalogModal');

Route::get('social-media/instagram', 'Controller@socialMediaFeed');
Route::get('social-media/pinterest', 'Controller@pinterestFeed');
Route::get('/share-install-images', '\App\Http\Controllers\Forms\UserUploadFormController@userImageForm');
Route::post('/share-install-images', '\App\Http\Controllers\Forms\UserUploadFormController@saveUserImage');

Route::get('updateclients', 'Controller@updateClients');
Route::get('/sitemap.xml', 'SitemapXmlController@index');

Route::get('faq', 'Controller@faqPage')->middleware('cache.headers:private,max_age=3600');

Route::group(['prefix' => 'dashboard'], function () {
    Voyager::routes();
    Route::get('/update-from-wd', 'Voyager\VoyagerProductMasterController@indexWd');
    Route::get('/getproducts', 'Voyager\VoyagerProductMasterController@getproducts')->name('getproducts');
    Route::post('/update-from-wd', 'Voyager\VoyagerProductMasterController@updateWd');
    Route::get('/export-guests', 'Voyager\VoyagerUserController@indexGuest');
    Route::post('/export-guests', 'Voyager\VoyagerUserController@exportGuest');
    Route::get('/form-data', 'Voyager\VoyagerFormStorageController@indexFormStorage');
    Route::post('/export-form', 'Voyager\VoyagerFormStorageController@exportFormStorage');
    Route::get('/download-zip', 'Voyager\VoyagerFormStorageController@zip_download');
});

Auth::routes();
