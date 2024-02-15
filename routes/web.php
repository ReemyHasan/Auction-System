<?php

use App\Http\Controllers\AuctionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerBidController;
use App\Http\Controllers\CustomerController;
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
    Route::get('customers/{customer}/auctions',[CustomerController::class,'myAuctions'])->name('customer.auctions');
    Route::get('customers/{customer}/auctions/{auction}/bids',[CustomerController::class,'myAuctionBids'])->name('customer.auction.bids');
    Route::post('auctions/{auction}/bids/store',[CustomerBidController::class,'store'])->name('bids.store');
    Route::delete('customers/{customer}/auctions/{auction}/bids/{id}',[CustomerBidController::class,'destroy'])->name('bids.destroy');
});



