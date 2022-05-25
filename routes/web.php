<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'DashboardController@index');
Route::post('/upload', 'UploadController@upload')->name('tinymce.upload');
Route::get('/upload/{img}', 'UploadController@getUploadImg');
Auth::routes();
Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

    Route::prefix('program')->group(function () {
        Route::get('/', 'ProgramController@index')->name('program');
        Route::get('/create', 'ProgramController@create')->name('program.create');
        Route::post('/create', 'ProgramController@store')->name('program.store');
        Route::get('/{id}/edit', 'ProgramController@edit')->name('program.edit');
        Route::post('/{id}/edit', 'ProgramController@update')->name('program.update');
    });
    Route::resource('halaman', 'PageController');
    Route::prefix('donasi')->group(function () {
        Route::get('/', 'DonasiController@index')->name('donasi');
        Route::get('/{id}/detail', 'DonasiController@detail')->name('donasi.detail');
        Route::post('/{id}/detail', 'DonasiController@update')->name('donasi.update');
        Route::get('/{id}/edit', 'DonasiController@editDone')->name('donasi.done.edit');
        Route::post('/{id}/edit', 'DonasiController@updateDone')->name('donasi.done.update');
        Route::post('/delete', 'DonasiController@delete')->name('donasi.delete');
        Route::post('/amal', 'DonasiController@submitAmal')->name('donasi.amal');
        Route::get('/create', 'DonasiController@create')->name('donasi.create');
        Route::post('/insert', 'DonasiController@insert')->name('donasi.insert');
        Route::get('/getCollected', 'DonasiController@getCollected')->name('donasi.getCollected');
        Route::post('/filter', 'DonasiController@filter')->name('donasi.filter');

        Route::get('/doa', 'DonasiController@indexDoa')->name('doa.donasi');
        Route::post('/doa/filter', 'DonasiController@filterDoa')->name('doa.donasi.filter');
        Route::get('/doa/{id}/detail', 'DonasiController@detailDoa')->name('doa.donasi.detail');
        Route::post('/doa/{id}/reply', 'DonasiController@saveReply')->name('doa.donasi.update');
    });
    Route::get('/fundraiser', 'DonasiController@fundraiser')->name('fundraiser');
    Route::prefix('qurban')->group(function () {
        Route::get('/', 'QurbanController@index')->name('qurban');
        Route::get('/{id}/detail', 'QurbanController@detail')->name('qurban.detail');
        Route::post('/{id}/detail', 'QurbanController@update')->name('qurban.update');
        Route::prefix('page')->group(function () {
            Route::get('/', 'QurbanController@indexPage')->name('qurban.page.index');
            Route::get('/create', 'QurbanController@createPage')->name('qurban.page.create');
            Route::post('/create', 'QurbanController@storePage')->name('qurban.page.store');
            Route::get('/{id}/edit', 'QurbanController@editPage')->name('qurban.page.edit');
            Route::post('/{id}/edit', 'QurbanController@updatePage')->name('qurban.page.update');
            Route::post('/{id}/delete', 'QurbanController@destroyPage')->name('qurban.page.delete');
        });
        Route::prefix('package')->group(function () {
            Route::get('/', 'QurbanController@indexPackage')->name('qurban.package.index');
            Route::get('/create', 'QurbanController@createPackage')->name('qurban.package.create');
            Route::post('/create', 'QurbanController@storePackage')->name('qurban.package.store');
            Route::get('/{id}/edit', 'QurbanController@editPackage')->name('qurban.package.edit');
            Route::post('/{id}/edit', 'QurbanController@updatePackage')->name('qurban.package.update');
            Route::post('/{id}/delete', 'QurbanController@destroyPackage')->name('qurban.package.delete');
        });
        Route::prefix('transaction')->group(function () {
            Route::get('/', 'QurbanController@index')->name('qurban.transaction.index');
            Route::get('/create', 'QurbanController@create')->name('qurban.transaction.create');
            Route::post('/create', 'QurbanController@store')->name('qurban.transaction.store');
            Route::get('/{id}/detail', 'QurbanController@show')->name('qurban.transaction.detail');
            Route::get('/{id}/edit', 'QurbanController@edit')->name('qurban.transaction.edit');
            Route::post('/{id}/edit', 'QurbanController@update')->name('qurban.transaction.update');
            Route::post('/{id}/delete', 'QurbanController@destroy')->name('qurban.transaction.delete');
            Route::post('/filter', 'QurbanController@filter')->name('qurban.filter');
        });
    });
    Route::prefix('amal')->group(function () {
        Route::get('/', 'AmalController@index')->name('amal');
        Route::get('/create', 'AmalController@create')->name('amal.create');
        Route::post('/create', 'AmalController@store')->name('amal.store');
        Route::get('/{id}/detail', 'AmalController@detail')->name('amal.detail');
        Route::post('/{id}/detail', 'AmalController@update')->name('amal.update');
        Route::post('/delete', 'AmalController@delete')->name('amal.delete');
    });
    Route::prefix('news')->group(function () {
        Route::get('/', 'NewsController@index')->name('news');
        Route::get('/{id}/edit', 'NewsController@edit')->name('news.edit');
        Route::post('/{id}/edit', 'NewsController@update')->name('news.update');
        Route::get('/create', 'NewsController@create')->name('news.create');
        Route::post('/create', 'NewsController@insert')->name('news.insert');
        Route::get('/upload/create', 'FileController@create')->name('news.upload.create');
        Route::post('/upload/insert', 'FileController@insert')->name('news.upload.insert');
    });
    Route::prefix('withdraw')->group(function () {
        Route::get('/', 'WithdrawController@index')->name('withdraw');
        Route::get('/create', 'WithdrawController@create')->name('withdraw.create');
        Route::get('/valDate', 'WithdrawController@valDate')->name('withdraw.valDate');
        Route::post('/create', 'WithdrawController@insert')->name('withdraw.insert');
        Route::get('/{id}/detail', 'WithdrawController@detail')->name('withdraw.detail');
        Route::post('/{id}/detail', 'WithdrawController@updateDetail')->name('withdraw.updateDetail');
        Route::post('/{id}/detail/cancel', 'WithdrawController@cancelRequest')->name('withdraw.cancelRequest');
        // Route::get('/mitra', 'WithdrawController@mitra')->name('withdraw.mitra');
    });
    Route::prefix('user')->group(function () {
        Route::get('/', 'UserController@index')->name('user');
        Route::post('/{id}/profile', 'UserController@submitProfile')->name('user.update.profile');
        Route::post('/{id}/password', 'UserController@changePassword')->name('user.update.password');
    });
    Route::prefix('report')->group(function () {
        Route::get('/', 'ReportController@index')->name('report');
        Route::post('/preview', 'ReportController@preview')->name('report.preview');
        Route::post('/export', 'ReportController@export')->name('report.export');
    });
    Route::prefix('channel')->group(function () {
        Route::get('/', 'ChannelController@index')->name('channel');
        Route::get('/create', 'ChannelController@create')->name('channel.create');
        Route::Post('/create', 'ChannelController@insert')->name('channel.insert');
        Route::get('/{id}/edit', 'ChannelController@edit')->name('channel.edit');
        Route::post('/{id}/edit', 'ChannelController@update')->name('channel.update');
    });
    Route::prefix('kajian')->group(function () {
        Route::get('/', 'KajianController@index')->name('kajian');
        Route::get('/create', 'KajianController@create')->name('kajian.create');
        Route::Post('/create', 'KajianController@store')->name('kajian.store');
        Route::get('/{id}/edit', 'KajianController@edit')->name('kajian.edit');
        Route::post('/{id}/edit', 'KajianController@update')->name('kajian.update');
    });
    Route::prefix('promosi')->group(function () {
        Route::get('/', 'PromosiController@index')->name('promosi');
        Route::get('/create', 'PromosiController@create')->name('promosi.create');
        Route::Post('/create', 'PromosiController@store')->name('promosi.store');
        Route::get('/{id}/edit', 'PromosiController@edit')->name('promosi.edit');
        Route::post('/{id}/edit', 'PromosiController@update')->name('promosi.update');
    });
    Route::prefix('page')->group(function () {
        Route::get('/', 'PageController@index')->name('page');
        Route::get('/create', 'PageController@create')->name('page.create');
        Route::post('/create', 'PageController@store')->name('page.store');
        Route::get('/{id}/edit', 'PageController@edit')->name('page.edit');
        Route::post('/{id}/edit', 'PageController@update')->name('page.update');
    });
    Route::prefix('mitra')->group(function () {
        Route::get('/', 'MitraController@index')->name('mitra');
        Route::get('/create', 'MitraController@create')->name('mitra.create');
        Route::post('/create', 'MitraController@insert')->name('mitra.insert');
        Route::get('/valEmail', 'MitraController@valEmail')->name('mitra.valEmail');
        Route::get('/valRefcode', 'MitraController@valRefcode')->name('mitra.valRefcode');
        Route::get('/{id}/edit', 'MitraController@edit')->name('mitra.edit');
        Route::post('/{id}/edit', 'MitraController@update')->name('mitra.update');
        Route::post('/delete', 'MitraController@delete')->name('mitra.delete');
    });
    Route::prefix('admin')->group(function () {
        Route::get('/', 'UserController@indexAdmin')->name('admin');
        Route::get('/create', 'UserController@createAdmin')->name('admin.create');
        Route::post('/create', 'UserController@insertAdmin')->name('admin.insert');
        Route::get('/{id}/edit', 'UserController@editAdmin')->name('admin.edit');
        Route::post('/{id}/edit', 'UserController@updateAdmin')->name('admin.update');
        Route::post('/delete', 'UserController@deleteAdmin')->name('admin.delete');
    });
    Route::get('/testmail', 'DonasiController@sendMail');
    Route::get('/payment/status', 'PaymentController@getStatus');

    Route::any('/ckfinder/connector', '\CKSource\CKFinderBridge\Controller\CKFinderController@requestAction')
        ->name('ckfinder_connector');

    Route::any('/ckfinder/browser', '\CKSource\CKFinderBridge\Controller\CKFinderController@browserAction')
        ->name('ckfinder_browser');

});