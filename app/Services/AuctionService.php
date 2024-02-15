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
}
