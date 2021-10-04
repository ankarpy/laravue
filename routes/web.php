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

Route::get('/', [App\Http\Controllers\QuestionsController::class, 'index']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('questions', App\Http\Controllers\QuestionsController::class)->except('show');
//Route::post('/questions/{question}/answers', [App\Http\Controllers\AnswersController::class, 'store'])->name('answers.store');
Route::resource('questions.answers', App\Http\Controllers\AnswersController::class)->except(['create', 'show']);
Route::get('/questions/{slug}', [App\Http\Controllers\QuestionsController::class, 'show'])->name('questions.show');

Route::post('/answers/{answer}/accept', [App\Http\Controllers\AnswersController::class, 'acceptAnswer'])->name('answers.accept');

Route::post('/questions/{question}/favorites', [App\Http\Controllers\FavoritesController::class, 'store'])->name('questions.favorite');
Route::delete('/questions/{question}/favorites', [App\Http\Controllers\FavoritesController::class, 'destroy'])->name('questions.unfavorite');

Route::post('/questions/{question}/vote', [App\Http\Controllers\QuestionsController::class, 'voteQuestion']);
Route::post('/answers/{answer}/vote', [App\Http\Controllers\AnswersController::class, 'voteAnswer']);
