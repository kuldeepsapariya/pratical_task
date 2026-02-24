@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Breadcrumb -->
    <div class="bg-white border-b shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex items-center space-x-2 text-sm">
                <a href="/" class="text-gray-500 hover:text-blue-600 transition font-medium">Home</a>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <a href="/" class="text-gray-500 hover:text-blue-600 transition font-medium">Products</a>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-gray-900 font-semibold">{{ $product->name }}</span>
            </nav>
        </div>
    </div>

    <!-- Product Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
            <!-- Left: Image Gallery -->
            <div class="space-y-4">
                <!-- Main Product Image -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">
                    <div class="aspect-square p-8 bg-gradient-to-br from-gray-50 to-white flex items-center justify-center">
                        <img id="mainProductImage" 
                             src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/600x600?text=Product+Image' }}" 
                             alt="{{ $product->name }}"
                             class="max-w-full max-h-full object-contain">
                    </div>
                </div>

                <!-- Selected Variant Image - Only shows when variant is selected -->
                <div id="selectedVariantImageContainer" class="hidden">
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border-2 border-blue-500">
                        <div class="h-64 p-8 bg-gradient-to-br from-blue-50 to-white flex items-center justify-center">
                            <img id="selectedVariantImage" 
                                 src="" 
                                 alt="Selected Variant"
                                 class="max-w-full max-h-full object-contain">
                        </div>
                        <div class="p-4 bg-blue-50 border-t-2 border-blue-500">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Selected Type:</p>
                                    <h4 id="selectedVariantImageTitle" class="font-bold text-gray-900"></h4>
                                </div>
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Product Details -->
            <div class="space-y-6">
                <!-- Product Title & Rating -->
                <div>
                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-3">{{ $product->name }}</h1>
                    <div class="flex items-center gap-4 mb-4">
                        <div class="flex items-center bg-green-50 px-3 py-1 rounded-full">
                            <svg class="w-5 h-5 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span class="text-sm font-semibold text-gray-700">4.8 (256 reviews)</span>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <span class="w-2 h-2 bg-green-600 rounded-full mr-2 animate-pulse"></span>
                            In Stock
                        </span>
                    </div>
                    <p class="text-lg text-gray-600 leading-relaxed mb-6">{{ $product->description }}</p>
                </div>

                @if($product->variants->count() > 0)
                <form id="productForm" class="space-y-6">
                    @csrf
                    
                    <!-- Variant Selection -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <label class="text-base font-bold text-gray-900">
                                Select Type
                            </label>
                            <span class="text-sm text-gray-500">{{ $product->variants->count() }} options available</span>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach($product->variants as $index => $variant)
                            <label class="relative cursor-pointer group">
                                <input type="radio" 
                                       name="variant_id" 
                                       value="{{ $variant->id }}"
                                       data-image="{{ $variant->image ? asset('storage/' . $variant->image) : ($product->image ? asset('storage/' . $product->image) : '') }}"
                                       data-prices='@json($variant->quantity_prices)'
                                       data-base-price="{{ $variant->base_price }}"
                                       data-name="{{ $variant->name }}"
                                       {{ $index === 0 ? 'checked' : '' }}
                                       class="peer sr-only variant-radio">
                                <div class="h-full px-4 py-4 border-2 border-gray-200 rounded-xl text-center transition-all peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:shadow-lg hover:border-gray-300 group-hover:shadow-md">
                                    <span class="block text-sm font-semibold text-gray-900 mb-1">{{ $variant->name }}</span>
                                    @php
                                        $minQty = array_keys($variant->quantity_prices)[0] ?? 1;
                                        $minPrice = $variant->quantity_prices[$minQty] ?? $variant->base_price;
                                        $totalPrice = $minQty * $minPrice;
                                    @endphp
                                    <span class="block text-xs text-gray-500">From &#8377;{{ number_format($totalPrice, 2) }}</span>
                                </div>
                                <div class="absolute top-2 right-2 w-5 h-5 bg-blue-600 rounded-full items-center justify-center hidden peer-checked:flex">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Quantity Selection Table -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <label class="text-base font-bold text-gray-900">
                                Select Quantity
                            </label>
                            <span class="text-sm text-green-600 font-medium">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Bulk discounts available
                            </span>
                        </div>
                        
                        <!-- Quantity Table -->
                        <div class="overflow-x-auto">
                            <table class="w-full" id="quantityTable">
                                <thead>
                                    <tr class="bg-gray-50 border-b-2 border-gray-200">
                                        <th class="px-4 py-3 text-left text-sm font-bold text-gray-700">Quantity</th>
                                        <th class="px-4 py-3 text-right text-sm font-bold text-gray-700">Price per Piece</th>
                                        <th class="px-4 py-3 text-right text-sm font-bold text-gray-700">Total Price</th>
                                    </tr>
                                </thead>
                                <tbody id="quantityTableBody">
                                    <!-- Rows will be populated by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <!-- Action Buttons -->
                    <div class="flex gap-3">
                        <button type="button" 
                                id="addToCartBtn"
                                class="flex-1 bg-gradient-to-r from-blue-500 to-blue-600 !text-white px-8 py-4 rounded-xl font-bold text-lg hover:from-blur-700 hover:to-blue-800 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center gap-3 group">
                            <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Add to Cart
                        </button>

                        <a href="{{ route('cart.index') }}" 
                        class="flex-1 px-8 py-4 bg-white border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:border-blue-600 hover:text-blue-600 hover:bg-blue-50 transition-all flex items-center justify-center gap-2 shadow-sm hover:shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            View Cart
                        </a>
                    </div>

                </form>
                @else
                <div class="bg-yellow-50 border-l-4 border-yellow-400 rounded-xl p-6 shadow-sm">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <p class="text-yellow-800 font-semibold">No variants available for this product yet.</p>
                    </div>
                </div>
                @endif
            </div>
        </div>


        <!-- Product Details Tabs -->
        <div class="mt-12 bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px">
                    <button onclick="switchTab('description')" id="tab-description" class="tab-btn active px-8 py-4 text-sm font-semibold border-b-2 border-blue-600 text-blue-600">
                        Description
                    </button>
                    <button onclick="switchTab('specifications')" id="tab-specifications" class="tab-btn px-8 py-4 text-sm font-semibold border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Specifications
                    </button>
                    <button onclick="switchTab('reviews')" id="tab-reviews" class="tab-btn px-8 py-4 text-sm font-semibold border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Reviews (256)
                    </button>
                </nav>
            </div>

            <div class="p-8">
                <!-- Description Tab -->
                <div id="content-description" class="tab-content">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Product Description</h3>
                    <p class="text-gray-600 leading-relaxed mb-6">{{ $product->description }}</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
                        <div>
                            <h4 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Key Features
                            </h4>
                            <ul class="space-y-3">
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-gray-700">High-quality professional printing</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-gray-700">Multiple size and finish options</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-gray-700">Bulk quantity discounts available</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-gray-700">Fast turnaround time (2-5 business days)</span>
                                </li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                What's Included
                            </h4>
                            <ul class="space-y-3">
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-gray-700">Professional design review</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-gray-700">Quality assurance check</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-gray-700">Secure packaging</span>
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-gray-700">Free shipping on orders above &#8377;1000</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Specifications Tab -->
                <div id="content-specifications" class="tab-content hidden">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Technical Specifications</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-3 border-b border-gray-200">
                                <span class="font-semibold text-gray-700">Available Variants:</span>
                                <span class="text-gray-900">{{ $product->variants->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-200">
                                <span class="font-semibold text-gray-700">Category:</span>
                                <span class="text-gray-900">Printing Services</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-200">
                                <span class="font-semibold text-gray-700">Minimum Order:</span>
                                <span class="text-gray-900">Check variant options</span>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-3 border-b border-gray-200">
                                <span class="font-semibold text-gray-700">Delivery Time:</span>
                                <span class="text-gray-900">2-5 business days</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-200">
                                <span class="font-semibold text-gray-700">Printing Method:</span>
                                <span class="text-gray-900">Digital/Offset</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-200">
                                <span class="font-semibold text-gray-700">Customization:</span>
                                <span class="text-gray-900">Available</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reviews Tab -->
                <div id="content-reviews" class="tab-content hidden">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Customer Reviews</h3>
                    <div class="flex items-center gap-8 mb-8 p-6 bg-gray-50 rounded-xl">
                        <div class="text-center">
                            <div class="text-5xl font-bold text-gray-900 mb-2">4.8</div>
                            <div class="flex items-center justify-center mb-2">
                                @for($i = 0; $i < 5; $i++)
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                @endfor
                            </div>
                            <div class="text-sm text-gray-600">Based on 256 reviews</div>
                        </div>
                        <div class="flex-1">
                            <div class="space-y-2">
                                @foreach([5 => 180, 4 => 50, 3 => 15, 2 => 8, 1 => 3] as $star => $count)
                                <div class="flex items-center gap-3">
                                    <span class="text-sm font-medium text-gray-700 w-12">{{ $star }} star</span>
                                    <div class="flex-1 h-3 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full !bg-yellow-400" style="width: {{ ($count / 256) * 100 }}%"></div>
                                    </div>
                                    <span class="text-sm text-gray-600 w-12 text-right">{{ $count }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <p class="text-center text-gray-500 py-8">Customer reviews will be displayed here.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Popular Products Section -->
    <section class="py-16 bg-white border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Popular Products</h2>
                <p class="text-xl text-gray-600">Our most loved printing solutions</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Dummy Product 1 -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition transform hover:-translate-y-1 border border-gray-200">
                    <div class="relative">
                        <div class="w-full h-48 bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center">
                            <svg class="w-20 h-20 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                        <span class="absolute top-3 right-3 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">Popular</span>
                    </div>
                    <div class="p-5">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Premium Business Cards</h3>
                        <p class="text-gray-600 text-sm mb-3 line-clamp-2">High-quality business cards with premium finish and vibrant colors</p>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs text-gray-500">Starting from</span>
                            <span class="text-lg font-bold text-blue-600">&#8377;299.00</span>
                        </div>
                        <div class="flex items-center gap-2 mb-3">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span class="text-sm font-semibold text-gray-700 ml-1">4.9</span>
                            </div>
                            <span class="text-xs text-gray-500">(1,234 reviews)</span>
                        </div>
                    </div>
                </div>

                <!-- Dummy Product 2 -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition transform hover:-translate-y-1 border border-gray-200">
                    <div class="relative">
                        <div class="w-full h-48 bg-gradient-to-br from-green-50 to-green-100 flex items-center justify-center">
                            <svg class="w-20 h-20 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <span class="absolute top-3 right-3 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full">Trending</span>
                    </div>
                    <div class="p-5">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Marketing Flyers</h3>
                        <p class="text-gray-600 text-sm mb-3 line-clamp-2">Eye-catching flyers perfect for promotions and events</p>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs text-gray-500">Starting from</span>
                            <span class="text-lg font-bold text-blue-600">&#8377;199.00</span>
                        </div>
                        <div class="flex items-center gap-2 mb-3">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span class="text-sm font-semibold text-gray-700 ml-1">4.7</span>
                            </div>
                            <span class="text-xs text-gray-500">(856 reviews)</span>
                        </div>
                    </div>
                </div>

                <!-- Dummy Product 3 -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition transform hover:-translate-y-1 border border-gray-200">
                    <div class="relative">
                        <div class="w-full h-48 bg-gradient-to-br from-purple-50 to-purple-100 flex items-center justify-center">
                            <svg class="w-20 h-20 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                            </svg>
                        </div>
                        <span class="absolute top-3 right-3 bg-purple-500 text-white text-xs font-bold px-2 py-1 rounded-full">Best Seller</span>
                    </div>
                    <div class="p-5">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Custom Brochures</h3>
                        <p class="text-gray-600 text-sm mb-3 line-clamp-2">Professional brochures to showcase your business perfectly</p>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs text-gray-500">Starting from</span>
                            <span class="text-lg font-bold text-blue-600">&#8377;399.00</span>
                        </div>
                        <div class="flex items-center gap-2 mb-3">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span class="text-sm font-semibold text-gray-700 ml-1">4.8</span>
                            </div>
                            <span class="text-xs text-gray-500">(642 reviews)</span>
                        </div>
                    </div>
                </div>

                <!-- Dummy Product 4 -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition transform hover:-translate-y-1 border border-gray-200">
                    <div class="relative">
                        <div class="w-full h-48 bg-gradient-to-br from-orange-50 to-orange-100 flex items-center justify-center">
                            <svg class="w-20 h-20 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                        <span class="absolute top-3 right-3 bg-orange-500 text-white text-xs font-bold px-2 py-1 rounded-full">Hot Deal</span>
                    </div>
                    <div class="p-5">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Letterhead Printing</h3>
                        <p class="text-gray-600 text-sm mb-3 line-clamp-2">Professional letterheads for your business correspondence</p>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs text-gray-500">Starting from</span>
                            <span class="text-lg font-bold text-blue-600">&#8377;249.00</span>
                        </div>
                        <div class="flex items-center gap-2 mb-3">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span class="text-sm font-semibold text-gray-700 ml-1">4.6</span>
                            </div>
                            <span class="text-xs text-gray-500">(423 reviews)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">PrintPro</h3>
                    <p class="text-gray-400">Your trusted partner for professional printing services.</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="#products" class="text-gray-400 hover:text-white">Products</a></li>
                        <li><a href="{{ route('cart.index') }}" class="text-gray-400 hover:text-white">Cart</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Contact</h4>
                    <p class="text-gray-400">Email: info@printpro.com</p>
                    <p class="text-gray-400">Phone: (555) 123-4567</p>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} PrintPro. All rights reserved.</p>
            </div>
        </div>
    </footer>

</div>


<script>
// Tab switching
function switchTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active', 'border-blue-600', 'text-blue-600');
        btn.classList.add('border-transparent', 'text-gray-500');
    });
    
    document.getElementById('content-' + tabName).classList.remove('hidden');
    
    const activeTab = document.getElementById('tab-' + tabName);
    activeTab.classList.add('active', 'border-blue-600', 'text-blue-600');
    activeTab.classList.remove('border-transparent', 'text-gray-500');
}

