<?php

use App\Http\Middleware\CheckForOpenCartProducts;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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
})->name('home');

Auth::routes();

Route::get('/products', 'Product\ProductController@index')->name('products');
Route::get('/products/{product}', 'Product\ProductController@getProduct')->name('product');

Route::get('/categories', ['as' => 'categories', 'uses' => 'Category\CategoryController@index']);
Route::get('/categories/{category}', ['as' => 'category', 'uses' => 'Category\CategoryController@getProductsCategory']);

Route::get('/orders', ['as' => 'orders', 'uses' => 'Order\OrderController@index']);
Route::get('/orders/products', ['as' => 'ordersProducts', 'uses' => 'Order\OrderController@getUnconfirmedProductsOrders']);
Route::get('/orders/confirmed', 'Order\OrderController@orderConfirmed')
    ->name('orderConfirmed');

Route::get('/cart/products', ['as' => 'cartProducts', 'uses' => 'Cart\CartController@getCartProducts'])
    ->middleware(CheckForOpenCartProducts::class);

Route::get('/cart/empty', ['as' => 'emptyCart', 'uses' => 'Cart\CartController@emptyCart']);

//Route::get('/emails', 'Product\ProductController@index')->name('products');

Route::group(['middleware' => ['role:super-admin']], function () {
    Route::get('/admin', 'Admin\AdminController@index');
    Route::get('/admin/editCategories', 'Admin\AdminController@getCategories');
    Route::get('/admin/editProducts', 'Admin\AdminController@getProducts');
});

Route::post('ajaxCreateProduct', 'Admin\AdminController@ajaxPostCreateProduct')->name('ajaxCreateProduct.post');
Route::post('ajaxUpdateProduct', 'Admin\AdminController@ajaxPostUpdateProduct')->name('ajaxUpdateProduct.post');
Route::post('ajaxGetEditedCategory', 'Admin\AdminController@ajaxGetEditedCategory')
    ->name('ajaxGetEditedCategory.post');

Route::post('ajaxProductsOrdersQuantity', 'Order\OrderController@ajaxProductsOrdersQuantity')
    ->name('ajaxProductsOrdersQuantity.post');

Route::post('ajaxAddCartProductsToOrder', 'Order\OrderController@ajaxAddCartProductsToOrder')
    ->name('ajaxAddCartProductsToOrder.post');

Route::post('ajaxAddProductToCart', 'Cart\CartController@ajaxAddProductToCart')
    ->name('ajaxAddProductToCart.post');

Route::post('ajaxGetProductsQuantityToCart', 'Cart\CartController@ajaxGetProductsQuantityToCart')
    ->name('ajaxGetProductsQuantityToCart.post');

Route::post('ajaxProductsCartQuantity', 'Cart\CartController@ajaxProductsCartQuantity')
    ->name('ajaxProductsCartQuantity.post');

Route::post('ajaxChangeProductCartQuantity', 'Cart\CartController@ajaxChangeProductCartQuantity')
    ->name('ajaxChangeProductCartQuantity.post');

Route::post('ajaxCheckIfCartEmpty', 'Cart\CartController@ajaxCheckIfCartEmpty')
    ->name('ajaxCheckIfCartEmpty.post');

Route::post('ajaxRemoveCartProduct', 'Cart\CartController@ajaxRemoveCartProduct')
    ->name('ajaxRemoveCartProduct.post');

Route::post('ajaxProducts', 'Product\ProductController@ajaxPostGetProducts')->name('ajaxProducts.post');
Route::post('ajaxDeleteProduct', 'Product\ProductController@ajaxPostDeleteProduct')->name('ajaxDeleteProduct.post');
Route::post('ajaxGetProduct', 'Product\ProductController@ajaxPostGetProduct')->name('ajaxGetProduct.post');

Route::post('ajaxCategories', 'Category\CategoryController@ajaxPostCategories')->name('ajaxCategories.post');
Route::post('ajaxProductsCategory', 'Category\CategoryController@ajaxPostProductsCategory')->name('ajaxProductsCategory.post');

Route::post('ajaxUpdateCategory', 'Category\CategoryController@ajaxPostUpdateCategory')->name('ajaxUpdateCategory.post');
