<?php
if (check_domain(env('DOMAIN_PB'))) {
    Route::group(['middleware' => 'web', 'namespace' => 'Modules\Pb\Http\Controllers'], function ()
    {
        Route::get('/', 'PbController@index')->name('pb.index');
        Route::get('/home', 'PbController@index')->name('pb.home');
        Route::group(['middleware' => 'guest:web'], function ()
        {
            // Login
            Route::get('/login', 'PbController@getLogin')->name('pb.login');
            Route::post('/login', 'PbController@postLogin')->name('pb.postLogin');
        });
        Route::group(['middleware' => 'auth:web'], function ()
        {
            Route::get('logout', 'PbController@getLogout')->name('pb.logout');
        });
    });
}
