@extends('layouts.admin')

@section('page-title', 'Edit Product')

@section('content')
<div class="p-6">
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Edit Product</h1>
            <p class="mt-1 text-sm text-gray-600">Update product information</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Product Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" id="description" rows="4" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Current Image</label>
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="h-32 w-32 object-contain rounded-lg mb-3" alt="{{ $product->name }}"/>
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
                </div>

                <div class="mt-8 flex gap-3">
                    <button type="submit" class="flex-1 bg-blue-600 !text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 transition">
                        Update Product
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="flex-1 bg-gray-100 text-gray-700 px-6 py-3 rounded-lg font-medium hover:bg-gray-200 transition text-center">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
