<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Services\CategoryService;
use App\Services\ProductService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    private $productService;
    private $categoryService;
    public function __construct(ProductService $productService, CategoryService $categoryService)
    {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
    }
    public function index()
    {
        $products = $this->productService->getAll()->filter();
        $categories = $this->categoryService->getAll()->get();
        return view("products.index", ["products" => $products->paginate(10), "categories" => $categories]);
    }

    public function create()
    {
        try {
            $this->authorize("create", Product::class);
            $categories = $this->categoryService->getAll()->get();
            return view("products.create", compact("categories"));
        } catch (AuthorizationException $e) {
            abort(403, 'Unauthorized');
        }
    }

    public function store(ProductRequest $request)
    {
        try {
            $this->authorize("create", Product::class);
            $categories = $request->category_id;
            $validated = $request->validated();
            $validated['vendor_id'] = Auth::user()->id;
            if ($request->hasFile('image')) {
                $validated['image'] = $this->productService->handleUploadedImage($request->file('image'), null);
            }
            $product = $this->productService->create($validated);
            if ($product && !empty($categories)) {
                $this->productService->attach_with_categories($product, $categories);
            }
            return redirect()->route("products.index")->with("success", "New product added successfully");

        } catch (ValidationException $e) {
            return redirect()->back();
        } catch (AuthorizationException $e) {
            abort(403, 'Unauthorized');
        }
    }

    public function show($id)
    {
        $product = $this->productService->getById($id);

        if ($product) {
            return view("products.show", compact("product"));
        } else {
            abort(404);
        }
    }

    public function edit($id)
    {
        try {
            $product = $this->productService->getById($id);
            if ($product) {
                $this->authorize("update", $product);
                $categories = $this->categoryService->getAll()->get();
                $product_categories = $product->categories->toArray();
                $checked = array();
                foreach ($product_categories as $product_category) {
                    array_push($checked, $product_category['id']);
                }
                return view("products.edit", compact("product", "categories", "checked"));
            } else {
                abort(404);
            }
        } catch (AuthorizationException $e) {
            abort(403, 'Unauthorized');
        }
    }

    public function update(UpdateProductRequest $request, $id)
    {
        try {
            $product = $this->productService->getById($id);
            if ($product) {
                $this->authorize("update", $product);
                $categories = $request->category_id;
                $validated = $request->validated();
                if ($request->hasFile('image')) {
                    $validated['image'] = $this->productService->handleUploadedImage($request->file('image'), $product);
                }
                $this->productService->update($product, $validated);
                $this->productService->update_attachments($product, $categories);
                return redirect()->route("products.index")->with("success", "product updated successfully");
            } else {
                abort(404);
            }
        } catch (ValidationException $e) {
            return redirect()->back();
        } catch (AuthorizationException $e) {
            abort(403, 'Unauthorized');
        }
    }

    public function destroy($id)
    {
        try {
            $product = $this->productService->getById($id);
            if ($product) {
                $this->authorize("delete", $product);
                $this->productService->detach_with_categories($product);
                $this->productService->delete($product);
                return redirect()->back()->with("success", "Product deleted successfully");
            } else {
                abort(404);
            }
        } catch (AuthorizationException $e) {
            abort(403, 'Unauthorized');
        }
    }

    public function add_interaction(Product $product)
    {
        try {
            $this->authorize("products.addInteractions", $product);
            return view("products.add_interactions", compact("product"));
        } catch (AuthorizationException $e) {
            abort(403, 'Unauthorized');
        }
    }

    public function store_interaction(Request $request, Product $product)
    {
        try {
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
        } catch (AuthorizationException $e) {
            abort(403, 'Unauthorized');
        }
    }
}
