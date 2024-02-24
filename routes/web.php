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
    Route::get('auctions/{id}/bids',[CustomerBidController::class,'show'])->name('bids.show');
    Route::post('auctions/{auction}/bids/store',[CustomerBidController::class,'store'])->name('bids.store');
    Route::delete('customers/{customer}/auctions/{auction}/bids',[CustomerBidController::class,'destroylatest'])->name('bids.destroylatest');
    Route::get('customers/{customer}/auctions/{auction}',[CustomerBidController::class,'destroyAll'])->name('bids.leave_auction');
    Route::get('customers/{customer}/auctions',[CustomerController::class,'myAuctions'])->name('customer.auctions');
    Route::get('customers/{customer}/auctions/{auction}/bids',[CustomerController::class,'myAuctionBids'])->name('customer.auction.bids');
    Route::get('auctions/{auction}/add_interaction',[AuctionController::class,'add_interaction'])->name('auctions.add_interaction');
    Route::post('auctions/{auction}/store_interaction',[AuctionController::class,'store_interaction'])->name('auctions.store_interaction');

    Route::get('products/{product}/add_interaction',[ProductController::class,'add_interaction'])->name('products.add_interaction');
    Route::post('products/{product}/store_interaction',[ProductController::class,'store_interaction'])->name('products.store_interaction');


});



