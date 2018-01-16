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
            Route::get('/', 'HomeController@report')->name('admin.system_fee');
            Route::get('/', 'HomeController@transList')->name('admin.user_trans');
            Route::get('/', 'HomeController@userList')->name('admin.user_list');
            Route::post('/', 'HomeController@userVerify')->name('admin.user_verify');
            Route::post('/', 'HomeController@userBlock')->name('admin.user_block');
        });
    });
}
