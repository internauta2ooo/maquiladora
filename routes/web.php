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

Route::get('/crearordenentrega', function () {
    return view('crearordenentrega');
})->middleware('auth');

Route::get('/obtenermarcasauto', "OrdenController@obtenerMarcasAutocomplete")->middleware('auth');
Route::post('/crearordenmaquila', "OrdenController@crearOrdenMaquila")->middleware('auth');
Route::get('/ordenesmaquila', 'OrdenController@obtenerOrdenesMaquila')->middleware('auth');
// Route::get('/crearordenentrega', 'OrdenController@crearOrdenEntrega')->middleware('auth');
//Obtiene la orden en formato PDF
Route::get('/ordenpdf', 'OrdenController@crearPdfOrdenTrabajo')->middleware('auth');
//Obtiene la lista de las ordenes de maquila
Route::get('/obtenerordenesmaquila', 'OrdenController@obtenerOrdenesMaquilaTallas')->middleware('auth');
//Obtenemos la orden de articulos por entregar...
Route::get('/obtenerordenesparaentregar', 'OrdenController@obtenerOrdenesMaquilaParaEntregar');
//Obtenemos si se puede entregar el numero de piezas...
Route::get('/obtenernumerotallas', 'OrdenController@obtenerNumeroTallas');

Route::post('/guardarimagen', 'OrdenController@guardarImagen');

Route::get('/obtenerimagenes', 'OrdenController@obtenerImagenes');
Route::get('/eliminarimagen', 'OrdenController@eliminarImagen');

Auth::routes();
