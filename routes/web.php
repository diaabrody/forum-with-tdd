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

Route::get('/', function () {
    return view('welcome');
});
//Route::resource("/threads" , 'ThreadController');
Route::get('/threads/create' , 'ThreadController@create');
Route::get('/threads' , 'ThreadController@index');
Route::get('/threads/{channel:slug}' , 'ThreadController@index');
Route::get('/threads/{channel:slug}/{thread}', 'ThreadController@show');
Route::delete('/threads/{channel:slug}/{thread}', 'ThreadController@destroy');

Route::post('/threads/{channel}/{thread}/replies' , 'ReplayController@store')
    ->middleware('auth');
Route::get('/threads/{channel}/{thread}/replies' , 'ReplayController@index');

Route::post('/threads' , 'ThreadController@store')
    ->middleware('auth');
Route::post("/replies/{replay}/favourites" , "FavouriteController@store");
Route::get("/profiles/{user:name}" , 'ProfileController@show');
Route::delete('/replay/{replay}' , 'ReplayController@destroy')
    ->name('replay.destroy');
Route::patch( '/replay/{replay}', 'ReplayController@update');

Route::delete("/replies/{replay}/favourites" , "FavouriteController@destroy");



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


