<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->get();
        $categories = Category::all();

        return view('admin.products.index', compact('products','categories'));
    }

    public function store(Request $request)
    {
        $product = Product::create($request->all());

        $product->load('category'); // penting!

        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'stock' => $product->stock,
            'price' => $product->price,
            'price_formatted' => number_format($product->price),
            'category_name' => $product->category->name
        ]);
    }


    public function edit($id)
    {
        return response()->json(Product::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->all());

        $product->load('category');

        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'stock' => $product->stock,
            'price' => $product->price,
            'price_formatted' => number_format($product->price),
            'category_name' => $product->category->name
        ]);
    }


    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}
