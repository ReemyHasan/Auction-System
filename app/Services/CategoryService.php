<?php

namespace App\Services;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
class CategoryService
{
    public function getAll(){
        $categories = Category::getRecords();
        return $categories;
    }
    public function create($data){
        return Category::create($data);
    }
    public function getById($id){
        return Category::getRecord($id);
    }
    public function update($category, $validated){
        return $category->update($validated);
    }
    public function delete($category){
        if ($category) {
            return DB::transaction(function () use ($category) {
                $this->detach_with_products($category);
                return $category->delete();
            });
        }

    }
    public function detach_with_products(Category $category){
        $products = $category->products()->get();
        if($products)
            $category->products()->detach($products);
    }
}
