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

route::get('/version', function () {
    return app()->version();
});

Route::get('/marca', function () {
    return view('marca');
})->middleware('auth');

Route::get('/obtenermarcasauto', "OrdenController@obtenerMarcasAutocomplete")->middleware('auth');
Route::post('/crearordenmaquila', "OrdenController@crearOrdenMaquila")->middleware('auth');
Route::get('/ordenesmaquila', 'OrdenController@obtenerOrdenesMaquila')->middleware('auth');
Route::get('/ordenpdf', 'OrdenController@crearPdfOrdenTrabajo')->middleware('auth');
Route::get('/obtenerordenesmaquila', 'OrdenController@obtenerOrdenesMaquilaTallas')->middleware('auth');

Auth::routes();
