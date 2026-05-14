<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::withSum('transactionItems as units_sold', 'qty')
            ->withSum('transactionItems as revenue', 'amount')
            ->get();

        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'barcode' => 'required|unique:products',
            'name' => 'required',
            'price' => 'required|numeric',
        ]);
        Product::create($request->all());

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'barcode' => 'required|unique:products,barcode,'.$product->id,
            'name' => 'required',
            'price' => 'required|numeric',
        ]);
        $product->update($request->all());

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    public function restock(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $product->stock += $request->quantity;
        $product->save();

        return response()->json([
            'success' => true,
            'message' => "Stock updated: {$product->name} now has {$product->stock} units.",
            'new_stock' => $product->stock,
        ]);
    }
}
