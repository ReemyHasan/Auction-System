<?php

namespace App\Http\Controllers;

use App\Http\Requests\BidRequest;
use App\Models\Auction;
use App\Models\CustomerBid;
use App\Services\BidService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerBidController extends Controller
{
    private $BidService;
    public function __construct(BidService $BidService){
        $this->BidService = $BidService;
    }
    public function index()
    {
        $bids = $this->BidService->getAll()->filter()->paginate(10);
        return view("bids.index", compact("bids"));
    }
    // for auction
    public function show(Auction $auction){
        if($this->BidService->checkBidsAvailabilityTime($auction))
        {
            $bids = $this->BidService->getAuctionBids($auction)->filter()->paginate(10);
        return view("bids.auction_bids", compact("bids", "auction"));
    }
    else{
        return redirect()->back()->with("error","you cannot enter the auction");
    }

    }

    public function store(BidRequest $request,Auction $auction)
    {
        $this->authorize("create", CustomerBid::class);
        if($this->BidService->checkBidsAvailabilityTime($auction)){
        $validated = $request->validated();
        $validated['customer_id'] = Auth::user()->id;
        $this->BidService->create($validated);
        return redirect()->route("bids.show", $auction)->with("success","your bid was added");
        }
        else{
        return redirect()->route("bids.show", $auction)->with("error","auction was closed");
        }
    }


    public function destroy(CustomerBid $customerBid)
    {
        //
    }
}
