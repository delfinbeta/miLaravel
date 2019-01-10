<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\{Schema, Blade, View, DB};
use App\Http\ViewComposers\UserFieldsComposer;
use Illuminate\Database\Eloquent\Builder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Blade::component('shared._card', 'card');
        // View::composer(['users.new', 'users.edit'], UserFieldsComposer::class);
        // Paginator::defaultView('shared.pagination');
        // Builder::macro('whereQuery', function($subquery, $value) {
        //     $this->addBinding($subquery->getBindings());
        //     $this->where(DB::raw("({$subquery->toSql()})"), $value);
        // });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
