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
            return $image;
        }
    }
    public function getMyProduct(){
        $products = Product::getRecords()->where('vendor_id','=',Auth::user()->id)->get();
        return $products;
    }
}
