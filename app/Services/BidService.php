<?php

namespace App\Services;

use App\Models\CustomerBid;
use Carbon\Carbon;

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
    public function getAuctionBids($auction)
    {
        return $auction->bids();
    }

    public function getCustomerBids($user)
    {
        return $user->bids();
    }
    public function getCustomerBidsForAuction($user, $auction)
    {
        return CustomerBid::getCustomerBidsForAuction($user, $auction);
    }
    public function checkBidsAvailabilityTime($auction)
    {
        if ($auction->start_time < Carbon::now() && $auction->closing_time > Carbon::now())
            return true;
        else
            return false;
    }
}
