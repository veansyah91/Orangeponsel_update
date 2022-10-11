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

//home
Route::get('/home/lost-profit', 'HomeController@lostProfit');
Route::get('/home/asset', 'HomeController@asset');
Route::get('/home/liability', 'HomeController@liability');
Route::get('/home/equity', 'HomeController@equity');

Route::get('/get-products','Admin\ProductController@getProducts');

Route::get('/invoice/get-invoice-number', 'Admin\InvoiceController@getInvoiceNumber')->name('check-invoice');
Route::get('/invoice/get-top-up-invoice-number', 'Admin\InvoiceController@getTopUpInvoiceNumber')->name('check-top-up-invoice');
Route::get('/invoices','Admin\InvoiceController@getInvoice')->name('get-invoice-history');
Route::get('/get-new-invoice-number','Admin\InvoiceController@getNewInvoiceNumber')->name('get-new-invoice-number');
Route::post('/invoice/create', 'Admin\InvoiceController@create')->name('create-invoice');
Route::post('/invoice/create-top-up-invoice', 'Admin\InvoiceController@createTopUpInvoice')->name('create-top-up-invoice');
Route::put('/invoice/update-top-up-invoice', 'Admin\InvoiceController@updateTopUpInvoice')->name('update-top-up-invoice');
Route::get('/top-up-invoice', 'Admin\InvoiceController@getTopUpInvoice')->name('top-up-invoice');
Route::get('/top-up-invoice/detail/{invoice}', 'Admin\InvoiceController@getTopUpInvoiceDetail')->name('top-up-invoice');
Route::delete('/top-up-invoice/delete/{invoice}', 'Admin\InvoiceController@deleteTopUpInvoice')->name('top-up-invoice');

Route::get('/sales-return','Admin\SalesReturnController@getData');
Route::post('/sales-return','Admin\SalesReturnController@storeData');
Route::put('/sales-return','Admin\SalesReturnController@updateData');
Route::delete('/sales-return/{id}','Admin\SalesReturnController@deleteData');
Route::get('/sales-return/new-invoice-number','Admin\SalesReturnController@newInvoiceNumber');

Route::get('/sales-return-detail','Admin\SalesReturnDetailController@getData');

Route::get('/purchase-goods','Admin\PurchaseGoodsController@getData');
Route::post('/purchase-goods','Admin\PurchaseGoodsController@storeData');
Route::put('/purchase-goods/{id}','Admin\PurchaseGoodsController@updateData');
Route::delete('/purchase-goods/{id}','Admin\PurchaseGoodsController@deleteData');
Route::get('/purchase-goods-detail/{id}','Admin\PurchaseGoodsController@getDataDetail');
Route::get('/purchase-goods/new-invoice-number','Admin\PurchaseGoodsController@newInvoiceNumber');

Route::get('/purchase-return','Admin\PurchaseReturnController@getData');
Route::get('/purchase-return/new-invoice-number','Admin\PurchaseReturnController@newInvoiceNumber');
Route::get('/purchase-return/{id}','Admin\PurchaseReturnController@getSingleData');
Route::post('/purchase-return','Admin\PurchaseReturnController@storeData');
Route::put('/purchase-return/{id}','Admin\PurchaseReturnController@updateData');
Route::delete('/purchase-return/{id}','Admin\PurchaseReturnController@deleteData');

Route::get('/top-up-balance','Admin\TopUpBalanceController@getData');
Route::get('/top-up-balance/new-invoice','Admin\TopUpBalanceController@newInvoice');
Route::get('/top-up-balance/{id}','Admin\TopUpBalanceController@getSingleData');
Route::post('/top-up-balance','Admin\TopUpBalanceController@postData');
Route::put('/top-up-balance/{id}','Admin\TopUpBalanceController@updateData');
Route::delete('/top-up-balance/{id}','Admin\TopUpBalanceController@deleteData');

