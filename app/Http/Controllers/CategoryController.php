<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::getRecords();
        return view("categories.index",["categories"=> $categories->paginate(10)]);
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
        Category::create($validated);
        return redirect()->route("categories.index")->with("success","New category added successfully");
    }

    public function show(string $id)
    {
        $category = Category::getRecord($id);
        return view("categories.show",["category"=> $category]);
    }

    public function edit(string $id)
    {
        $category = Category::getRecord($id);
        $this->authorize("update", $category);
        return view("categories.edit",["category"=> $category]);
    }

    public function update(CategoryRequest $request, string $id)
    {
        $category = Category::getRecord($id);
        $this->authorize("update", $category);
        $validated = $request->validated();
        $category->update($validated);
        return redirect()->route("categories.index")->with("success","Category updated successfully");
    }

    public function destroy(string $id)
    {
        $category = Category::getRecord($id);
        $this->authorize("delete", $category);
        $category->delete();
        return redirect()->back()->with("success","Category deleted successfully");
    }
}
