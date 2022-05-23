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
    Route::get('/', function () { return redirect()->route('ro.index'); });
    // Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');
    // Route::get('/welcome', function () { return view('welcome'); });
    Route::get('/users', function () { return view('users.index'); })->name('users.index');
    Route::get('/user/{id}', function () { return view('users.show'); })->name('users.show');

    Route::get('/repair-orders', function () { return view('ro.index'); })->name('ro.index');
    Route::get('/ro/create', function () { 
        $teamId = \Auth::user()->current_team_id ?? 0; 
        $vehicle = \App\Models\Vehicle::create(['team_id' => $teamId]);
        $ro = \App\Models\RepairOrder::create([
            'priority' => 1, 
            'status' => 'requested', 
            'team_id' => $teamId, 
            'created_by' => \Auth::user()->id, 
            'vehicle_id' => $vehicle->id,
        ]);
        return redirect()->route('ro.show', $ro->id);
    })->name('ro.create');
    Route::get('/ro/{id}', function ($id) { 
        $ro = \App\Models\RepairOrder::getByRO($id);
        $ro_id = !empty($ro->ro) ? $ro->ro : $ro->id;
        return view('ro.show', ['ro_id' => $ro_id, 'vehicleId' => $ro->vehicle->id]); 
    })->name('ro.show');


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
