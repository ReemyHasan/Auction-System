<?php

namespace App\Http\Controllers;

use App\Http\Requests\BidRequest;
use App\Models\Auction;
use App\Models\CustomerBid;
use App\Models\User;
use App\Notifications\NewBidAdded;
use App\Services\AuctionService;
use App\Services\bidService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Notification;

class CustomerBidController extends Controller
{
    private $bidService;
    private $auctionService;
    public function __construct(BidService $bidService, AuctionService $auctionService)
    {
        $this->bidService = $bidService;
        $this->auctionService = $auctionService;
    }
    public function index()
    {
        $bids = $this->bidService->getAll()->filter()->paginate(10);
        return view("bids.index", compact("bids"));
    }
    // for auction
    public function show($id)
    {
        $auction = $this->auctionService->getById($id);
        if ($auction) {
            $bids = $this->bidService->getAuctionBids($auction)->filter()->paginate(10);
            $customers = $this->auctionService->getAuctionCustomers($auction)->paginate(10);
            return view("bids.auction_bids", compact("bids", "auction", "customers"));
        } else {
            abort(404);
        }

    }

    public function store(BidRequest $request, Auction $auction)
    {
        try {
            $this->authorize("create", CustomerBid::class);
            if ($this->bidService->checkBidsAvailabilityTime($auction)) {
                $validated = $request->validated();
                $validated['customer_id'] = Auth::user()->id;
                $bid = $this->bidService->create($validated);
                $users = $this->auctionService->getAuctionCustomers($auction)->get();
                // dd($users);
                Notification::route('mail', $users)->notify(new NewBidAdded($bid));
                return redirect()->route("bids.show", $auction)->with("success", "your bid was added");
            } else {
                return redirect()->route("bids.show", $auction)->with("error", "auction was closed");
            }
        } catch (ValidationException $e) {
            return redirect()->back();
        } catch (AuthorizationException $e) {
            abort(403, 'Unauthorized');
        }
    }

    public function destroyAll(User $customer, Auction $auction)
    {
        if ($auction->status == 1) {
            $bids = $this->bidService->getCustomerBidsForAuction($customer, $auction)->get();
            foreach ($bids as $bid) {
                $this->bidService->delete($bid);
            }
            return redirect()->route("customer.auction.bids", compact("bids", "auction"))->with("success", "you leaved the auction");
        } else {
            return redirect()->back()->with("error", "auction closed");

        }
    }
    public function destroylatest(User $customer, Auction $auction)
    {
        if ($auction->status == 1) {
            $bids = $this->bidService->getCustomerBidsForAuction($customer, $auction)->get();
            // dd($bids[0]);
            $this->bidService->delete($bids[0]);
            return redirect()->back()->with("success", "deleted latest bid successfully");
        } else {
            return redirect()->back()->with("error", "auction closed");

        }
    }
}
