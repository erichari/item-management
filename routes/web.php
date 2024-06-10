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

//管理者用ページ
Route::prefix('admin')->group(function () {
    Route::group(['middleware' => ['auth', 'can:admin']], function () {
        Route::get('/', [App\Http\Controllers\UserController::class, 'index'])->name('admin.index');
        Route::get('/users', [App\Http\Controllers\UserController::class, 'users']);
        Route::delete('/users/destroy{id}',[App\Http\Controllers\UserController::class, 'destroy'])->name('destroy');
        Route::any('/inquiry/{inquiry}', [App\Http\Controllers\UserController::class, 'inquiry']);
        Route::any('/info', [App\Http\Controllers\UserController::class, 'info']);
    });
});


Route::group(['middleware' => ['auth', 'can:general']], function () {
    Route::get('/', [App\Http\Controllers\ItemController::class, 'index'])->name('home');
    Route::prefix('items')->group(function () {
        Route::get('/', [App\Http\Controllers\ItemController::class, 'index'])->name('index');
        Route::get('/drafts', [App\Http\Controllers\ItemController::class, 'drafts']);
        Route::get('/add', [App\Http\Controllers\ItemController::class, 'addView']);
        Route::post('/add', [App\Http\Controllers\ItemController::class, 'add'])->name('add');
        Route::get('/add/draft', [App\Http\Controllers\ItemController::class, 'add']);
        Route::post('/add/draft', [App\Http\Controllers\ItemController::class, 'add'])->name('addDraft');
        Route::get('/add/scrape/{name}', [App\Http\Controllers\ItemController::class, 'addView']);
        Route::post('/add/scrape/cookpad', [App\Http\Controllers\ScrapingController::class, 'cookpadScrape']);
        Route::post('/add/scrape/rakuten', [App\Http\Controllers\ScrapingController::class, 'rakutenScrape']);
        Route::get('/show/{item}', [App\Http\Controllers\ItemController::class, 'show']);
        Route::get('/edit/{item}', [App\Http\Controllers\ItemController::class, 'editView']);
        Route::post('/edit/{id}', [App\Http\Controllers\ItemController::class, 'edit'])->name('edit');
        Route::get('/edit/draft/{id}', [App\Http\Controllers\ItemController::class, 'drafts']);
        Route::post('/edit/draft/{id}', [App\Http\Controllers\ItemController::class, 'edit'])->name('returnDraft');
        Route::get('/destroy/{item}', [App\Http\Controllers\ItemController::class, 'index']);
        Route::delete('/destroy/{item}', [App\Http\Controllers\ItemController::class, 'destroy']);
        Route::get('/editTag', [App\Http\Controllers\TagController::class, 'editTagView']);
        Route::post('/editTag', [App\Http\Controllers\TagController::class, 'editTag']);
        Route::get('/search', [App\Http\Controllers\ItemController::class, 'search']);
    });

    Route::get('/notice', [App\Http\Controllers\InquiryController::class, 'notice']);
    Route::post('/notice', [App\Http\Controllers\InquiryController::class, 'inquiry']);
    Route::patch('/notice', [App\Http\Controllers\InquiryController::class, 'notice']);
});