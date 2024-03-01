<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuctionRequest;
use App\Http\Requests\UpdateAuctionRequest;
use App\Models\Auction;
use App\Services\AuctionService;
use App\Services\CategoryService;
use App\Services\ProductService;
use App\Traits\CommonControllerFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuctionController extends Controller
{
    use CommonControllerFunctions;
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
        return $this->commonIndex("auctions.index", compact("auctions", "categories"));
    }

    public function create()
    {
        $products = $this->productService->getMyProduct();
        return $this->commonCreate(Auction::class, "auctions.create", compact("products"));

    }

    public function store(AuctionRequest $request)
    {

        $product = $this->productService->getById($request->product_id);
        $this->authorize("auctions.create", $product);
        $validated = $request->validated();

        return $this->commonStore($validated,Auction::class ,
        $this->auctionService, "auctions.index", "auction");
    }

    public function show($id)
    {
        return $this->commonShow($id, $this->auctionService, "auctions.show", "auction");

    }

    public function edit($id)
    {
        $auction = $this->auctionService->getById($id);
        return $this->commonEdit($auction,  "auctions.edit", compact("auction"));
    }

    public function update(UpdateAuctionRequest $request, $id)
    {
        $auction = $this->auctionService->getById($id);
        $validated = $request->validated();
        return $this->commonUpdate($validated, $auction, $this->auctionService, "auctions.index", "auction");

    }

    public function destroy($id)
    {
        return $this->commonDestroy($id, $this->auctionService, "Auction");
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
