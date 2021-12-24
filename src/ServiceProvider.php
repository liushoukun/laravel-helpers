<?php
namespace Liushoukun\LaravelHelpers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Liushoukun\LaravelHelpers\Views\Components\Modules;
use Liushoukun\LaravelHelpers\Views\Components\WindowConfigs;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //


    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (app('config')->get('app.debug')) {
            DB::listen(function ($query) {
                Log::channel('sql')->debug('sql:' . $query->sql . ';bindings:' . json_encode($query->bindings) . 'time:' . $query->time);
            });
        }

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laraavel-helpers');

        $this->registerViewsComponents();
    }


    /**
     * 主持模板
     */
    public function registerViewsComponents()
    {
        Blade::component((Modules::class),'modules');
        Blade::component((WindowConfigs::class),'window-configs');
    }

}
