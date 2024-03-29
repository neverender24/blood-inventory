<?php



Auth::routes();

Route::get('/', 'UserController@welcome')->middleware('auth');

Route::get('blood-types', 'BloodTypeController@index');
Route::get('blood-components', 'BloodComponentController@index');
Route::get('blood-stations', 'BloodStationController@index');
Route::post('get-no-dispositions', 'DispositionController@getNoDispositions');
Route::get('get-dispositions', 'DispositionController@getDispositions');
Route::get('get-releases', 'ReleaseController@getReleaseDispositions');
Route::get('get-details', 'OrderController@getDetails');

Route::post('all-stocks', 'ReportController@getAllStocks');
Route::post('total-stocks', 'ReportController@getTotalStocks');
Route::get('pending-orders', 'ReportController@getPendingOrders');
Route::post('expired-blood', 'ReportController@getExpiredDispositions');
Route::post('near-expired-blood', 'ReportController@getNearExpiredDispositions');
Route::post('admin-stocks', 'ReportController@getAdminStocks');
Route::post('generate-code', 'OrderController@generateCode');
Route::post('get-released-by', 'ReleaseController@getReleasedBy');

Route::get('client-dispositions', 'DispositionController@getClientDispositions');
Route::post('get-available-dispositions-client', 'DispositionController@getAvailableClientDispositions');

Route::post("printer","OrderController@print");

Route::middleware('auth')->group(function () {
    Route::resource('dispositions', 'DispositionController');
    Route::resource('releases', 'ReleaseController');
    Route::resource('orders', 'OrderController');
    Route::post('store-client', 'DispositionController@storeClient');

    Route::post('register-user', 'Auth\RegisterController@register');
    Route::post('change-password', 'UserController@changePassword');
    
    Route::get('get-user', 'UserController@getUser');

    Route::post('serve', 'DispositionController@serveOrder');
    Route::patch('serve/{id}', 'DispositionController@updateOrder');
    Route::patch('receive/{id}', 'OrderController@receive');
 
});
Route::get('api/report1', 'ReportController@report1');
Route::get('api/report2', 'ReportController@report2');
Route::get('api/bm6', 'ReportController@bm6');
