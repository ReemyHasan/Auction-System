<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuctionRequest;
use App\Http\Requests\UpdateAuctionRequest;
use App\Models\Auction;
use App\Services\AuctionService;
use App\Services\CategoryService;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $validated = $request->validated();
        $product = $this->productService->getById($request->product_id);
        $this->authorize("auctions.create", $product);
        $this->auctionService->create($validated);
        return redirect()->route("auctions.index")->with("success", "New auction added successfully");
    }

    public function show($id)
    {
        $auction = $this->auctionService->getById($id);
        if (!$auction)
            abort(404);
        return view("auctions.show", compact("auction"));

    }

    public function edit($id)
    {
        $auction = $this->auctionService->getById($id);
        if (!$auction)
            abort(404);
        $this->authorize("update", $auction);
        return view("auctions.edit", compact("auction"));
    }

    public function update(UpdateAuctionRequest $request, $id)
    {
        $auction = $this->auctionService->getById($id);
        if (!$auction)
            abort(404);
        $this->authorize("update", $auction);
        $validated = $request->validated();
        $this->auctionService->update($auction, $validated);
        return redirect()->route("auctions.index")->with("success", "Auction updated successfully");
    }

    public function destroy($id)
    {
        $auction = $this->auctionService->getById($id);
        if (!$auction)
            abort(404);

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
        $this->auctionService->add_interaction($auction, $validated);
        return redirect()->route("auctions.index")->with("success", "thanks for your review");
    }
}
