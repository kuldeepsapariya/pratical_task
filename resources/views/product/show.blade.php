@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Breadcrumb -->
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex items-center space-x-2 text-sm">
                <a href="/" class="text-gray-500 hover:text-blue-600 transition">Home</a>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <a href="/" class="text-gray-500 hover:text-blue-600 transition">Products</a>
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-gray-900 font-medium">{{ $product->name }}</span>
            </nav>
        </div>
    </div>

    <!-- Product Details -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
            <!-- Left Column - Images -->
            <div class="space-y-4">
                <!-- Main Image -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden sticky top-4">
                    <div class="aspect-square flex items-center justify-center p-8 bg-gray-50">
                        <img id="mainProductImage" 
                             src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/600x600?text=Product+Image' }}" 
                             alt="{{ $product->name }}"
                             class="max-w-full max-h-full object-contain rounded-lg transition-all duration-300">
                    </div>
                </div>

                <!-- Thumbnail Gallery (if variants have images) -->
                @if($product->variants->where('image', '!=', null)->count() > 0)
                <div class="grid grid-cols-4 gap-3">
                    @if($product->image)
                    <button onclick="changeMainImage('{{ asset('storage/' . $product->image) }}')" 
                            class="aspect-square bg-white rounded-lg overflow-hidden border-2 border-gray-200 hover:border-blue-500 transition">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="Main" class="w-full h-full object-contain">
                    </button>
                    @endif
                    @foreach($product->variants->where('image', '!=', null)->take(3) as $variant)
                    <button onclick="changeMainImage('{{ asset('storage/' . $variant->image) }}')" 
                            class="aspect-square bg-white rounded-lg overflow-hidden border-2 border-gray-200 hover:border-blue-500 transition">
                        <img src="{{ asset('storage/' . $variant->image) }}" alt="{{ $variant->name }}" class="w-full h-full object-contain">
                    </button>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Right Column - Product Info -->
            <div class="space-y-6">
                <!-- Product Title & Description -->
                <div>
                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-3">{{ $product->name }}</h1>
                    <div class="flex items-center gap-4 mb-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span class="ml-1 text-sm text-gray-600">4.8 (120 reviews)</span>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <span class="w-2 h-2 bg-green-600 rounded-full mr-1.5"></span>
                            In Stock
                        </span>
                    </div>
                    <p class="text-lg text-gray-600 leading-relaxed">{{ $product->description }}</p>
                </div>

                @if($product->variants->count() > 0)
                <form id="productForm" class="space-y-6">
                    @csrf
                    
                    <!-- Variant Selection -->
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                        <label class="block text-sm font-semibold text-gray-900 mb-4">
                            Select Type
                            <span class="text-gray-500 font-normal ml-2">({{ $product->variants->count() }} options)</span>
                        </label>
                        <div class="grid grid-cols-2 gap-3">
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
                                <div class="px-4 py-3 border-2 border-gray-200 rounded-lg text-center transition peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:shadow-md hover:border-gray-300 group-hover:shadow-sm">
                                    <span class="text-sm font-medium text-gray-900">{{ $variant->name }}</span>
                                    <p class="text-xs text-gray-500 mt-1">From ₹{{ number_format($variant->base_price, 2) }}</p>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Quantity Selection -->
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                        <label class="block text-sm font-semibold text-gray-900 mb-4">
                            Select Quantity
                            <span class="text-gray-500 font-normal ml-2">(Bulk discounts available)</span>
                        </label>
                        <select id="quantitySelect" 
                                name="quantity" 
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base font-medium">
                        </select>
                    </div>

                    <!-- Price Display -->
                    <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl p-6 shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-blue-100 mb-1">Total Price</p>
                                <p class="text-4xl font-bold text-white">$<span id="priceDisplay">0.00</span></p>
                                <p class="text-sm text-blue-100 mt-2">
                                    <span id="selectedVariantName"></span> • <span id="selectedQuantity"></span> pieces
                                </p>
                            </div>
                            <div class="text-right">
                                <svg class="w-16 h-16 text-blue-400 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3">
                        <button type="button" 
                                id="addToCartBtn"
                                class="flex-1 bg-green-600 text-white px-8 py-4 rounded-xl font-semibold text-lg hover:bg-green-700 transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Add to Cart
                        </button>
                        <a href="{{ route('cart.index') }}" 
                           class="px-6 py-4 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                        </a>
                    </div>

                    <!-- Features -->
                    <div class="grid grid-cols-3 gap-4 pt-6 border-t">
                        <div class="text-center">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <p class="text-xs font-medium text-gray-900">Premium Quality</p>
                        </div>
                        <div class="text-center">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <p class="text-xs font-medium text-gray-900">Fast Delivery</p>
                        </div>
                        <div class="text-center">
                            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <p class="text-xs font-medium text-gray-900">Secure Payment</p>
                        </div>
                    </div>
                </form>
                @else
                <div class="bg-yellow-50 border-l-4 border-yellow-400 rounded-lg p-6">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <p class="text-yellow-800 font-medium">No variants available for this product yet.</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Product Description Section -->
        <div class="mt-12 bg-white rounded-xl shadow-sm p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Product Details</h2>
            <div class="prose max-w-none">
                <p class="text-gray-600 leading-relaxed">{{ $product->description }}</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Features</h3>
                        <ul class="space-y-2">
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-gray-600">High-quality printing</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-gray-600">Multiple variants available</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-gray-600">Bulk pricing discounts</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-gray-600">Fast turnaround time</span>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Specifications</h3>
                        <ul class="space-y-2 text-gray-600">
                            <li class="flex justify-between border-b border-gray-100 pb-2">
                                <span class="font-medium">Available Variants:</span>
                                <span>{{ $product->variants->count() }}</span>
                            </li>
                            <li class="flex justify-between border-b border-gray-100 pb-2">
                                <span class="font-medium">Minimum Order:</span>
                                <span>Check variant options</span>
                            </li>
                            <li class="flex justify-between border-b border-gray-100 pb-2">
                                <span class="font-medium">Delivery:</span>
                                <span>2-5 business days</span>
                            </li>
                            <li class="flex justify-between border-b border-gray-100 pb-2">
                                <span class="font-medium">Category:</span>
                                <span>Printing Services</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateProductUI() {
    const variantRadios = document.querySelectorAll('.variant-radio');
    const quantitySelect = document.getElementById('quantitySelect');
    const priceDisplay = document.getElementById('priceDisplay');
    const mainImage = document.getElementById('mainProductImage');
    
    let selectedVariant = null;
    variantRadios.forEach(radio => {
        if (radio.checked) selectedVariant = radio;
    });
    
    if (!selectedVariant) return;
    
    const prices = JSON.parse(selectedVariant.getAttribute('data-prices'));
    const image = selectedVariant.getAttribute('data-image');
    const basePrice = selectedVariant.getAttribute('data-base-price');
    
    if (image) mainImage.src = image;
    
    quantitySelect.innerHTML = '';
    let firstPrice = null;
    for (const qty in prices) {
        const option = document.createElement('option');
        option.value = qty;
        option.text = `${qty} pieces - ₹${parseFloat(prices[qty]).toFixed(2)}`;
        quantitySelect.appendChild(option);
        if (!firstPrice) firstPrice = prices[qty];
    }
    
    priceDisplay.textContent = firstPrice ? parseFloat(firstPrice).toFixed(2) : parseFloat(basePrice).toFixed(2);
}

