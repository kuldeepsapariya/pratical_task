@extends('layouts.admin')

@section('page-title', 'View Product')

@section('content')
<div class="p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header with Actions -->
        <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $product->name }}</h1>
            <p class="text-gray-600 mt-1">Product Details</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.products.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Products
                </a>
            <a href="{{ route('admin.products.edit', $product) }}" class="px-4 py-2 bg-blue-600 !text-white rounded-lg hover:bg-blue-700 transition">
                Edit Product
            </a>
        </div>
    </div>

    <!-- Product Information Card -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Product Image -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Product Image</h3>
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-auto rounded-lg shadow-md">
                @else
                    <div class="w-full h-64 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-center text-gray-500 mt-2">No image available</p>
                @endif
            </div>
        </div>

        <!-- Product Details -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Product Information</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Product Name</label>
                        <p class="text-lg text-gray-900">{{ $product->name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Description</label>
                        <p class="text-gray-900">{{ $product->description ?? 'No description provided' }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 pt-4 border-t">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Created At</label>
                            <p class="text-gray-900">{{ $product->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Last Updated</label>
                            <p class="text-gray-900">{{ $product->updated_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Variants Section -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-xl font-semibold text-gray-900">Product Variants</h3>
                <p class="text-sm text-gray-600 mt-1">{{ $product->variants->count() }} variant(s) available</p>
            </div>
            <a href="{{ route('admin.products.variants.create', $product) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 !text-white rounded-lg hover:bg-blue-700 transition">
                 <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add New Variant
            </a>
        </div>

        @if($product->variants->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($product->variants as $variant)
                <div class="border-2 border-gray-200 rounded-xl p-5 hover:shadow-lg hover:border-blue-300 transition">
                    <!-- Variant Image -->
                    @if($variant->image)
                        <div class="w-full h-48 bg-gray-50 rounded-lg flex items-center justify-center mb-4 p-4">
                            <img src="{{ asset('storage/' . $variant->image) }}" alt="{{ $variant->name }}" class="max-w-full max-h-full object-contain">
                        </div>
                    @else
                        <div class="w-full h-48 bg-gray-100 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif

                    <!-- Variant Info -->
                    <h4 class="text-lg font-bold text-gray-900 mb-3 text-center">{{ $variant->name }}</h4>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg px-3 py-2 mb-4 text-center">
                        <p class="text-xs text-gray-600 mb-1">Base Price</p>
                        <p class="text-xl font-bold text-blue-600">&#8377;{{ number_format($variant->base_price, 2) }}<span class="text-sm font-normal text-gray-600">/pc</span></p>
                    </div>

                    <!-- Quantity Prices -->
                    @if($variant->quantity_prices && count($variant->quantity_prices) > 0)
                        <div class="mb-4">
                            <p class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-1 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                Quantity Pricing
                            </p>
                            <div class="space-y-2">
                                @foreach($variant->quantity_prices as $qty => $pricePerPiece)
                                    @php
                                        $totalPrice = $qty * $pricePerPiece;
                                    @endphp
                                    <div class="bg-gradient-to-r from-gray-50 to-white border border-gray-200 px-3 py-2.5 rounded-lg">
                                        <div class="flex justify-between items-center mb-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">{{ $qty }} pcs</span>
                                            <span class="text-xs text-gray-600">&#8377;{{ number_format($pricePerPiece, 2) }}/pc</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-xs text-gray-500">{{ $qty }} × &#8377;{{ number_format($pricePerPiece, 2) }} =</span>
                                            <span class="text-sm font-bold text-blue-600">&#8377;{{ number_format($totalPrice, 2) }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="flex gap-2 pt-3 border-t border-gray-200">
                        <a href="{{ route('admin.products.variants.edit', [$product, $variant]) }}" class="flex-1 text-center px-3 py-2.5 bg-blue-600 !text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition shadow-sm">
                            Edit
                        </a>
                        <form action="{{ route('admin.products.variants.destroy', [$product, $variant]) }}" method="POST" class="flex-1" onsubmit="return confirmDelete(this, 'Delete Variant?', 'This will permanently delete this variant.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full px-3 py-2.5 bg-red-600 !text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition shadow-sm">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-gray-50 rounded-lg">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Variants Yet</h3>
                <p class="text-gray-600 mb-4">Get started by creating your first variant for this product.</p>
                <a href="{{ route('admin.products.variants.create', $product) }}" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Create First Variant
                </a>
            </div>
        @endif
    </div>
    </div>
</div>
@endsection
