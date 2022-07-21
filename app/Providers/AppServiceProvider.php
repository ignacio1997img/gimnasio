<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use TCG\Voyager\Facades\Voyager;
// use App\FormFields\UserIdFormField;
// use App\Http\Controllers\BusineController;
use App\FormFields\BusineIdFormField;
use App\FormFields\UserIdFormField;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Voyager::addFormField(BusineIdFormField::class);
        Voyager::addFormField(UserIdFormField::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
    }
}
