<?php

use App\Http\Livewire\Game;
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

Route::get('/', Game::class)->name('home');

Route::view('blog', 'blog.intro')->name('blog.intro');
Route::view('blog.chapter-1', 'blog.chapter-1')->name('blog.chapter-1');
Route::view('blog.chapter-2', 'blog.chapter-2')->name('blog.chapter-2');
Route::view('blog.chapter-3', 'blog.chapter-3')->name('blog.chapter-3');
Route::view('blog.chapter-4', 'blog.chapter-4')->name('blog.chapter-4');
Route::view('blog.chapter-5', 'blog.chapter-5')->name('blog.chapter-5');
Route::view('blog.chapter-6', 'blog.chapter-6')->name('blog.chapter-6');
