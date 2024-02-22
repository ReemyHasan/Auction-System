<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

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
        try {
            $this->authorize("create", Category::class);
            $validated = $request->validated();
            $validated['created_by'] = Auth::user()->id;
            $category = $this->categoryService->create($validated);
            return redirect()->route("categories.index")->with("success", "New category added successfully");
        } catch (ValidationException $e) {
            return redirect()->back();
        }
    }

    public function show(string $id)
    {
        $category = $this->categoryService->getById($id);
        if ($category)
            return view("categories.show", ["category" => $category]);
        else {
            abort(404);
        }
    }

    public function edit(string $id)
    {
        $category = $this->categoryService->getById($id);
        if ($category) {
            $this->authorize("update", $category);
            return view("categories.edit", ["category" => $category]);
        } else {
            abort(404);
        }
    }

    public function update(CategoryRequest $request, string $id)
    {
        try {
            $category = $this->categoryService->getById($id);
            if ($category) {
                $this->authorize("update", $category);
                $validated = $request->validated();
                $this->categoryService->update($category, $validated);
                return redirect()->route("categories.index")->with("success", "Category updated successfully");
            } else {
                abort(404);
            }
        } catch (ValidationException $e) {
            return redirect()->back();
        }
    }

    public function destroy(string $id)
    {
        $category = $this->categoryService->getById($id);
        if ($category) {
            $this->authorize("delete", $category);
            $this->categoryService->delete($category);
            return redirect()->back()->with("success", "Category deleted successfully");
        } else {
            abort(404);
        }
    }
}
