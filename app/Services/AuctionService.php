<?php

namespace App\Services;

use App\Models\Auction;
use Illuminate\Support\Facades\Storage;

class AuctionService
{
    public function getAll()
    {
        $auctions = Auction::getRecords();
        return $auctions;
    }
    public function create($data)
    {
        return Auction::create($data);
    }
    public function getById($id)
    {
        $auction = Auction::getRecord($id);
        return $auction;
    }
    public function update($auction, $validated)
    {
        return $auction->update($validated);
    }
    public function delete($auction)
    {
        return $auction->delete();
    }
    public function getCustomerAuctions($user)
    {
        return $user->getCustomerAuctions();
    }
    public function getAuctionCustomers($auction)
    {
        return $auction->getAuctionCustomers();
    }
    public function add_interaction($auction, $interaction){
        $userInteraction = $auction->interactions()->where("user_id", $interaction['user_id'])->first();
        // return $userInteraction;
        if($userInteraction == null ){
        $auction->interactions()->create($interaction);
        }
        else {
            $userInteraction->update($interaction);
        }
    }
}
