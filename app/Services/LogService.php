<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class LogService
{
    public static function write($request, \Exception $exception)
    {
        if (empty($request)) {
            dd($exception);
            Log::error($exception);
        } else {
            Log::error($exception, [$request->getMethod() => $request->url()]);
        }
    }

}

