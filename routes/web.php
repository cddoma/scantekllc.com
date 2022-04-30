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


    Route::impersonate();
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/', function () { return view('dashboard'); });
    Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');
    // Route::get('/welcome', function () { return view('welcome'); });
    Route::get('/users', function () { return view('users.index'); })->name('users.index');
    Route::get('/user/{id}', function () { return view('users.show'); })->name('users.show');

    Route::get('/ro', function () { return view('ro.index'); })->name('ro.index');
    Route::get('/ro/{id}', function () { return view('ro.show'); })->name('ro.show');
    Route::get('/ro/create', function () { return view('ro.create'); })->name('ro.create');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {



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