document.querySelectorAll('.variant-radio').forEach(radio => {
    radio.addEventListener('change', updateProductUI);
});

document.getElementById('quantitySelect').addEventListener('change', function() {
    const variantRadios = document.querySelectorAll('.variant-radio');
    let selectedVariant = null;
    variantRadios.forEach(radio => {
        if (radio.checked) selectedVariant = radio;
    });
    
    if (selectedVariant) {
        const prices = JSON.parse(selectedVariant.getAttribute('data-prices'));
        const qty = this.value;
        const price = prices[qty] || selectedVariant.getAttribute('data-base-price');
        document.getElementById('priceDisplay').textContent = parseFloat(price).toFixed(2);
    }
});

document.getElementById('addToCartBtn').addEventListener('click', function() {
    const variantRadios = document.querySelectorAll('.variant-radio');
    let variantId = null;
    variantRadios.forEach(radio => {
        if (radio.checked) variantId = radio.value;
    });
    
    const quantity = document.getElementById('quantitySelect').value;
    
    if (!variantId || !quantity) {
        alert('Please select a variant and quantity');
        return;
    }
    
    fetch("{{ route('cart.add') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({variant_id: variantId, quantity: quantity})
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            const btn = document.getElementById('addToCartBtn');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Added to Cart!';
            btn.classList.remove('bg-green-600', 'hover:bg-green-700');
            btn.classList.add('bg-green-700');
            
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.classList.remove('bg-green-700');
                btn.classList.add('bg-green-600', 'hover:bg-green-700');
            }, 2000);
        } else {
            alert('Could not add to cart.');
        }
    });
});

