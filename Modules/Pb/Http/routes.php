<?php

Route::group(['middleware' => 'web', 'prefix' => 'pb', 'namespace' => 'Modules\Pb\Http\Controllers'], function()
{
    Route::get('/', 'PbController@index');
});
