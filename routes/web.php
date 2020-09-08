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
// Route::view("/marca","marca");
route::get('/version', function () {
    return app()->version();
});
// Auth::routes();

// Route::view("/marca","marca");


Route::get('/home', 'HomeController@index')->name('home');
Route::get('/marca', function () {
    return view('marca');
})->middleware('auth');

Route::get('/marcas', function () {
    return view('marcas');
})->middleware('auth');
Route::get('/marcastest', function () {
    return view('marcatest');
})->middleware('auth');


Route::get('/crearorden', "OrdenController@crearOrden");

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
