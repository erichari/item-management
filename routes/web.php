<?php

use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('items')->group(function () {
    Route::get('/', [App\Http\Controllers\ItemController::class, 'index'])->name('index');
    Route::get('/add', [App\Http\Controllers\ItemController::class, 'add']);
    Route::post('/add', [App\Http\Controllers\ItemController::class, 'add']);
    Route::get('/add/{draft}', [App\Http\Controllers\ItemController::class, 'add']);
    Route::post('/add/{draft}', [App\Http\Controllers\ItemController::class, 'add']);
    Route::get('/show/{id}', [App\Http\Controllers\ItemController::class, 'show']);
    Route::get('/edit/{id}', [App\Http\Controllers\ItemController::class, 'edit']);
    Route::post('/edit/{id}', [App\Http\Controllers\ItemController::class, 'edit']);
    Route::delete('/destroy/{id}', [App\Http\Controllers\ItemController::class, 'destroy']);
    Route::get('/editTag', [App\Http\Controllers\TagController::class, 'editTag']);
    Route::post('/editTag', [App\Http\Controllers\TagController::class, 'editTag']);
    Route::post('/search', [App\Http\Controllers\ItemController::class, 'search']);
});
