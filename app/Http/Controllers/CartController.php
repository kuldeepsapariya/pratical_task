<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Variant;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = session('cart', []);
        return view('cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'variant_id' => 'required|exists:variants,id',
            'quantity' => 'required|integer|min:1',
        ]);
        $variant = Variant::findOrFail($data['variant_id']);
        $cart = session('cart', []);
        $key = $variant->id . '-' . $data['quantity'];
        
        // Get price per piece for the selected quantity
        $pricePerPiece = $variant->quantity_prices[$data['quantity']] ?? $variant->base_price;
        
        // Calculate total price: quantity × price per piece
        $totalPrice = $data['quantity'] * $pricePerPiece;
        
        $cart[$key] = [
            'variant_id' => $variant->id,
            'variant_name' => $variant->name,
            'product_name' => $variant->product->name,
            'quantity' => $data['quantity'],
            'price_per_piece' => $pricePerPiece,
            'total_price' => $totalPrice,
            'image' => $variant->image,
        ];
        session(['cart' => $cart]);
        return response()->json(['success' => true, 'cart_count' => count($cart)]);
    }

    public function remove(Request $request)
    {
        $key = $request->input('key');
        $cart = session('cart', []);
        unset($cart[$key]);
        session(['cart' => $cart]);
        return redirect()->route('cart.index');
    }
}
