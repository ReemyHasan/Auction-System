<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Services\CategoryService;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    private $productService;
    private $categoryService;
    public function __construct(ProductService $productService, CategoryService $categoryService){
        $this->productService = $productService;
        $this->categoryService = $categoryService;
    }
    public function index()
    {
        $products = $this->productService->getAll()->filter();
        $categories = $this->categoryService->getAll()->get();
        return view("products.index", ["products"=>$products->paginate(10),"categories"=> $categories]);
    }

    public function create()
    {
        $this->authorize("create", Product::class);
        $categories = $this->categoryService->getAll()->get();
        return view("products.create", compact("categories"));
    }

    public function store(ProductRequest $request)
    {
        $this->authorize("create", Product::class);
        $validated = $request->validated();
        $validated['vendor_id'] = Auth::user()->id;
        if($request->hasFile('image')){
        $validated['image'] = $this->productService->handleUploadedImage($request->file('image'),null);
        }
        $this->productService->create($validated);
        return redirect()->route("products.index")->with("success","New product added successfully");
    }

    public function show($id)
    {
        $product = $this->productService->getById($id);
        return view("products.show", compact("product"));
    }

    public function edit($id)
    {
        $product = $this->productService->getById($id);
        $this->authorize("update", $product);
        $categories = $this->categoryService->getAll()->get();
        return view("products.edit", compact("product","categories"));
    }

    public function update(ProductRequest $request, $id)
    {
        // dd($request->all());
        $product = $this->productService->getById($id);
        $this->authorize("update", $product);
        $validated = $request->validated();
        if($request->hasFile('image')){
        $validated['image'] = $this->productService->handleUploadedImage($request->file('image'),$product);
        }
        $this->productService->update($product,$validated);
        return redirect()->route("products.index")->with("success","product updated successfully");
    }

    public function destroy($id)
    {
        $product = $this->productService->getById($id);
        $this->authorize("delete", $product);
        $this->productService->delete($product);
        return redirect()->back()->with("success","Product deleted successfully");
    }

    public function add_interaction(Product $product)
    {
        $this->authorize("products.addInteractions", $product);
        return view("products.add_interactions", compact("product"));
    }

    public function store_interaction(Request $request, Product $product)
    {
        $this->authorize("products.addInteractions", $product);
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
        $this->productService->add_interaction($product, $validated);
        return redirect()->route("products.index")->with("success","thanks for your review");

    }
}
