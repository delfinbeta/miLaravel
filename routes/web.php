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

Route::get('/usuarios', 'UserController@index');

Route::get('/usuarios/{id}', 'UserController@show')
	->where(['id' => '[\d]+']);

Route::get('/usuarios/nuevo', 'UserController@create');

Route::get('/usuarios/{id}/edit', 'UserController@edit')
	->where(['id' => '[\d]+']);

Route::get('/saludo/{nombre}', 'WelcomeUserController@index');

Route::get('/saludo/{nombre}/{apodo?}', 'WelcomeUserController@index2');