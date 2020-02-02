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
    return  redirect()->route('product.index');
});

Route::get('invoice','ProductController@index' )->name('product.index');
Route::post('invoice', 'ProductController@store')->name('product.store');
Route::get('invoice/{id}', 'ProductController@generateInvoice')->name('generate.invoice');

// failed
//Route::get('new/invoice/{id}', 'ProductController@generateInvoiceTwo')->name('generate.invoice.two');
