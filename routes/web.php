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


use Goutte\Client;
use Orchestra\Parser\Xml\Facade as XmlParser;

Route::get('/','HomeController@index');
Route::post('/filter','HomeController@filter')->name('filter.data');
