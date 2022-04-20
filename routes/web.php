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


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/', function () {
        return view('dashboard');
        // return view('welcome');
    });
    Route::get('/welcome', function () {
        return view('welcome');
    });
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/users', function () {
        return view('users.index');
    })->name('users.index');
        Route::get('/print_r', function () {
            print '<pre>';
            print 'ENV';
            print_r($_ENV);
            print '</pre>';

            print '<pre>';
            print 'SERVER';
            print_r($_SERVER);
            print '</pre>';

            print '<pre>';
            print 'REQUEST';
            print_r($_REQUEST);
            print '</pre>';
            return view('dashboard');
            // return view('welcome');
        });
});
