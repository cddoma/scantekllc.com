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
    Route::get('/ro/create/{vehicleId}', function ($vehicleId) { return view('ro.show', ['vehicleId' => $vehicleId]); })->name('ro.create');

    Route::get('/vehicles', function () { return view('vehicles.index'); })->name('vehicles.index');
    Route::get('/vehicle/create', function () { return view('vehicles.show'); })->name('vehicles.create');
    Route::get('/vehicle/{id}', function ($id) { return view('vehicles.show', ['vehicleId' => $id]); })->name('vehicles.show');

    Route::get('/admin/makes', function () { return view('vehicles.makes'); })->name('vehicles.makes');
    Route::get('/admin/models', function () { return view('vehicles.models'); })->name('vehicles.models');
    Route::get('/admin/make/{id}/models', function ($id) { return view('vehicles.models', ['make_id' => $id]); })->name('vehicles.make.models');
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
