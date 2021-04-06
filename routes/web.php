<?php

use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/history', 'HomeController@history')->name('history');;
Route::resource('vote', 'VoteController',['only' => ['store']]);
Route::resource('poll', 'PollController',['except' => ['edit','update']]);
Route::resource('comment', 'CommentController',['only' => ['store']]);
Route::resource('user', 'UserController');
Route::get('/delete-images/user/{user_id}', 'UserController@deleteImages');
Route::post('user/{user}/follow', 'UserController@follow')->name('follow');
Route::post('user/{user}/unfollow', 'UserController@unfollow')->name('unfollow');
Route::get('user/{user}/followers', 'UserController@followers')->name('followers');
Route::get('user/{user}/following', 'UserController@following')->name('following');
