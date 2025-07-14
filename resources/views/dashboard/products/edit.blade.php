<x-layouts.app :title="__('Products')">
    <flux:heading size="xl">Edit Product</flux:heading>
    <flux:separator class="my-4" />

    <form action="{{ route('products.update', $product->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <flux:input label="Name" name="name" value="{{ old('name', $product->name) }}" class="mb-3" />

        <flux:input label="Slug" name="slug" value="{{ old('slug', $product->slug) }}" class="mb-3" />

        <flux:input label="SKU" name="sku" value="{{ old('sku', $product->sku) }}" class="mb-3" />

        <flux:input type="number" label="Price" name="price" step="0.01" value="{{ old('price', $product->price) }}" class="mb-3" />

        <flux:input type="number" label="Stock" name="stock" value="{{ old('stock', $product->stock) }}" class="mb-3" />

        <flux:select label="Category" name="product_category_id" class="mb-3">
            <option value="">-- Select Category --</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ $category->id == $product->product_category_id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </flux:select>

        <flux:textarea label="Description" name="description" class="mb-3">{{ old('description', $product->description) }}</flux:textarea>

        <flux:input type="file" label="Image" name="image" class="mb-3" />
        @if($product->image_url)
            <img src="{{ asset('storage/' . $product->image_url) }}" class="w-24 mt-2 rounded shadow" alt="Product Image">
        @endif

        <flux:select label="Is Active?" name="is_active" class="mb-3">
            <option value="1" {{ $product->is_active ? 'selected' : '' }}>Yes</option>
            <option value="0" {{ !$product->is_active ? 'selected' : '' }}>No</option>
        </flux:select>

        <flux:button type="submit" variant="primary">Update</flux:button>
    </form>
</x-layouts.app>