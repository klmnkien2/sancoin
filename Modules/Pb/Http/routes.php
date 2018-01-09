<?php
if (check_domain(env('DOMAIN_PB'))) {
    Route::group(['middleware' => 'web', 'namespace' => 'Modules\Pb\Http\Controllers'], function ()
    {
        Route::get('/', 'PbController@index')->name('pb.index');
        Route::get('/home', 'PbController@index')->name('pb.home');
        Route::get('/user/{username}', 'PbController@getUser')->name('pb.get_user');
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
            Route::group(['prefix' => 'wallet'], function () {
                Route::get('/eth', 'WalletController@eth')->name('pb.wallet.eth');
                Route::get('/btc', 'WalletController@btc')->name('pb.wallet.btc');
                Route::get('/vnd', 'WalletController@vnd')->name('pb.wallet.vnd');
				Route::post('/vnd', 'WalletController@updateVND')->name('pb.wallet.updateVND');
                Route::post('/withdraw', 'WalletController@withdraw')->name('pb.wallet.withdraw');
            });
            Route::group(['prefix' => 'order'], function () {
                Route::get('/', 'OrderController@index')->name('pb.order.index');
                Route::match(array('GET','POST'), '/detail/{id}', 'OrderController@detail')->name('pb.order.detail');
                Route::post('/accept/{id}', 'OrderController@accept')->name('pb.order.accept');
                Route::post('/create', 'OrderController@create')->name('pb.order.create');
                Route::post('/transfer', 'OrderController@transfer')->name('pb.order.transfer');
                Route::post('/cancel/{id}', 'OrderController@cancel')->name('pb.order.cancel');
            });
            Route::get('/all/{type}', 'OrderController@all')->name('pb.order.all');
        });
    });
}
