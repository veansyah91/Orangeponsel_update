<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Auth::routes();
Auth::routes(['register' => false]);

Route::group(['middleware' => ['auth']], function () {
    Route::get('/account/users', 'Admin\UserController@index')->name('user.index');
    Route::get('/account/roles', 'Admin\RoleController@index')->name('user.role');
    Route::get('/change-password','User\UserController@changePassword')->name('change-password');
    Route::patch('/change-password','User\UserController@updatePassword')->name('edit-password');

    Route::get('/home', 'HomeController@index')->name('home');

    Route::get('/outlet', 'Admin\OutletController@index')->name('outlet.index');

    Route::get('/inter-outlet', 'Admin\InterOutletController@index')->name('inter-outlet.index');
    Route::get('/inter-outlet/part1={pihak1}/part2={pihak2}', 'Admin\InterOutletController@detail')->name('inter-outlet.detail');

    Route::get('/master/brand', 'Admin\BrandController@index')->name('master.brand');
    Route::get('/master/kategori', 'Admin\CategoryController@index')->name('master.category');
    Route::get('/master/pemasok', 'Admin\SupplierController@index')->name('master.supplier');
    Route::get('/master/pelanggan', 'Admin\CustomerController@index')->name('master.customer');
    Route::get('/master/produk', 'Admin\ProductController@index')->name('master.product');

    Route::get('/sales/invoice', 'Admin\InvoiceController@index')->name('sales.invoice');
    Route::get('/sales/balance', 'Admin\InvoiceController@balance')->name('sales.balance');
    Route::get('/sales/hutang', 'Admin\DebtController@index')->name('sales.debt');
    Route::get('/sales/account-receivable', 'Admin\AccountReceivableController@index')->name('sales.account-receivable');
    Route::get('/sales/account-receivable-payment', 'Admin\AccountReceivablePaymentController@index')->name('sales.account-receivable-payment');

    Route::get('/purchase/purchase-goods','Admin\PurchaseGoodsController@index')->name('purchase.purchase-goods');
    Route::get('/purchase/top-up-balance','Admin\TopUpBalanceController@index')->name('purchase.top-up-balance');
    Route::get('/purchase/purchase-return','Admin\PurchaseReturnController@index')->name('purchase.purchase-return');
    Route::get('/purchase/account-payable','Admin\AccountPayableController@index')->name('purchase.account-payable');
    Route::get('/purchase/account-payable-payment','Admin\AccountPayablePaymentController@index')->name('purchase.account-payable-payment');

    Route::get('/sales/sales-return','Admin\SalesReturnController@index')->name('sales.sales-return');

    Route::get('/cash/expense','Admin\ExpenseController@index')->name('cash.expense');

    Route::get('/service/masuk','Admin\ServiceController@index')->name('service.index');
    Route::get('/service/invoice','Admin\ServiceController@invoice')->name('service.invoice');
    
    Route::get('/stock/item-entry', 'Admin\ItemEntryController@index')->name('stock.item-entry');
    Route::get('/stock/item', 'Admin\StockController@index')->name('stock.index');
    Route::get('/stock/balance', 'Admin\StockController@balance')->name('stock.balance');
    Route::get('/stock/asset', 'Admin\StockController@asset')->name('stock.asset');
    Route::get('/stock/pdf', 'Admin\StockController@pdf')->name('stock.pdf');
    Route::get('/stock/asset-pdf', 'Admin\StockController@assetPdf')->name('stock.asset-pdf');

    Route::get('/outlets-cashflow', 'Admin\StockController@index')->name('outlets-cashflow.index');

    Route::get('/credit-partners','Admin\CreditPartnerController@index')->name('credit-partners.index');
    Route::get('/credit-partner/partner={partner}/customer','Admin\CreditPartnerController@customer')->name('credit-partner.customer');
    Route::get('/credit-partner/partner={partner}/proposal','Admin\CreditPartnerController@proposal')->name('credit-partner.proposal');
    Route::get('/credit-partner/partner={partner}/history','Admin\CreditPartnerController@history')->name('credit-partner.history');
    Route::get('/credit-partner/partner={partner}/credit-payment','Admin\CreditPartnerController@creditPayment')->name('credit-partner.credit-payment');
    Route::get('/credit-partner/partner={partner}/invoice','Admin\CreditPartnerController@invoice')->name('credit-partner.invoice');
    Route::get('/credit-partner/partner={partner}/invoice-claim','Admin\CreditPartnerController@invoiceClaim')->name('credit-partner.invoice-claim');

    Route::get('/credit-partner/partner={partner}/invoice-claim/to-pdf','Admin\CreditPartnerController@invoiceClaimToPdf');

    Route::get('/credit-partner/partner={partner}/old-proposal','Admin\CreditApplicationOldController@index')->name('credit-partner.old-proposal');
    Route::get('/credit-partner/partner={partner}/old-payment','Admin\CreditPaymentOldController@index')->name('credit-partner.old-payment');

    Route::get('/credit-partner/partner={partner}/collect','Admin\CreditCollectController@index')->name('credit-partner.collect');
    Route::get('/credit-partner/partner={partner}/delay-payment','Admin\CreditPaymentDelayController@index')->name('credit-partner.delay-payment');

    Route::get('/ledger/account', 'Admin\AccountController@index')->name('ledger.account');
    Route::get('/ledger/ledger', 'Admin\LedgerController@index')->name('ledger.ledger');
    Route::get('/ledger/journal', 'Admin\JournalController@index')->name('ledger.journal');

});

