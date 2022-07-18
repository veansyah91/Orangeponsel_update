<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/invoice/get-invoice-number', 'Admin\InvoiceController@getInvoiceNumber')->name('check-invoice');
Route::post('/invoice/create', 'Admin\InvoiceController@create')->name('create-invoice');

Route::get('/pelanggan/get-pelanggan', 'Admin\CustomerController@getCustomer')->name('get-pelanggan');
Route::get('/produk/get-produk', 'Admin\ProductController@getProduct')->name('get-pelanggan');