// Update product UI
function updateProductUI() {
    const variantRadios = document.querySelectorAll('.variant-radio');
    const quantityTableBody = document.getElementById('quantityTableBody');
    const selectedVariantImageContainer = document.getElementById('selectedVariantImageContainer');
    const selectedVariantImage = document.getElementById('selectedVariantImage');
    const selectedVariantImageTitle = document.getElementById('selectedVariantImageTitle');
    
    let selectedVariant = null;
    variantRadios.forEach(radio => {
        if (radio.checked) selectedVariant = radio;
    });
    
    if (!selectedVariant) return;
    
    const prices = JSON.parse(selectedVariant.getAttribute('data-prices'));
    const image = selectedVariant.getAttribute('data-image');
    const basePrice = selectedVariant.getAttribute('data-base-price');
    const variantName = selectedVariant.getAttribute('data-name');
    
    // Show/hide and update selected variant image
    if (image) {
        selectedVariantImage.src = image;
        selectedVariantImageTitle.textContent = variantName;
        selectedVariantImageContainer.classList.remove('hidden');
        
        selectedVariantImageContainer.style.opacity = '0';
        setTimeout(() => {
            selectedVariantImageContainer.style.opacity = '1';
            selectedVariantImageContainer.style.transition = 'opacity 0.3s ease-in-out';
        }, 50);
    } else {
        selectedVariantImageContainer.classList.add('hidden');
    }
    
    // Populate quantity table
    quantityTableBody.innerHTML = '';
    let firstQty = null;
    let firstPrice = null;
    
    for (const qty in prices) {
        const pricePerPiece = parseFloat(prices[qty]);
        const totalPrice = parseInt(qty) * pricePerPiece;
        
        if (!firstQty) {
            firstQty = qty;
            firstPrice = totalPrice;
        }
        
        const row = document.createElement('tr');
        row.className = 'quantity-row border-b border-gray-100 hover:bg-blue-50 cursor-pointer transition';
        row.setAttribute('data-quantity', qty);
        row.setAttribute('data-price', totalPrice);
        row.onclick = function() { selectQuantity(qty, totalPrice); };
        
        row.innerHTML = `
            <td class="px-4 py-4">
                <div class="flex items-center">
                    <div class="w-5 h-5 rounded-full border-2 border-gray-300 mr-3 flex items-center justify-center quantity-radio">
                        <div class="w-2.5 h-2.5 rounded-full bg-blue-600 hidden"></div>
                    </div>
                    <span class="font-semibold text-gray-900">${qty} pieces</span>
                </div>
            </td>
            <td class="px-4 py-4 text-right text-gray-700 font-medium">&#8377;${pricePerPiece.toFixed(2)}</td>
            <td class="px-4 py-4 text-right">
                <div class="text-sm text-gray-600 mb-1">${qty} × &#8377;${pricePerPiece.toFixed(2)}</div>
                <span class="text-lg font-bold text-blue-600">&#8377;${totalPrice.toFixed(2)}</span>
            </td>
        `;
        
        quantityTableBody.appendChild(row);
    }
    
    // Auto-select first quantity
    if (firstQty) {
        selectQuantity(firstQty, firstPrice);
    }
}

