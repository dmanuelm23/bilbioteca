<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanBooksController;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('books', BookController::class)->only(['index', 'store', 'update', 'destroy'])->middleware('auth')->names('books');
Route::get('list-books', [BookController::class, 'listBooks'])->middleware('auth')->name('list-books');

Route::get('loan-books', [LoanBooksController::class, 'index'])->middleware('auth')->name('loan-books.index');
Route::post('loan-books/{id}', [LoanBooksController::class, 'loanBook'])->middleware('auth')->name('loan-books.loan-book');
Route::get('list-available-books', [LoanBooksController::class, 'listAvailableBooks'])->middleware('auth')->name('list-available-books');

Route::get('return-books', [LoanBooksController::class, 'indexReturnBook'])->middleware('auth')->name('return-books.index');
Route::get('list-borrowed-books', [LoanBooksController::class, 'listBorrowedBooks'])->middleware('auth')->name('list-borrowed-books');
Route::post('return-books/{id}', [LoanBooksController::class, 'returnBook'])->middleware('auth')->name('return-books.return-book');

