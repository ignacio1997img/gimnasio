<?php

use App\Http\Controllers\AttentionController;
use App\Http\Controllers\BusineController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\PeopleController;
use App\Http\Controllers\VaultController;
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

Route::get('login', function () {
    return redirect('admin/login');
})->name('login');

Route::get('/', function () {
    return redirect('admin');
});


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();


    Route::get('people', [PeopleController::class, 'index'])->name('voyager.people.index');
    Route::get('people/ajax/list/{search?}', [PeopleController::class, 'list']);

    Route::get('busines',[BusineController::class, 'index'])->name('voyager.busines.index');
    Route::get('busines/user/{id}',[BusineController::class, 'indexUser'])->name('busines-user.index');
    Route::post('busines/user/store',[BusineController::class, 'storeUser'])->name('busines-user.store');
    Route::post('busines/user/update',[BusineController::class, 'updateUser'])->name('busines-user.update');


    Route::resource('vaults', VaultController::class);
    Route::post('vaults/{id}/details/store', [VaultController::class, 'details_store'])->name('vaults.details.store');
    Route::post('vaults/{id}/open', [VaultController::class, 'open'])->name('vaults.open');
    Route::get('vaults/{id}/close', [VaultController::class, 'close'])->name('vaults.close');
    Route::post('vaults/{id}/close/store', [VaultController::class, 'close_store'])->name('vaults.close.store');
    Route::get('vaults/{vault}/print/status', [VaultController::class, 'print_status'])->name('vaults.print.status');


    Route::resource('cashiers', CashierController::class);
    Route::post('cashiers/{cashier}/change/status', [CashierController::class, 'change_status'])->name('cashiers.change.status');//para que acepta los cajeros el  monto dado de 
    Route::get('cashiers/{cashier}/amount', [CashierController::class, 'amount'])->name('cashiers.amount');
    Route::post('cashiers/amount/store', [CashierController::class, 'amount_store'])->name('cashiers.amount.store');
    Route::post('cashiers/{cashier}/close/revert', [CashierController::class, 'close_revert'])->name('cashiers.close.revert');
    Route::get('cashiers/{cashier}/close/', [CashierController::class, 'close'])->name('cashiers.close');

    Route::get('cashiers/print/transfer/{transfer}', [CashierController::class, 'print_transfer'])->name('print.transfer');

    Route::get('planillas/pagos/print/{id}', [CashierController::class, 'planillas_pagos_print']);//proceso
    Route::get('planillas/pagos/delete/print/{id}', [CashierController::class, 'planillas_pagos_delete_print']);//procesoo




    Route::resource('clients', ClientController::class);
    Route::post('clients/update', [ClientController::class, 'update'])->name('clients.update');
    // Route::delete('clients/delete', [ClientController::class, 'destroy'])->name('checks.delet');
    // Route::get('planillas/pagos/people/search', [AttentionController::class, 'planillas_pagos_people_search']);








    Route::resource('client', ClientController::class);

    Route::resource('instructor', InstructorController::class);

});

// Clear cache
Route::get('/admin/clear-cache', function() {
    Artisan::call('optimize:clear');
    return redirect('/admin/profile')->with(['message' => 'Cache eliminada.', 'alert-type' => 'success']);
})->name('clear.cache');
