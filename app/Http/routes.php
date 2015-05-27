<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'HomeController@index');
Route::get('methods', 'HomeController@methods');
Route::get('warning-clear', 'HomeController@clear');
Route::get('warning-dummy', 'HomeController@dummy');

Route::group(['prefix' => 'koubachi'], function () {
    Route::get('plant/types','KoubachiController@plant_types');
    Route::get('plant/type/photo','KoubachiController@plant_type_photo');
    Route::get('plant/type/climate','KoubachiController@plant_type_climate');
});

Route::group(['prefix' => 'api'], function () {
    Route::resource(getenv('COUCHDB_OBJECT'), 'CouchDBController');

    Route::get('average/brightness', 'CouchDBController@avgBright');
    Route::get('average/temperature', 'CouchDBController@avgTemp');
    Route::get('average/moisture', 'CouchDBController@avgMoisture');

});
