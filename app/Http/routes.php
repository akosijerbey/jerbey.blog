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

Route::group(['middleware' => 'web'], function () {

    Route::auth();
    Route::get('/home', 'HomeController@index');
    Route::post('/sendMessage', array('uses' => 'chatController@sendMessage','as' => 'sendMessage'));
    Route::post('/createRoom', array('uses' => 'chatController@createRoom','as' => 'createRoom'));
    Route::get('/test/{id}/{room}', array('uses' => 'chatController@test','as' => 'test'));
});

//Route::post('sendmessage', 'chatController@sendMessage');
