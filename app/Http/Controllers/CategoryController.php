<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use App\Traits\CommonControllerFunctions;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    use CommonControllerFunctions;
    private $categoryService;
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $categories = $this->categoryService->getAll()->filter()->paginate(10);
        return $this->commonIndex("categories.index", compact("categories"));
    }

    public function create()
    {
        return $this->commonCreate(Category::class, "categories.create");
    }

    public function store(CategoryRequest $request)
    {
        $validated = $request->validated();
        $validated['created_by'] = Auth::user()->id;
        return $this->commonStore($validated, Category::class, $this->categoryService, "categories.index", "category");
    }

    public function show(string $id)
    {
        return $this->commonShow($id, $this->categoryService, "categories.show", "category");
    }

    public function edit(string $id)
    {
        $category = $this->categoryService->getById($id);
        return $this->commonEdit($category, "categories.edit", compact('category'));
    }

    public function update(UpdateCategoryRequest $request, string $id)
    {
        $category = $this->categoryService->getById($id);
        $validated = $request->validated();
        return $this->commonUpdate($validated, $category, $this->categoryService, "categories.index", "Category");
    }

    public function destroy(string $id)
    {
        return $this->commonDestroy($id, $this->categoryService, "Category");
    }
}
