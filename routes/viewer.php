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

Route::get('base','viewerController@baseGet')->name('baseGet');
Route::post('base','viewerController@basePost')->name('basePost');

Route::get('insights','viewerController@insightsGet')->name('insightsGet');
Route::post('insights','viewerController@insightsPost')->name('insightsPost');

