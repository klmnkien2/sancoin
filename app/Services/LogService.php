<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class LogService
{
    public static function write($request, \Exception $exception)
    {
        try {
            Log::error($exception, [$request->getMethod() => $request->url()]);
        } catch (\Exception $e) {
            return response()->view('errors.500', [], 500);
        }
    }

}

