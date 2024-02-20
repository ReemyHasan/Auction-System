<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function getAll()
    {
        $products = Product::getRecords();
        return $products;
    }
    public function create($data)
    {
        return Product::create($data);
    }
    public function getById($id)
    {
        return Product::getRecord($id);
    }
    public function update($product, $validated)
    {
        return $product->update($validated);
    }
    public function delete($product)
    {
        return $product->delete();
    }
    public function handleUploadedImage($image, $product)
    {
        if (!is_null($image)) {
            $image = $image->store('products', 'public');
            if(!is_null($product)) {
            Storage::disk('public')->delete($product->image ?? '');
            }
            return explode('/', $image,2)[1];
        }
    }
    public function getMyProduct(){
        $products = Product::getRecords()->where('vendor_id','=',Auth::user()->id)->get();
        return $products;
    }

    public function add_interaction($product, $interaction){
        $userInteraction = $product->interactions()->where("user_id", $interaction['user_id'])->first();
        if($userInteraction == null ){
        $product->interactions()->create($interaction);
        }
        else {
            $userInteraction->update($interaction);
        }
    }
    public function attach_with_categories($product, $categories){
        foreach ($categories as $category) {
            $cat = Category::getRecord($category);
            $product->categories()->attach($cat);
        }
    }
    public function detach_with_categories(Product $product){
        $categories = $product->categories()->get();
        foreach ($categories as $category) {
            $product->categories()->detach($category);
        }
    }
    public function update_attachments($product, $updatedCategories){
        return $product->categories()->sync($updatedCategories);
    }
}
