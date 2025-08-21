<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Product::with('images')->latest()->paginate(20);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'description'       => 'nullable|string',
            'price'             => 'required|numeric|min:0',
            'compare_at_price'  => 'nullable|numeric|min:0',
            'cost'              => 'nullable|numeric|min:0',
            'charge_tax'        => 'boolean',
            'sku'               => 'nullable|string|max:100',
            'barcode'           => 'nullable|string|max:100',
            'quantity'          => 'nullable|integer|min:0',
            'type'              => 'nullable|string|max:100',
            'vendor'            => 'nullable|string|max:100',
            'collections'       => 'nullable|string|max:255',
            'tags'              => 'nullable|string|max:255',
            'status'            => 'required|string|in:draft,active',
            'images.*'          => 'nullable|file|image|max:2048',
        ]);

        $product = Product::create($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $idx => $file) {
                $path = $file->store('products', 'public');
                $product->images()->create([
                    'path'      => $path,
                    'position'  => $idx,
                    'is_main'   => $idx === 0,
                ]);
            }
        }

        return response()->json($product->load('images'), 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return $product->load('images', 'stores');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
