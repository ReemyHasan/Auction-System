<?php

namespace App\Services;

use App\Models\CustomerBid;
use Carbon\Carbon;

class BidService
{
    public function getAll()
    {
        $bids = CustomerBid::getRecords();
        return $bids;
    }
    public function create($data)
    {
        return CustomerBid::create($data);
    }
    public function getById($id)
    {
        $bid = CustomerBid::getRecord($id);
        return $bid;
    }
    public function delete($bid)
    {
        return $bid->delete();
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
