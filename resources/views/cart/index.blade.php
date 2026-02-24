@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Shopping Cart</h1>
            <p class="mt-1 text-sm text-gray-600">Review your items before checkout</p>
        </div>

        @if(count($cart) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2 space-y-4">
                    @foreach($cart as $key => $item)
                    <div class="bg-white rounded-lg shadow-sm p-6 flex items-center gap-6">
                        @if($item['image'])
                            <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['variant_name'] }}" class="w-24 h-24 object-contain rounded-lg">
                        @else
                            <div class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                        
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $item['product_name'] }}</h3>
                            <p class="text-sm text-gray-600">Variant: {{ $item['variant_name'] }}</p>
                            <p class="text-sm text-gray-600">Quantity: {{ $item['quantity'] }} pieces</p>
                            @if(isset($item['price_per_piece']))
                            <p class="text-sm text-gray-500 mt-1">&#8377;{{ number_format($item['price_per_piece'], 2) }}/piece</p>
                            @endif
                        </div>
                        
                        <div class="text-right">
                            @if(isset($item['total_price']))
                                <div class="text-sm text-gray-600 mb-1">{{ $item['quantity'] }} × &#8377;{{ number_format($item['price_per_piece'], 2) }}</div>
                                <p class="text-2xl font-bold text-blue-600">&#8377;{{ number_format($item['total_price'], 2) }}</p>
                            @else
                                <!-- Fallback for old cart items -->
                                <p class="text-2xl font-bold text-blue-600">&#8377;{{ number_format($item['price'] ?? 0, 2) }}</p>
                            @endif
                            <form action="{{ route('cart.remove') }}" method="POST" class="mt-2">
                                @csrf
                                <input type="hidden" name="key" value="{{ $key }}">
                                <button type="submit" class="text-sm text-red-600 hover:text-red-800 transition">Remove</button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm p-6 sticky top-24">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Order Summary</h2>
                        
                        @php
                            $subtotal = 0;
                            foreach($cart as $item) {
                                $subtotal += $item['total_price'] ?? $item['price'] ?? 0;
                            }
                        @endphp
                        
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-gray-600">
                                <span>Items ({{ count($cart) }})</span>
                                <span>&#8377;{{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Shipping</span>
                                <span>Calculated at checkout</span>
                            </div>
                            <div class="border-t pt-3 flex justify-between text-lg font-bold text-gray-900">
                                <span>Total</span>
                                <span>&#8377;{{ number_format($subtotal, 2) }}</span>
                            </div>
                        </div>
                        
                        <button class="w-full bg-blue-600 !text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition mb-3">
                            Proceed to Checkout
                        </button>
                        <a href="/" class="block w-full text-center px-6 py-3 border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50 transition">
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Your cart is empty</h3>
                <p class="text-gray-600 mb-6">Add some products to get started!</p>
                <a href="/" class="inline-block px-6 py-3 bg-blue-600 !text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                    Browse Products
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