// Select quantity from table
let selectedQuantityValue = null;

function selectQuantity(qty, price) {
    selectedQuantityValue = qty;
    
    // Update row styling
    document.querySelectorAll('.quantity-row').forEach(row => {
        row.classList.remove('bg-blue-100', 'border-blue-500');
        row.classList.add('border-gray-100');
        
        // Hide all radio dots
        const radioDot = row.querySelector('.quantity-radio div');
        radioDot.classList.add('hidden');
        
        // Reset border color
        const radioCircle = row.querySelector('.quantity-radio');
        radioCircle.classList.remove('border-blue-600');
        radioCircle.classList.add('border-gray-300');
    });
    
    // Highlight selected row
    const selectedRow = document.querySelector(`.quantity-row[data-quantity="${qty}"]`);
    if (selectedRow) {
        selectedRow.classList.add('bg-blue-100', 'border-blue-500');
        selectedRow.classList.remove('border-gray-100');
        
        // Show radio dot
        const radioDot = selectedRow.querySelector('.quantity-radio div');
        radioDot.classList.remove('hidden');
        
        // Update border color
        const radioCircle = selectedRow.querySelector('.quantity-radio');
        radioCircle.classList.remove('border-gray-300');
        radioCircle.classList.add('border-blue-600');
    }
}

