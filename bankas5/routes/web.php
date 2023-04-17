<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController as CL;
use App\Http\Controllers\AccountController as AC;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('clients')->name('clients-')->group(function() {
    Route::get('/', [CL::class, 'index'])->name('index');
    Route::get('/create', [CL::class, 'create'])->name('create');
    Route::post('/create', [CL::class, 'store'])->name('store');    
    Route::get('/edit/{client}', [CL::class, 'edit'])->name('edit');
    Route::put('/edit/{client}', [CL::class, 'update'])->name('update');
    Route::delete('/delete/{client}', [CL::class, 'destroy'])->name('delete');
});

Route::prefix('accounts')->name('accounts-')->group(function() {
    Route::get('/', [AC::class, 'index'])->name('index');
    Route::get('/create/{client}', [AC::class, 'store'])->name('store');
    // Route::post('/create', [AC::class, 'store'])->name('store');    
    Route::get('/edit/{account}', [AC::class, 'edit'])->name('edit');
    Route::put('/edit/{account}', [AC::class, 'update'])->name('update');
    Route::delete('/delete/{account}', [AC::class, 'destroy'])->name('delete');
});
