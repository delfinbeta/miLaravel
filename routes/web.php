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

Route::get('/usuarios', 'UserController@index')
	->name('users.list');

Route::get('/usuarios/{user}', 'UserController@show')
	->where(['user' => '[\d]+'])
	->name('users.show');

Route::get('/usuarios/nuevo', 'UserController@create')
	->name('users.create');

Route::post('/usuarios/nuevo', 'UserController@store')
	->name('users.store');

Route::get('/usuarios/{user}/edit', 'UserController@edit')
	->where(['user' => '[\d]+'])
	->name('users.edit');

Route::put('/usuarios/{user}', 'UserController@update');

Route::get('/usuarios/papelera', 'UserController@trashed')
	->name('users.trashed');

Route::patch('/usuarios/{user}/papelera', 'UserController@trash')
	->name('users.trash');

Route::delete('/usuarios/{id}', 'UserController@destroy')
	->name('users.delete');

Route::get('/perfil', 'ProfileController@edit');

Route::put('/perfil', 'ProfileController@update');

Route::get('/profesiones', 'ProfessionController@index');

Route::delete('/profesiones/{profession}', 'ProfessionController@destroy')
	->name('professions.delete');

Route::get('/habilidades', 'SkillController@index');

Route::get('/saludo/{nombre}', 'WelcomeUserController@index');

Route::get('/saludo/{nombre}/{apodo?}', 'WelcomeUserController@index2');