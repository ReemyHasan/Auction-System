<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuctionRequest;
use App\Models\auction;
use App\Services\AuctionService;
use App\Services\CategoryService;
use App\Services\ProductService;

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
        $product = $this->productService->getById($request->product_id);
        $this->authorize("auctions.create", $product);
        $validated = $request->validated();
        $this->auctionService->create($validated);
        return redirect()->route("auctions.index")->with("success", "New auction added successfully");
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
        $auction = $this->auctionService->getById($id);
        $this->authorize("update", $auction);
        $validated = $request->validated();
        $this->auctionService->update($auction, $validated);
        return redirect()->route("auctions.index")->with("success", "Auction updated successfully");
    }

    public function destroy($id)
    {
        $auction = $this->auctionService->getById($id);
        $this->authorize("delete", $auction);
        $this->auctionService->delete($auction);
        return redirect()->back()->with("success", "Auction deleted successfully");
    }
}