Route::get('/account-receivable','Admin\AccountReceivableController@getData');
Route::get('/account-receivable/{detail}/detail','Admin\AccountReceivableDetailController@detail');

Route::get('/account-payable','Admin\AccountPayableController@getData');
Route::get('/account-payable/{detail}/detail','Admin\AccountPayableDetailController@detail');

Route::get('/account-receivable-payments','Admin\AccountReceivablePaymentController@getData');
Route::post('/account-receivable-payments','Admin\AccountReceivablePaymentController@postData');
Route::get('/account-receivable-payments/new-invoice-number','Admin\AccountReceivablePaymentController@newInvoiceNumber');

Route::get('/account-payable-payments','Admin\AccountPayablePaymentController@getData');
Route::post('/account-payable-payments','Admin\AccountPayablePaymentController@postData');
Route::get('/account-payable-payments/new-invoice-number','Admin\AccountPayablePaymentController@newInvoiceNumber');

Route::get('/pelanggan/get-pelanggan', 'Admin\CustomerController@getCustomer')->name('get-pelanggan');
Route::get('/supplier/get-supplier', 'Admin\SupplierController@getSupplier')->name('get-supplier');
Route::get('/produk/get-produk', 'Admin\ProductController@getProduct')->name('get-pelanggan');

Route::get('/account/getData', 'Admin\AccountController@getData')->name('get-account-data');
Route::get('/account/get-expense', 'Admin\AccountController@getExpense')->name('get-account-expense');
Route::get('/account/get-next-account-number', 'Admin\AccountController@getNextAccountNumber')->name('get-next-account-number');
Route::post('/account/add-account', 'Admin\AccountController@addAccount')->name('add-account');
Route::put('/account/edit-account/{account}', 'Admin\AccountController@editAccount')->name('edit-account');

Route::get('/account/get-account-category', 'Admin\AccountController@getAccountCategory')->name('get-account-category');
Route::post('/account/get-account-category', 'Admin\AccountController@createAccountCategory')->name('create-account-category');
Route::put('/account/update-account-category/{accountCategory}', 'Admin\AccountController@updateAccountCategory')->name('update-account-category');
Route::delete('/account/delete-account-category/{accountCategory}', 'Admin\AccountController@deleteAccountCategory')->name('delete-account-category');

Route::get('/ledgers', 'Admin\LedgerController@getLedger')->name('get-ledger');
Route::get('/count-ledger', 'Admin\LedgerController@countLedger')->name('count-ledger');
Route::get('/ledgers/balance', 'Admin\LedgerController@balance')->name('balance-ledger');
Route::get('/ledgers/top-up-balance', 'Admin\LedgerController@topUpbalance')->name('top-up-balance-ledger');


Route::get('/journal/check-description','Admin\JournalController@checkDeskription')->name('cek-deskription');
Route::post('/journal/create','Admin\JournalController@create')->name('create-journal');
Route::get('/journal/count','Admin\JournalController@countJournal')->name('create-count-journal');
Route::get('/journal/get-journals','Admin\JournalController@getJournals')->name('get-journals');
Route::get('/journal/get-journal/{journal}','Admin\JournalController@getJournal')->name('get-journal');
Route::delete('/journal/delete/{journal}','Admin\JournalController@delete')->name('delete-journals');
Route::get('/journal/edit/{journal}','Admin\JournalController@edit')->name('edit-journals');
Route::put('/journal/edit/{journal}','Admin\JournalController@update')->name('update-journals');

Route::get('/expense', 'Admin\ExpenseController@getData');
Route::post('/expense', 'Admin\ExpenseController@postData');
Route::get('/expense/new-invoice', 'Admin\ExpenseController@newInvoiceNumber');
Route::get('/expense/{id}', 'Admin\ExpenseController@getSingleData');
Route::put('/expense/{id}', 'Admin\ExpenseController@updateData');
Route::delete('/expense/{id}', 'Admin\ExpenseController@deleteData');