window.addEventListener('DOMContentLoaded', updateProductUI);
</script>
@endsection


<script>
// Change main image function
function changeMainImage(imageSrc) {
    const mainImage = document.getElementById('mainProductImage');
    mainImage.style.opacity = '0';
    setTimeout(() => {
        mainImage.src = imageSrc;
        mainImage.style.opacity = '1';
    }, 150);
}

// Update product UI
function updateProductUI() {
    const variantRadios = document.querySelectorAll('.variant-radio');
    const quantitySelect = document.getElementById('quantitySelect');
    const priceDisplay = document.getElementById('priceDisplay');
    const mainImage = document.getElementById('mainProductImage');
    const selectedVariantName = document.getElementById('selectedVariantName');
    const selectedQuantity = document.getElementById('selectedQuantity');
    
    let selectedVariant = null;
    variantRadios.forEach(radio => {
        if (radio.checked) selectedVariant = radio;
    });
    
    if (!selectedVariant) return;
    
    const prices = JSON.parse(selectedVariant.getAttribute('data-prices'));
    const image = selectedVariant.getAttribute('data-image');
    const basePrice = selectedVariant.getAttribute('data-base-price');
    const variantName = selectedVariant.getAttribute('data-name');
    
    // Update image
    if (image) {
        mainImage.style.opacity = '0';
        setTimeout(() => {
            mainImage.src = image;
            mainImage.style.opacity = '1';
        }, 150);
    }
    
    // Update variant name
    selectedVariantName.textContent = variantName;
    
    // Populate quantity dropdown
    quantitySelect.innerHTML = '';
    let firstPrice = null;
    let firstQty = null;
    for (const qty in prices) {
        const option = document.createElement('option');
        option.value = qty;
        option.text = `${qty} pieces - ₹${parseFloat(prices[qty]).toFixed(2)}`;
        quantitySelect.appendChild(option);
        if (!firstPrice) {
            firstPrice = prices[qty];
            firstQty = qty;
        }
    }
    
    // Update price and quantity display
    priceDisplay.textContent = firstPrice ? parseFloat(firstPrice).toFixed(2) : parseFloat(basePrice).toFixed(2);
    selectedQuantity.textContent = firstQty || '0';
}

// Variant change event
document.querySelectorAll('.variant-radio').forEach(radio => {
    radio.addEventListener('change', updateProductUI);
});

// Quantity change event
document.getElementById('quantitySelect').addEventListener('change', function() {
    const variantRadios = document.querySelectorAll('.variant-radio');
    const selectedQuantity = document.getElementById('selectedQuantity');
    let selectedVariant = null;
    
    variantRadios.forEach(radio => {
        if (radio.checked) selectedVariant = radio;
    });
    
    if (selectedVariant) {
        const prices = JSON.parse(selectedVariant.getAttribute('data-prices'));
        const qty = this.value;
        const price = prices[qty] || selectedVariant.getAttribute('data-base-price');
        document.getElementById('priceDisplay').textContent = parseFloat(price).toFixed(2);
        selectedQuantity.textContent = qty;
    }
});

// Add to cart
document.getElementById('addToCartBtn').addEventListener('click', function() {
    const variantRadios = document.querySelectorAll('.variant-radio');
    let variantId = null;
    variantRadios.forEach(radio => {
        if (radio.checked) variantId = radio.value;
    });
    
    const quantity = document.getElementById('quantitySelect').value;
    
    if (!variantId || !quantity) {
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
    btn.innerHTML = '<svg class="animate-spin w-6 h-6 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>';
    
    fetch("{{ route('cart.add') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({variant_id: variantId, quantity: quantity})
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            btn.innerHTML = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Added to Cart!';
            btn.classList.remove('bg-green-600', 'hover:bg-green-700');
            btn.classList.add('bg-green-700');
            
            Swal.fire({
                icon: 'success',
                title: 'Added to Cart!',
                text: 'Product has been added to your cart successfully.',
                confirmButtonColor: '#3b82f6',
                timer: 2000,
                timerProgressBar: true
            });
            
            setTimeout(() => {
                btn.innerHTML = originalHTML;
                btn.classList.remove('bg-green-700');
                btn.classList.add('bg-green-600', 'hover:bg-green-700');
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
window.addEventListener('DOMContentLoaded', updateProductUI);
</script>
@endsection
