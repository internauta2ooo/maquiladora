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
// Auth::routes();
Route::view("/marca","marca");

// Auth::routes();

// Route::view("/marca","marca");


// Route::get('/home', 'HomeController@index')->name('home');
Route::get('/run', function () {
    return view('welcome');
})->middleware("auth");


