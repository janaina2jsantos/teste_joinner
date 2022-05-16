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
    return redirect()->route('pessoas.index');
});

Route::get('/pessoas', 'PessoaController@index')->name('pessoas.index');
Route::get('/ajax/pessoas', 'PessoaController@indexAjax')->name('pessoas.index.ajax');
Route::get('/pessoas/cadastrar', 'PessoaController@create')->name('pessoas.create');
Route::post('/pessoas/cadastrar', 'PessoaController@store')->name('pessoas.store');
Route::get('/pessoas/editar/{id}', 'PessoaController@edit')->name('pessoas.edit');
Route::put('/pessoas/editar/{id}', 'PessoaController@update')->name('pessoas.update');
Route::delete('/pessoas/deletar/{id}', 'PessoaController@destroy')->name('pessoas.destroy');

