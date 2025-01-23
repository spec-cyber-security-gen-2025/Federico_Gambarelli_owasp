<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Models\Article;

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

Route::get('/', [ArticleController::class, 'index'])->name('home');
// UNSECURE
Route::get('/articles/search', [ArticleController::class, 'search'])->name('articles.search');
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');

Route::middleware(['auth'])->group(function () {

    Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create');
    Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
    Route::get('/articles/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
    Route::put('/articles/{article}', [ArticleController::class, 'update'])->name('articles.update');
    Route::get('/articles/{article}/delete', [ArticleController::class, 'destroy'])->can('delete','article')->name('articles.destroy');
    Route::post('/articles/import-xml', [ArticleController::class, 'importXml'])->name('articles.importXml');
    Route::get('/articles/{article}/export-xml', [ArticleController::class, 'exportXml'])->name('articles.exportXml');

    Route::get('/profile',[UserController::class,'show'])->name('profile');



    Route::patch('/users/{id}/update',[UserController::class,'update'])->name('users.update');
    Route::post('/users/name/change',[UserController::class,'changeName'])->name('change.name');
    Route::get('/users/email/change',[UserController::class,'changeEmail'])->name('change.email');
    Route::post('/users/img/change',[UserController::class,'changeImg'])->name('change.img');

    Route::post('/upload',[UserController::class, 'upload'])->name('upload');

    // UNSECURE V1
    // Route::get('/download-doc', [UserController::class,'downloadReq'])->name('download-req');
    // UNSECURE V2
    // Route::get('/download-doc/{fileName}', [UserController::class,'downloadParam'])->name('download-param');
    Route::get('/PrivacyPolicy', [UserController::class,'privacyPolicy'])->name('privacyPolicy');

    Route::get('/Cookie', [UserController::class, 'cookiePolicy'])->name('cookiePolicy');

    Route::get('/ServiceAgreements', [UserController::class, 'serviceAgreements'])->name('serviceAgreements');


    // SECURE
    //Route::prefix('dashboard')->middleware('admin')->group(function () {
    // UNSECURE
    Route::prefix('dashboard')->group(function () {
        Route::get('/', [AdminController::class,'dashboard'])->name('dashboard');
        Route::get('/articles', [AdminController::class,'articles'])->name('admin.articles');
        Route::get('/users', [AdminController::class,'users'])->name('admin.users');

        // UNSECURE
        Route::get('/users/{id}/toggle', [AdminController::class,'toggleUsersAdmin'])->name('admin.users.toggle');
        // SECURE
        // Route::post('/users/{id}/toggle', [AdminController::class,'toggleUsersAdmin'])->name('admin.users.toggle');

        Route::get('articles/{id}/toggle',[AdminController::class,'toggleArticleStatus'])->name('admin.articles.toggle');
        Route::get('/financial-data',[AdminController::class,'getFinancialData'])->name('admin.financialData');
    });
    // UNSECURE
    Route::post('/articles/{articleId}/comments', [CommentController::class, 'store'])->middleware('block.suspicious')->name('comments.store');
});

Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('articles.show');
