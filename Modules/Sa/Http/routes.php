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
            Route::get('/home', 'HomeController@index')->name('admin.index');
            Route::get('/system/fee', 'HomeController@systemFee')->name('admin.system_fee');
            Route::match(array('GET','POST'), '/trans/list', 'HomeController@transList')->name('admin.trans_list');
            Route::post('/trans/approve', 'HomeController@transApprove')->name('admin.trans_approve');
            Route::match(array('GET','POST'), '/user/list', 'HomeController@userList')->name('admin.user_list');
            Route::post('/user/verify', 'HomeController@userVerify')->name('admin.user_verify');
            Route::post('/user/delete', 'HomeController@userDelete')->name('admin.user_delete');
        });
    });
}
