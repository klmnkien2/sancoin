<?php
if (check_domain(env('DOMAIN_SA'))) {
    Route::group(['middleware' => 'web', 'prefix' => 'admin', 'namespace' => 'Modules\Sa\Http\Controllers'], function () {
        Route::group(['middleware' => 'guest:web_sa'], function () {
            // Login
            Route::get('/login', 'HomeController@getLogin')->name('sa.login');
            Route::get('/', 'HomeController@index')->name('sa.index');
            Route::post('/login', 'HomeController@postLogin')->name('sa.postLogin');
        });

        Route::group(['middleware' => 'auth:web_sa'], function () {
            Route::get('/logout', 'HomeController@getLogout')->name('sa.logout');
        });
    });
}
