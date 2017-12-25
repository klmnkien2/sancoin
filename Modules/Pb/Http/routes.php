<?php
if (check_domain(env('DOMAIN_PB'))) {
    Route::group(['middleware' => 'web', 'namespace' => 'Modules\Pb\Http\Controllers'], function ()
    {
        Route::get('/', 'PbController@index')->name('pb.index');
        Route::get('/home', 'PbController@index')->name('pb.home');
        Route::group(['middleware' => 'guest:web'], function ()
        {
            // Login
            Route::post('/register', 'PbController@postRegister')->name('pb.register');
            Route::get('/login', 'PbController@getLogin')->name('login');
            Route::post('/login', 'PbController@postLogin')->name('pb.postLogin');
            Route::get('/r/a/u/{id}/{code}', 'PbController@activate')->name('pb.reg_activate');
            Route::get('/preactivate', 'PbController@preActivate')->name('pb.pre_activate');
        });
        Route::group(['middleware' => 'auth:web'], function ()
        {
            Route::get('/logout', 'PbController@getLogout')->name('pb.logout');
            Route::get('/profile', 'WalletController@profile')->name('pb.getProfile');
            Route::post('/updateProfile', 'WalletController@updateProfile')->name('pb.updateProfile');
        });
    });
}
