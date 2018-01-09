<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class LogService
{
    public static function write($request, \Exception $exception)
    {
        dd($exception);
        if (empty($request)) {
            Log::error($exception);
        } else {
            Log::error($exception, [$request->getMethod() => $request->url()]);
        }
    }

}

