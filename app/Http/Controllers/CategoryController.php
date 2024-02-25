<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    private $categoryService;
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $categories = $this->categoryService->getAll()->filter();
        if ($categories)
            return view("categories.index", ["categories" => $categories->paginate(10)]);
    }

    public function create()
    {
        $this->authorize("create", Category::class);
        return view("categories.create");
    }

    public function store(CategoryRequest $request)
    {
        $this->authorize("create", Category::class);
        $validated = $request->validated();
        $validated['created_by'] = Auth::user()->id;
        $category = $this->categoryService->create($validated);
        return redirect()->route("categories.index")->with("success", "New category added successfully");
    }

    public function show(string $id)
    {
        $category = $this->categoryService->getById($id);
        if (!$category)
            abort(404);
        return view("categories.show", ["category" => $category]);
    }

    public function edit(string $id)
    {
        $category = $this->categoryService->getById($id);
        if (!$category)
            abort(404);
        $this->authorize("update", $category);
        return view("categories.edit", ["category" => $category]);
    }

    public function update(UpdateCategoryRequest $request, string $id)
    {
        $category = $this->categoryService->getById($id);
        if (!$category)
            abort(404);
        $this->authorize("update", $category);
        $validated = $request->validated();
        $this->categoryService->update($category, $validated);
        return redirect()->route("categories.index")->with("success", "Category updated successfully");
    }

    public function destroy(string $id)
    {
        $category = $this->categoryService->getById($id);
        if (!$category)
            abort(404);
        $this->authorize("delete", $category);
        $this->categoryService->detach_with_products($category);
        $this->categoryService->delete($category);
        return redirect()->back()->with("success", "Category deleted successfully");
    }
}
