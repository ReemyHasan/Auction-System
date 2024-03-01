<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Services\CategoryService;
use App\Services\ProductService;
use App\Traits\CommonControllerFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    use CommonControllerFunctions;
    private $productService;
    private $categoryService;
    public function __construct(ProductService $productService, CategoryService $categoryService)
    {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
    }
    public function index()
    {
        $products = $this->productService->getAll()->filter()->paginate(10);
        $categories = $this->categoryService->getAll()->get();
        return $this->commonIndex("products.index", compact("products", "categories"));
    }

    public function create()
    {
        $categories = $this->categoryService->getAll()->get();
        return $this->commonCreate(Product::class, "products.create", compact("categories"));
    }

    public function store(ProductRequest $request)
    {
        $categories = $request->category_id;
        $validated = $request->validated();
        $validated['vendor_id'] = Auth::user()->id;
        if ($request->hasFile('image')) {
            $validated['image'] = $this->productService->handleUploadedImage($request->file('image'), null);
        }
        return $this->commonStore($validated, Product::class,
        $this->productService, "products.index", "product",$categories);

    }

    public function show($id)
    {
        return $this->commonShow($id, $this->productService, "products.show", "product");
    }

    public function edit($id)
    {
        $product = $this->productService->getById($id);
        if (!$product)
            abort(404);
        $categories = $this->categoryService->getAll()->get();
        $product_categories = $product->categories->toArray();
        $checked = array();
        foreach ($product_categories as $product_category) {
            array_push($checked, $product_category['id']);
        }
        return $this->commonEdit($product,  "products.edit", compact("product", "categories", "checked"));

    }

    public function update(UpdateProductRequest $request, $id)
    {
        $product = $this->productService->getById($id);
        if (!$product)
            abort(404);
        $categories = $request->category_id;
        $validated = $request->validated();
        if ($request->hasFile('image')) {
            $validated['image'] = $this->productService->handleUploadedImage($request->file('image'), $product);
        }
        return $this->commonUpdate($validated, $product, $this->productService, "products.index", "product",$categories);
    }

    public function destroy($id)
    {
        return $this->commonDestroy($id, $this->productService, "Product");

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
        return redirect()->route("products.index")->with("success", "thanks for your review");
    }
}
