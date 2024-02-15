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

Route::group(["middleware"=> "auth"], function () {
    Route::get('bids',[CustomerBidController::class,'index'])->name('bids.index');
    Route::get('auctions/{auction}/bids',[CustomerBidController::class,'show'])->name('bids.show');
    Route::post('auctions/{auction}/bids/store',[CustomerBidController::class,'store'])->name('bids.store');
    Route::delete('auctions/{auction}/bids/{id}',[CustomerBidController::class,'destroy'])->name('bids.destroy');
});



