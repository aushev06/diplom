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


$path = explode('.', request()->path());

dd($path);

if (sizeof($path) === 1 && end($path) !== 'ru') {
    Route::get('{any}', function () {
        return view('main');
    })->where('any', '=');
    Auth::routes();
}


