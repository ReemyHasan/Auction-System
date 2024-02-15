<?php

use App\Http\Controllers\AuctionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerBidController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
})->name('home');
Route::resource('categories',CategoryController::class)->middleware(["auth"]);
Route::resource('products',ProductController::class)->middleware(["auth"]);
Route::resource('auctions',AuctionController::class)->middleware(["auth"]);
Route::resource('auctions\{auction}\bids',CustomerBidController::class)->middleware(["auth"]);



