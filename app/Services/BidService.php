<?php

namespace App\Services;
use App\Models\CustomerBid;

class BidService
{
    public function getAll()
    {
        $auctions = CustomerBid::getRecords();
        return $auctions;
    }
    public function create($data)
    {
        return CustomerBid::create($data);
    }
    public function getById($id)
    {
        $auction = CustomerBid::getRecord($id);
        return $auction;
    }
    public function delete($auction)
    {
        return $auction->delete();
    }
    public function getAuctionBids($auction){
        return $auction->bids();
    }

    public function getCustomerBids($user){
        return $user->bids();
    }
}