// Variant change event
document.querySelectorAll('.variant-radio').forEach(radio => {
    radio.addEventListener('change', updateProductUI);
});

// Add to cart
document.getElementById('addToCartBtn').addEventListener('click', function() {
    const variantRadios = document.querySelectorAll('.variant-radio');
    let variantId = null;
    variantRadios.forEach(radio => {
        if (radio.checked) variantId = radio.value;
    });
    
    if (!variantId || !selectedQuantityValue) {
        Swal.fire({
            icon: 'warning',
            title: 'Missing Selection',
            text: 'Please select a variant and quantity',
            confirmButtonColor: '#3b82f6'
        });
        return;
    }
    
    const btn = this;
    const originalHTML = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<svg class="animate-spin w-6 h-6 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    
    fetch("{{ route('cart.add') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({variant_id: variantId, quantity: selectedQuantityValue})
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Added to Cart!',
                text: 'Product has been added to your cart successfully.',
                confirmButtonColor: '#3b82f6',
                showCancelButton: true,
                confirmButtonText: 'View Cart',
                cancelButtonText: 'Continue Shopping'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('cart.index') }}";
                }
            });
            
            btn.innerHTML = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Added!';
            btn.classList.remove('from-green-600', 'to-green-700');
            btn.classList.add('from-green-700', 'to-green-800');
            
            setTimeout(() => {
                btn.innerHTML = originalHTML;
                btn.classList.remove('from-green-700', 'to-green-800');
                btn.classList.add('from-green-600', 'to-green-700');
                btn.disabled = false;
            }, 2000);
        } else {
            btn.innerHTML = originalHTML;
            btn.disabled = false;
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Could not add to cart. Please try again.',
                confirmButtonColor: '#ef4444'
            });
        }
    })
    .catch(error => {
        btn.innerHTML = originalHTML;
        btn.disabled = false;
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Something went wrong. Please try again.',
            confirmButtonColor: '#ef4444'
        });
    });
});

// Initialize on page load
window.addEventListener('DOMContentLoaded', function() {
    updateProductUI();
});
</script>
@endsection
