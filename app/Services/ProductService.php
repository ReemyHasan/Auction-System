<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function getAll()
    {
        $products = Product::getRecords();
        return $products;
    }
    public function create($data, $categories)
    {
        return DB::transaction(function () use ($data, $categories) {
            $product = Product::create($data);
            if ($categories)
                $this->attach_with_categories($product, $categories);
            return $product;
        });
    }
    public function getById($id)
    {
        return Product::getRecord($id);
    }
    public function update($product, $validated, $categories)
    {
        if ($product)
            return DB::transaction(function () use ($product, $validated, $categories) {
                $product->update($validated);
                if ($categories)
                    $this->update_attachments($product, $categories);
                return true;

            });
    }
    public function delete($product)
    {
        if ($product)
            return DB::transaction(function () use ($product) {
                $this->detach_with_categories($product);
                return $product->delete();
            });
    }
    public function handleUploadedImage($image, $product)
    {
        if (!is_null($image)) {
            $image = $image->store('products', 'public');
            if (!is_null($product)) {
                Storage::disk('public')->delete($product->image ?? '');
            }
            return explode('/', $image, 2)[1];
        }
    }
    public function getMyProduct()
    {
        $products = Product::getRecords()->where('vendor_id', '=', Auth::user()->id)->get();
        return $products;
    }

    public function add_interaction($product, $interaction)
    {
        $userInteraction = $product->interactions()->where("user_id", $interaction['user_id'])->first();
        if ($userInteraction == null) {
            $product->interactions()->create($interaction);
        } else {
            $userInteraction->update($interaction);
        }
    }
    public function attach_with_categories($product, $categories)
    {
        foreach ($categories as $category) {
            $cat = Category::getRecord($category);
            $product->categories()->attach($cat);
        }
    }
    public function detach_with_categories(Product $product)
    {
        $categories = $product->categories()->get();
        if ($categories)
            $product->categories()->detach($categories);
    }
    public function update_attachments($product, $updatedCategories)
    {
        return $product->categories()->sync($updatedCategories);
    }
}
