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
});

Auth::routes();

Route::get('/categories', 'CategoryController@index');
Route::get('/categories/{category}', 'CategoryController@category');

Route::group(['middleware' => ['role:super-admin']], function () {
    Route::get('/admin', 'admin\AdminController@index');
    Route::get('/admin/editCategories', 'admin\AdminController@getCategories');
    Route::get('/admin/editProducts', 'admin\AdminController@getProducts');
});


Route::post('ajaxProducts', 'ProductController@ajaxPostGerProducts')->name('ajaxProducts.post');
Route::post('ajaxDeleteProduct', 'ProductController@ajaxPostDeleteProduct')->name('ajaxDeleteProduct.post');
Route::post('ajaxGetProduct', 'ProductController@ajaxPostGetProduct')->name('ajaxGetProduct.post');