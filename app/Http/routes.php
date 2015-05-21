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
Route::get('clear', 'HomeController@clear');
Route::get('dummy', 'HomeController@dummy');

Route::group(['prefix' => 'api'], function () {
    Route::resource(getenv('COUCHDB_OBJECT'), 'CouchDBController');
});
