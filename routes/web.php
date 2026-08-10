<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\StockCategoryController;
use App\Http\Controllers\StockUnitController;
use App\Http\Controllers\StockSubCategoryController;
use App\Http\Controllers\AssetsController;
use App\Http\Controllers\BusinessExpenseController;
use App\Http\Controllers\CashController;
use App\Http\Controllers\ShipmentController;

// use App\Http\Controllers\TailorController;

Route::get('/', function () {
    return redirect('/login');
});
Auth::routes();


Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/edit-profile', [HomeController::class, 'changePassword'])->name('password.change');
    Route::post('/edit-profile', [HomeController::class, 'changePasswordPost'])->name('password.change.post');
    Route::get('logout', [LoginController::class, 'logout']);
    Route::resource('stocks', StockController::class);
    Route::get('/expense', [StockController::class, 'expense'])->name('expense');
    Route::get('/expense/create', [StockController::class, 'expenseCreate'])->name('expense.create');
    Route::resource('customers', CustomerController::class);
    Route::post('/customers/save', [CustomerController::class, 'save'])->name('customer.add');
    Route::get('customer/ledger/{id}', [CustomerController::class, 'GetLedger'])->name('customer.ledger');
    Route::post('customer/ledger/store/{id}', [CustomerController::class, 'LedgerAdd'])->name('customer.ledger.store');
    Route::resource('sales', SaleController::class);
    Route::get('sales/get/{id}', [SaleController::class, 'Get'])->name('sale.get');
    Route::get('sales/print/{id}', [SaleController::class, 'print'])->name('sales.print');
    Route::post('sales/status/change', [SaleController::class, 'SaleStatusChange'])->name('sale.status.change');
    Route::get('/sales/upload-images/{id}', [SaleController::class, 'uploadImages'])->name('sales.uploadImages');
    Route::post('/sales/upload-images/store', [SaleController::class, 'storeUploadedImages'])->name('sales.storeUploadedImages');



    Route::resource('stock_category', StockCategoryController::class);
    Route::resource('stock_sub_category', StockSubCategoryController::class);
    Route::resource('stock_unit', StockUnitController::class);
    Route::get('/get-subcategories/{categoryId}', [StockController::class, 'getSubCategories']);
    Route::get('/get-products/{categoryId}/{subCategoryId}', [StockController::class, 'getProducts']);
    Route::get('report/stock', [StockController::class, 'Report'])->name('report.stock');
    Route::get('report/stock/print', [StockController::class, 'ReportPrint'])->name('report.stock.print');
    //assets
    Route::get('assets', [AssetsController::class, 'index'])->name('assets.index');
    Route::get('assets/create', [AssetsController::class, 'create'])->name('assets.create');
    Route::post('assets/store', [AssetsController::class, 'store'])->name('assets.store');
    Route::delete('assets/{id}', [AssetsController::class, 'destroy'])->name('assets.destroy');

    //assets used
    Route::get('assets/used/create', [AssetsController::class, 'createUsedAssets'])->name('assets.used.create');
    Route::post('assets/used/store', [AssetsController::class, 'storeUsedAssets'])->name('assets.used.store');
    Route::get('report/assets', [AssetsController::class, 'reportUsedAssets'])->name('report.assets');
    Route::get('report/assets/print', [AssetsController::class, 'ReportPrint'])->name('report.assets.print');

    // Business Expenses
    Route::resource('business-expenses', BusinessExpenseController::class)->except(['show']);
    Route::get('report/business-expense', [BusinessExpenseController::class, 'report'])->name('report.business.expense');
    Route::get('report/business-expense/print', [BusinessExpenseController::class, 'reportPrint'])->name('report.business.expense.print');
    Route::get('report/shipment', [ShipmentController::class, 'report'])->name('report.shipment');
    Route::get('report/shipment/print', [ShipmentController::class, 'reportPrint'])->name('report.shipment.print');

    // Cash
    Route::get('cash', [CashController::class, 'index'])->name('cash.index');
    Route::get('cash/create', [CashController::class, 'create'])->name('cash.create');
    Route::post('cash', [CashController::class, 'store'])->name('cash.store');
    Route::get('cash/{id}/edit', [CashController::class, 'edit'])->name('cash.edit');
    Route::put('cash/{id}', [CashController::class, 'update'])->name('cash.update');
    Route::delete('cash/{id}', [CashController::class, 'destroy'])->name('cash.destroy');

    // Shipments
    Route::get('shipments/customer-invoices/{customer}', [ShipmentController::class, 'customerInvoices'])->name('shipments.customerInvoices');
    Route::resource('shipments', ShipmentController::class)->except(['show']);





    // Route::resource('tailors', TailorController::class);
    // Route::post('/tailors/save', [TailorController::class, 'save'])->name('tailor.add');
    // Route::get('tailor/ledger/{id}', [TailorController::class, 'GetLedger'])->name('tailor.ledger');
    // Route::post('tailor/ledger/store/{id}', [TailorController::class, 'LedgerAdd'])->name('tailor.ledger.store');
    Route::resource('user', UserController::class);
});
