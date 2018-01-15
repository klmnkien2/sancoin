<?php
if (check_domain(env('DOMAIN_SA'))) {
    Route::group(['middleware' => 'web', 'prefix' => 'admin', 'namespace' => 'Modules\Sa\Http\Controllers'], function () {
        Route::group(['middleware' => 'guest:admin'], function () {
            // Login
            Route::get('/login', 'AdminLoginController@getLogin')->name('admin.login');
            Route::post('/login', 'AdminLoginController@postLogin')->name('admin.postLogin');
        });

        Route::group(['middleware' => 'auth:admin'], function () {
            Route::get('/logout', 'HomeController@getLogout')->name('admin.logout');
            Route::get('/', 'HomeController@index')->name('admin.index');
        });
    });
}
