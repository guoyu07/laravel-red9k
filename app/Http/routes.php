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

    Route::get('/', function () { return view('welcome'); });
    Route::get('/posts', 'PostController@index');
    Route::get('/posts/category/{category}', ['as' => 'category', 'uses' => 'PostController@category']);
	Route::get('/post/create', 'PostController@create')->middleware('auth');
    Route::post('/post/edit/{post}', ['as' => 'edit', 'uses' => 'PostController@edit'])->middleware('auth');
    Route::post('/post', 'PostController@store')->middleware('auth');
	Route::post('/post/{post}/up', 'PostController@up')->middleware('auth');
	Route::post('/post/{post}/down', 'PostController@down')->middleware('auth');
    Route::delete('/post/{post}/delete', 'PostController@destroy')->middleware('auth');
    Route::get('user/{user}', ['as' => 'user', 'uses' => 'UserController@index'])->middleware('auth');
    Route::auth();
});