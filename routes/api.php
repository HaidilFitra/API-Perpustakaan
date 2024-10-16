<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BookController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\BorrowingController; 

// Authentication
Route::controller(AuthController::class)->group(function () {
  Route::post('/v1/register', 'register');
  Route::post('/v1/login', 'login');
  Route::delete('/v1/logout', 'logout')->middleware('auth:api');
});

// Book
Route::middleware('auth:api')->controller(BookController::class)->group(function () {
  Route::post('/v1/store/book', 'store');
  Route::get('/v1/show/book/{id}', 'show');
  Route::post('/v1/update/book/{id}', 'update');
  Route::delete('/v1/delete/book/{id}', 'destroy');
});

// Category
Route::middleware('auth:api')->controller(CategoryController::class)->group(function () {
  Route::post('/v1/store/category', 'store');
  Route::get('/v1/show/category/{id}', 'show');
  Route::post('/v1/update/category/{id}', 'update');
  Route::delete('/v1/destroy/category/{id}', 'destroy');
});

// Borrowing
Route::middleware('auth:api')->controller(BorrowingController::class)->group(function () {
  Route::post('/v1/store/borrowing', 'store'); 
  Route::get('/v1/show/borrowing/{id}', 'show'); 
  Route::post('/v1/update/borrowing/{id}', 'update'); 
  Route::delete('/v1/destroy/borrowing/{id}', 'destroy'); 
});

// For Fetching Data
Route::get('/v1/show/book', [BookController::class, 'index']);
