<?php

namespace App\Services;
use App\Models\Category;
class CategoryService
{
    public function create($data){
        return Category::create($data);
    }
    public function getRecord($id){
        return Category::getRecord($id);
    }
    public function update($category, $validated){
        return $category->update($validated);
    }
    public function delete($category){
        return $category->delete();
    }
}
