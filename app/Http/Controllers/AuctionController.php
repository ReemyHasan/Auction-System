<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuctionRequest;
use App\Models\auction;
use App\Notifications\TelegramNotification;
use App\Services\AuctionService;
use App\Services\CategoryService;
use App\Services\ProductService;
use ArgumentCountError;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;

class AuctionController extends Controller
{
    private $auctionService;
    private $productService;
    private $categoryService;

    public function __construct(AuctionService $auctionService, ProductService $productService, CategoryService $categoryService)
    {
        $this->auctionService = $auctionService;
        $this->productService = $productService;
        $this->categoryService = $categoryService;
    }
    public function index()
    {
        $auctions = $this->auctionService->getAll()->filter()->paginate(10);
        foreach ($auctions as $auction) {
            $status = $auction->setStatus();
            if ($auction->status != $status)
                $this->auctionService->update($auction, ['status' => $status]);
        }
        $categories = $this->categoryService->getAll()->get();
        return view("auctions.index", compact("auctions", "categories"));
    }

    public function create()
    {
        $this->authorize("create", Auction::class);
        $products = $this->productService->getMyProduct();
        return view("auctions.create", compact("products"));

    }

    public function store(AuctionRequest $request)
    {
        try {
            $product = $this->productService->getById($request->product_id);
            $this->authorize("auctions.create", $product);
            $validated = $request->validated();
            $this->auctionService->create($validated);
            return redirect()->route("auctions.index")->with("success", "New auction added successfully");
        } catch (ValidationException $e) {
            return redirect()->back();
        }
    }

    public function show($id)
    {
        $auction = $this->auctionService->getById($id);
        return view("auctions.show", compact("auction"));
    }

    public function edit($id)
    {
        $auction = $this->auctionService->getById($id);
        $this->authorize("update", $auction);
        return view("auctions.edit", compact("auction"));
    }

    public function update(AuctionRequest $request, $id)
    {
        try {
            $auction = $this->auctionService->getById($id);
            $this->authorize("update", $auction);
            $validated = $request->validated();
            $this->auctionService->update($auction, $validated);
            return redirect()->route("auctions.index")->with("success", "Auction updated successfully");
        } catch (ValidationException $e) {
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        $auction = $this->auctionService->getById($id);
        $this->authorize("delete", $auction);
        $this->auctionService->delete($auction);
        return redirect()->back()->with("success", "Auction deleted successfully");
    }

    public function add_interaction(Auction $auction)
    {
        $this->authorize("auctions.addInteractions", $auction);
        return view("auctions.add_interactions", compact("auction"));
    }

    public function store_interaction(Request $request, Auction $auction)
    {
        $this->authorize("auctions.addInteractions", $auction);
        $validated = $request->validate(
            [
                "rate" => "required",
                "comment" => "",
            ]
        );
        $validated["rate"] = trim($validated["rate"]);
        $validated["comment"] = trim($validated["comment"]);
        $validated["user_id"] = Auth::user()->id;
        // dd($validated);
        $this->auctionService->add_interaction($auction, $validated);
        return redirect()->route("auctions.index")->with("success", "thanks for your review");

    }
}
