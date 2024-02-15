<?php

namespace App\Http\Controllers;

use App\Http\Requests\BidRequest;
use App\Models\Auction;
use App\Models\CustomerBid;
use App\Models\User;
use App\Services\AuctionService;
use App\Services\bidService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerBidController extends Controller
{
    private $bidService;
    private $auctionService;
    public function __construct(BidService $bidService, AuctionService $auctionService){
        $this->bidService = $bidService;
        $this->auctionService = $auctionService;
    }
    public function index()
    {
        $bids = $this->bidService->getAll()->filter()->paginate(10);
        return view("bids.index", compact("bids"));
    }
    // for auction
    public function show(Auction $auction){
        if($this->bidService->checkBidsAvailabilityTime($auction))
        {
            $bids = $this->bidService->getAuctionBids($auction)->filter()->paginate(10);
            $customers = $this->auctionService->getAuctionCustomers($auction)->paginate(10);
        return view("bids.auction_bids", compact("bids", "auction","customers"));
    }
    else{
        return redirect()->back()->with("error","you cannot enter the auction");
    }

    }

    public function store(BidRequest $request,Auction $auction)
    {
        $this->authorize("create", CustomerBid::class);
        if($this->bidService->checkBidsAvailabilityTime($auction)){
        $validated = $request->validated();
        $validated['customer_id'] = Auth::user()->id;
        $this->bidService->create($validated);
        return redirect()->route("bids.show", $auction)->with("success","your bid was added");
        }
        else{
        return redirect()->route("bids.show", $auction)->with("error","auction was closed");
        }
    }

    public function destroyAll(User $customer, Auction $auction)
    {
        $bids = $this->bidService->getCustomerBidsForAuction($customer, $auction)->get();
        foreach($bids as $bid){
            $this->bidService->delete($bid);
        }
        return redirect()->route("bids.show", $auction)->with("success","you leaved the auction");
    }
    public function destroylatest(User $customer, Auction $auction)
    {
        $bids = $this->bidService->getCustomerBidsForAuction($customer, $auction)->get();
        // dd($bids[0]);
        $this->bidService->delete($bids[0]);
        return redirect()->back()->with("success","deleted latest bid successfully");
    }
}
