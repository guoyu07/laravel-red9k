<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {

    Route::get('/', function () {
        return view('welcome');
    })->middleware('guest');

    Route::get('/posts', 'PostController@index');
	Route::get('/myposts', 'PostController@userIndex');
	Route::get('/post/create', 'PostController@create');
    Route::post('/post', 'PostController@store');
	Route::post('/post/{post}/up', 'PostController@up');
	Route::post('/post/{post}/down', 'PostController@down');
    Route::delete('/post/{post}/delete', 'PostController@destroy');
    Route::auth();
});