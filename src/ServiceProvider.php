<?php
namespace Liushoukun\LaravelHelpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
    }

}
