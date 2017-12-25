<?php namespace App\Helper;


use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class Uploader
{
    public static function delete($filename = null)
    {
        return  Storage::delete($filename);
    }

    public static function genFileName($prefix, $fileName)
    {
        return $prefix . '_' . bcrypt(Carbon::now()) . '_' . md5($fileName);
    }
}