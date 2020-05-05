<?php

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

Route::get('/products', 'ProductController@index')->name('products');
Route::get('/products/{product}', 'ProductController@getProduct')->name('product');

Route::get('/categories', ['as' => 'categories', 'uses' => 'CategoryController@index']);
Route::get('/categories/{category}', ['as' => 'category', 'uses' => 'CategoryController@getProductsCategory']);

Route::group(['middleware' => ['role:super-admin']], function () {
    Route::get('/admin', 'admin\AdminController@index');
    Route::get('/admin/editCategories', 'admin\AdminController@getCategories');
    Route::get('/admin/editProducts', 'admin\AdminController@getProducts');
});

Route::post('ajaxCreateProduct', 'admin\AdminController@ajaxPostCreateProduct')->name('ajaxCreateProduct.post');
Route::post('ajaxUpdateProduct', 'admin\AdminController@ajaxPostUpdateProduct')->name('ajaxUpdateProduct.post');


Route::post('ajaxProducts', 'ProductController@ajaxPostGetProducts')->name('ajaxProducts.post');
Route::post('ajaxDeleteProduct', 'ProductController@ajaxPostDeleteProduct')->name('ajaxDeleteProduct.post');
Route::post('ajaxGetProduct', 'ProductController@ajaxPostGetProduct')->name('ajaxGetProduct.post');

Route::post('ajaxCategories', 'CategoryController@ajaxPostCategories')->name('ajaxCategories.post');
Route::post('ajaxProductsCategory', 'CategoryController@ajaxPostProductsCategory')->name('ajaxProductsCategory.post');

Route::post('ajaxUpdateCategory', 'CategoryController@ajaxPostUpdateCategory')->name('ajaxUpdateCategory.post');
