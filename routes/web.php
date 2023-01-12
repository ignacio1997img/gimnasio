<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AttentionController;
use App\Http\Controllers\BusineController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\PeopleController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\VaultController;
use App\Http\Controllers\WherehouseController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\UserController;
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

    Route::resource('categories', CategoryController::class);
    Route::post('categories/update', [CategoryController::class, 'update'])->name('categories.update');

    Route::resource('articles', ArticleController::class);
    Route::post('articles/update', [ArticleController::class, 'update'])->name('articles.update');

    Route::resource('providers', ProviderController::class);

    Route::get('services', [ServiceController::class, 'index'])->name('voyager.services.index');
    Route::get('services/{service?}/plans', [ServiceController::class, 'indexPlan'])->name('service-plans.index');
    Route::post('services/plans/store', [ServiceController::class, 'storePlan'])->name('service-plans.store');
    Route::post('services/plans/update', [ServiceController::class, 'updatePlan'])->name('service-plans.update');

    Route::get('services/{service?}/hour', [ServiceController::class, 'indexHour'])->name('service-hour.index');
    Route::post('services/hour/store', [ServiceController::class, 'storeHour'])->name('service-hour.store');
    Route::post('services/hour/update', [ServiceController::class, 'updateHour'])->name('service-hour.update');

    Route::get('services/{service?}/hour/{hour?}/instructor', [ServiceController::class, 'indexInstructor'])->name('service-hour-instructor.index');
    Route::post('services/hour/instructor/store', [ServiceController::class, 'storeInstructor'])->name('service-hour-instructor.store');
    Route::get('services/{service}/hour/{hour}/instructor/{id?}/inactivo', [ServiceController::class, 'inactivoInstructor'])->name('service-hour-instructor.inactivo');
    Route::get('services/{service}/hour/{hour}/instructor/{id?}/activo', [ServiceController::class, 'activoInstructor'])->name('service-hour-instructor.activo');




    // para los turnos 
    // Route::resource('shifts', ShiftController::class);
    // Route::post('shifts/update', [ShiftController::class, 'update'])->name('shifts.update');
    // Route::get('shifts/hour/index/{id?}', [ShiftController::class, 'indexHour'])->name('shifts-hour.index');

    
 

    Route::resource('wherehouses', WherehouseController::class);
    Route::get('wherehouses/items-disponible', [WherehouseController::class, 'show'])->name('wherehouses-items.itemDisponible');

    Route::get('people', [PeopleController::class, 'index'])->name('voyager.people.index');
    Route::get('people/ajax/list/{search?}', [PeopleController::class, 'list']);
    Route::post('people/store', [PeopleController::class, 'store'])->name(('people.store'));

    Route::get('people/list/ajax', [PeopleController::class, 'lists']);//para obtener los lista de persona en registro de servicio

    Route::resource('instructors', InstructorController::class);
    Route::get('instructors/ajax/list/{type}/{search?}', [InstructorController::class, 'list']);






    Route::get('busines',[BusineController::class, 'index'])->name('voyager.busines.index');
    Route::get('busines/user/{id?}',[BusineController::class, 'indexUser'])->name('busines-user.index');
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
    // Route::get('cashiers/{cashier}/amount', [CashierController::class, 'amount'])->name('cashiers.amount');
    // Route::post('cashiers/amount/store', [CashierController::class, 'amount_store'])->name('cashiers.amount.store');
    Route::post('cashiers/{cashier}/close/revert', [CashierController::class, 'close_revert'])->name('cashiers.close.revert');
    Route::get('cashiers/{cashier}/close/', [CashierController::class, 'close'])->name('cashiers.close');//para cerrar la caja el cajero vista 
    Route::post('cashiers/{cashier}/close/store', [CashierController::class, 'close_store'])->name('cashiers.close.store'); //para que el cajerop cierre la caja 
    Route::get('cashiers/{cashier}/confirm_close', [CashierController::class, 'confirm_close'])->name('cashiers.confirm_close');
    Route::post('cashiers/{cashier}/confirm_close/store', [CashierController::class, 'confirm_close_store'])->name('cashiers.confirm_close.store');


    Route::get('cashiers/print/open/{id?}', [CashierController::class, 'print_open'])->name('print.open');//para imprimir el comprobante cuando se abre una caja


    // Route::get('cashiers/print/transfer/{transfer}', [CashierController::class, 'print_transfer'])->name('print.transfer');

    // Route::get('planillas/pagos/print/{id}', [CashierController::class, 'planillas_pagos_print']);//proceso
    // Route::get('planillas/pagos/delete/print/{id}', [CashierController::class, 'planillas_pagos_delete_print']);//procesoo


    Route::get('loans/ajax/list/{cashier_id}/{type}/{search?}', [LoanController::class, 'list']);


    Route::resource('clients', ClientController::class);
    Route::get('clients/ajax/list/{type}/{search?}', [ClientController::class, 'list']);
    Route::post('clients/adition/store', [ClientController::class, 'aditionStore'])->name('clients-adition.store');
    // Route::post('clients/update', [ClientController::class, 'update'])->name('clients.update');
    Route::post('clients/article', [ClientController::class, 'articleStore'])->name('clients-article.store');
    Route::get('clients/ajax/plans/list/{id?}', [ClientController::class, 'ajaxPlan'])->name('clients-ajax-list.plan');
    Route::get('clients/ajax/hour/list/{id?}', [ClientController::class, 'ajaxHour'])->name('clients-ajax-list.hour');
    Route::get('clients/ajax/instructor/list/{id?}', [ClientController::class, 'ajaxInstructor'])->name('clients-ajax-list.instructor');
    Route::get('clients/ajax/plans/inf/{id?}', [ClientController::class, 'ajaxInfPlan'])->name('clients-ajax-plan.inf');

    Route::get('clients/factura/{id?}', [ClientController::class, 'print']);


    // Route::get('planillas/pagos/people/search', [AttentionController::class, 'planillas_pagos_people_search']);








    Route::resource('client', ClientController::class);//en observacion
    





    //AJAX
    Route::get('wherehouses/ajax/article/{id?}', [WherehouseController::class, 'ajaxArticle'])->name('wherehouses-ajax.article');



    Route::get('clients/ajax/article/{id?}', [ClientController::class, 'ajaxArticle'])->name('clients-ajax.article');
    Route::get('clients/ajax/item/{id?}', [ClientController::class, 'ajaxItem'])->name('clients-ajax.item.modal');
    Route::get('clients/ajax/adition/{id?}', [ClientController::class, 'ajaxAdition'])->name('clients-ajax.adition.modal');



    Route::get('client/Service/baja', [ClientController::class, 'clientBaja'])->name('clients-ajax.baja');


    Route::get('users/ajax/user/{id?}', [UserController::class, 'ajaxUser'])->name('user-ajax.user');

});

// Clear cache
Route::get('/admin/clear-cache', function() {
    Artisan::call('optimize:clear');
    return redirect('/admin/profile')->with(['message' => 'Cache eliminada.', 'alert-type' => 'success']);
})->name('clear.cache');
