<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VariantController extends Controller
{
    public function index($productId)
    {
        $product = \App\Models\Product::with('variants')->findOrFail($productId);
        return view('admin.products.variants.index', compact('product'));
    }

    public function create($productId)
    {
        $product = \App\Models\Product::findOrFail($productId);
        return view('admin.products.variants.create', compact('product'));
    }

    public function store(Request $request, $productId)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'base_price' => 'required|numeric|min:0',
            'image' => 'nullable|image',
            'quantities' => 'required|array',
            'quantities.*' => 'required|integer|min:1',
            'prices' => 'required|array',
            'prices.*' => 'required|numeric|min:0',
        ]);

        $quantityPrices = [];
        foreach ($data['quantities'] as $index => $qty) {
            $quantityPrices[$qty] = $data['prices'][$index];
        }

        $variantData = [
            'product_id' => $productId,
            'name' => $data['name'],
            'base_price' => $data['base_price'],
            'quantity_prices' => $quantityPrices,
        ];

        if ($request->hasFile('image')) {
            $variantData['image'] = $request->file('image')->store('products', 'public');
        }

        \App\Models\Variant::create($variantData);
        return redirect()->route('admin.products.variants.index', $productId)->with('success', 'Variant created!');
    }

    public function edit($productId, $id)
    {
        $product = \App\Models\Product::findOrFail($productId);
        $variant = \App\Models\Variant::findOrFail($id);
        return view('admin.products.variants.edit', compact('product', 'variant'));
    }

    public function update(Request $request, $productId, $id)
    {
        $variant = \App\Models\Variant::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'base_price' => 'required|numeric|min:0',
            'image' => 'nullable|image',
            'quantities' => 'required|array',
            'quantities.*' => 'required|integer|min:1',
            'prices' => 'required|array',
            'prices.*' => 'required|numeric|min:0',
        ]);

        $quantityPrices = [];
        foreach ($data['quantities'] as $index => $qty) {
            $quantityPrices[$qty] = $data['prices'][$index];
        }

        $variantData = [
            'name' => $data['name'],
            'base_price' => $data['base_price'],
            'quantity_prices' => $quantityPrices,
        ];

        if ($request->hasFile('image')) {
            $variantData['image'] = $request->file('image')->store('products', 'public');
        }

        $variant->update($variantData);
        return redirect()->route('admin.products.variants.index', $productId)->with('success', 'Variant updated!');
    }

    public function destroy($productId, $id)
    {
        $variant = \App\Models\Variant::findOrFail($id);
        $variant->delete();
        return redirect()->route('admin.products.variants.index', $productId)->with('success', 'Variant deleted!');
    }
}
