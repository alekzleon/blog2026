<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AiPostController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsletterSubscriberController;
use App\Http\Controllers\PanelPostController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;

Route::get('/', [PostController::class, 'index'])->name('home');
Route::get('/blog', [PostController::class, 'index'])->name('posts.index');
Route::get('/blog/{post}', [PostController::class, 'show'])->name('posts.show');
Route::get('/categoria/{category}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/tag/{tag}', [TagController::class, 'show'])->name('tags.show');
Route::post('/newsletter/subscribe', [NewsletterSubscriberController::class, 'store'])->name('newsletter.subscribe');
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/panel/ia-posts', [AiPostController::class, 'create'])->name('panel.ai-posts.create');
    Route::post('/panel/ia-posts', [AiPostController::class, 'store'])->name('panel.ai-posts.store');
    Route::get('/panel/blogs', [PanelPostController::class, 'index'])->name('panel.posts.index');
    Route::get('/panel/blogs/{post}', [PanelPostController::class, 'show'])->name('panel.posts.show');
    Route::get('/panel/blogs/{post}/editar', [PanelPostController::class, 'edit'])->name('panel.posts.edit');
    Route::put('/panel/blogs/{post}', [PanelPostController::class, 'update'])->name('panel.posts.update');
    Route::delete('/panel/blogs/{post}', [PanelPostController::class, 'destroy'])->name('panel.posts.destroy');
    Route::get('/panel/suscritos', [NewsletterSubscriberController::class, 'index'])->name('panel.newsletter.index');
    Route::get('/panel/suscritos/export', [NewsletterSubscriberController::class, 'export'])->name('panel.newsletter.export');
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
});
