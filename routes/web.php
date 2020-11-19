<?php

use Illuminate\Support\Facades\Route;

use Inertia\Inertia;

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

// redirects 
Route::get('/', function () {
    return redirect('/public');
});
Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return redirect()->route('movies.form');
})->name('dashboard');


// pages routes
Route::get('/public', 'App\Http\Controllers\MoviesController@index')->name('movies.index');
Route::middleware(['auth:sanctum', 'verified'])->
get('/admin', 'App\Http\Controllers\MoviesController@create')->name('movies.form');

// CRUD routes
Route::post('/store', 'App\Http\Controllers\MoviesController@store')->name('movies.store');
Route::post('/thumbs', 'App\Http\Controllers\MoviesController@thumbs')->name('movies.thumbs');
Route::get('/edit/{id}', 'App\Http\Controllers\MoviesController@edit')->name('movies.edit');