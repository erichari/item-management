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

Route::get('/', [App\Http\Controllers\ItemController::class, 'index'])->name('home');

//管理者用ページ
Route::prefix('admin')->group(function () {
    Route::group(['middleware' => ['auth', 'can:admin']], function () {
        Route::get('/', [App\Http\Controllers\UserController::class, 'index'])->name('admin.index');
        Route::get('/users', [App\Http\Controllers\UserController::class, 'users']);
        Route::delete('/users/destroy{id}',[App\Http\Controllers\UserController::class, 'destroy'])->name('destroy');
        Route::get('/inquiry/{id}', [App\Http\Controllers\UserController::class, 'inquiry']);
        Route::post('/inquiry/{id}', [App\Http\Controllers\UserController::class, 'inquiry']);
        Route::patch('/inquiry/{id}', [App\Http\Controllers\UserController::class, 'inquiry']);
        Route::get('/info', [App\Http\Controllers\UserController::class, 'info']);
        Route::post('/info', [App\Http\Controllers\UserController::class, 'info']);
    });
});


Route::prefix('items')->group(function () {
    Route::get('/', [App\Http\Controllers\ItemController::class, 'index'])->name('index');
    Route::get('/drafts', [App\Http\Controllers\ItemController::class, 'drafts']);
    Route::get('/add', [App\Http\Controllers\ItemController::class, 'addView']);
    Route::post('/add', [App\Http\Controllers\ItemController::class, 'add'])->name('add');
    Route::post('/add/draft', [App\Http\Controllers\ItemController::class, 'add'])->name('addDraft');
    Route::get('/scrape', [App\Http\Controllers\ItemController::class, 'addView']);
    Route::post('/scrape/cookpad', [App\Http\Controllers\ScrapingController::class, 'cookpadScrape']);
    Route::post('/scrape/rakuten', [App\Http\Controllers\ScrapingController::class, 'rakutenScrape']);
    Route::get('/show/{id}', [App\Http\Controllers\ItemController::class, 'show']);
    Route::get('/edit/{id}', [App\Http\Controllers\ItemController::class, 'editView']);
    Route::post('/edit/{id}', [App\Http\Controllers\ItemController::class, 'edit'])->name('edit');
    Route::post('/edit/draft/{id}', [App\Http\Controllers\ItemController::class, 'edit'])->name('returnDraft');
    Route::delete('/destroy/{id}', [App\Http\Controllers\ItemController::class, 'destroy']); //getの場合エラーでる
    Route::get('/editTag', [App\Http\Controllers\TagController::class, 'editTagView']);
    Route::post('/editTag', [App\Http\Controllers\TagController::class, 'editTag']);
    Route::get('/search', [App\Http\Controllers\ItemController::class, 'search']);
});

Route::get('/notice', [App\Http\Controllers\InquiryController::class, 'notice']);
Route::post('/inquiry', [App\Http\Controllers\InquiryController::class, 'inquiry']);
Route::post('/status', [App\Http\Controllers\InquiryController::class, 'change_status']);