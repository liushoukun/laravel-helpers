<?php

namespace Liushoukun\LaravelHelpers\Logs;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LogSqlServiceProvider
{

    public static function register()
    {
        if (app('config')->get('app.debug')) {
            DB::listen(function ($query) {
                Log::channel('sql')->debug('sql:' . $query->sql . ';bindings:' . json_encode($query->bindings) . 'time:' . $query->time);
            });
        }
    }

}
