<?php

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

Route::get('/usuarios', function () {
  return "Usuarios";
});

Route::get('/usuarios/nuevo', function () {
  return "Crear Nuevo Usuario";
});

Route::get('/usuarios/{id}', function ($id) {
  return "Mostrando detalle del usuario: {$id}";
})->where(['id' => '[\d]+']);

Route::get('/saludo/{nombre}/{apodo?}', function ($nombre, $apodo = null) {
  if($apodo) { return "Bienvenido {$nombre}, tu apodo es {$apodo}"; }
  else { return "Bienvenido {$nombre}, sin apodo"; }
});