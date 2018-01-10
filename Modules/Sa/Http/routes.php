<?php

Route::group(['middleware' => 'web', 'prefix' => 'sa', 'namespace' => 'Modules\Sa\Http\Controllers'], function()
{
    Route::get('/', 'SaController@index');
});
