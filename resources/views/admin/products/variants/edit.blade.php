@extends('layouts.admin')

@section('page-title', 'Edit Variant')

@section('content')
<div class="p-6">
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Edit Variant</h1>
            <p class="mt-1 text-sm text-gray-600">Update variant details for {{ $product->name }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <form action="{{ route('admin.products.variants.update', [$product, $variant]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Variant Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $variant->name) }}" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="base_price" class="block text-sm font-medium text-gray-700 mb-2">Base Price (Per Piece)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">₹</span>
                            <input type="number" name="base_price" id="base_price" value="{{ old('base_price', $variant->base_price) }}" step="0.01" min="0" required 
                                class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        @error('base_price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Image</label>
                        @if($variant->image)
                            <img src="{{ asset('storage/' . $variant->image) }}" class="h-32 w-32 object-contain rounded-lg mb-3" alt="{{ $variant->name }}"/>
                        @else
                            <p class="text-sm text-gray-500 mb-3">No image uploaded</p>
                        @endif
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Update Image</label>
                        <input type="file" name="image" id="image" accept="image/*"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Quantity Pricing</label>
                        <div id="priceRows" class="space-y-3">
                            @foreach($variant->quantity_prices as $qty => $price)
                            <div class="flex gap-3 price-row">
                                <div class="flex-1">
                                    <input type="number" name="quantities[]" value="{{ $qty }}" min="1" required 
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="Quantity">
                                </div>
                                <div class="flex-1">
                                    <div class="relative">
                                        <span class="absolute left-3 top-2 text-gray-500">₹</span>
                                        <input type="number" name="prices[]" value="{{ $price }}" step="0.01" min="0" required 
                                            class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="Price">
                                    </div>
                                </div>
                                <button type="button" onclick="removeRow(this)" class="px-3 py-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            @endforeach
                        </div>
                        <button type="button" onclick="addPriceRow()" class="mt-3 inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Price Tier
                        </button>
                    </div>
                </div>

                <div class="mt-8 flex gap-3">
                    <button type="submit" class="flex-1 bg-blue-600 !text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition">
                        Update Variant
                    </button>
                    <a href="{{ route('admin.products.variants.index', $product) }}" class="flex-1 bg-gray-100 text-gray-700 px-6 py-3 rounded-lg font-medium hover:bg-gray-200 transition text-center">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function addPriceRow() {
    const container = document.getElementById('priceRows');
    const row = document.createElement('div');
    row.className = 'flex gap-3 price-row';
    row.innerHTML = `
        <div class="flex-1">
            <input type="number" name="quantities[]" min="1" required 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Quantity">
        </div>
        <div class="flex-1">
            <div class="relative">
                <span class="absolute left-3 top-2 text-gray-500">₹</span>
                <input type="number" name="prices[]" step="0.01" min="0" required 
                    class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Price">
            </div>
        </div>
        <button type="button" onclick="removeRow(this)" class="px-3 py-2 text-red-600 hover:bg-red-50 rounded-lg transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    `;
    container.appendChild(row);
}

function removeRow(button) {
    const rows = document.querySelectorAll('.price-row');
    if (rows.length > 1) {
        button.closest('.price-row').remove();
    }
}
</script>
@endsection
