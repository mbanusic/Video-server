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

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function() {
	Route::get('/', ['as' => 'admin_home', 'uses' => 'IndexController@getIndex']);
	Route::group(['prefix' => 'video'], function() {
		Route::get('/', ['as' => 'video_index', 'uses' => 'VideoController@getIndex']);
		Route::get('/upload', ['as' => 'video_upload', 'uses' => 'VideoController@getUploadVideo']);
		Route::get('/edit/{id}', ['as' => 'video_edit', 'uses' => 'VideoController@getEditVideo']);
		Route::post('/upload', ['uses' => 'VideoController@postUploadVideo']);
		Route::post('edit/{id}', ['uses' => 'VideoController@postEditVideo']);
	});
});
