<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\User;
use App\Services\AuctionService;
use App\Services\BidService;

class CustomerController extends Controller
{
    private $auctionService;
    private $bidService;
    public function __construct(AuctionService $auctionService, BidService $bidService)
    {
        $this->auctionService = $auctionService;
        $this->bidService = $bidService;
    }
    public function myAuctions(User $customer)
    {
        $CustomerAuctions = $this->auctionService->getCustomerAuctions($customer);
        $auctions = array();
        foreach ($CustomerAuctions as $auction) {
            array_push($auctions, $this->auctionService->getById($auction->id));
        }
        return view("customer.my_auctions", compact("auctions"));
    }
    public function myAuctionBids(User $customer, Auction $auction)
    {
        $bids = $this->bidService->getCustomerBidsForAuction($customer, $auction)->paginate(10);
        return view("customer.my_auction_bids", compact("bids", "auction"));
    }
}
