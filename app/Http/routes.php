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

Route::group(['prefix' => 'appdata'], function () {
    Route::get('plant/photos','KoubachiController@plant_types_photos');
    Route::get('plant/climates','KoubachiController@plant_types_climates');
    Route::get('plant/scientificNames' , 'KoubachiController@plant_types_scientific_names');
    Route::get('plant/familyNames', 'KoubachiController@plant_types_family_names');

    Route::get('plant/list' , 'KoubachiController@plant_list');
    Route::get('plant/{id}' , 'KoubachiController@plant_by_id');

    Route::resource('local', 'LocalDataController');
});

Route::group(['prefix' => 'api'], function () {
    Route::resource(getenv('COUCHDB_OBJECT'), 'CouchDBController');

    Route::get('average/brightness', 'CouchDBController@avgBright');
    Route::get('average/temperature', 'CouchDBController@avgTemp');
    Route::get('average/moisture', 'CouchDBController@avgMoisture');

    Route::get('now', 'CouchDBController@current');
});
