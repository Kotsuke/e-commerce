<x-layouts.app :title="__('Products')">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl">Add New Products</flux:heading>
        <flux:subheading size="lg" class="mb-6">Manage data Products</flux:heading>
        <flux:separator variant="subtle" />
    </div>

    @if(session()->has('successMessage'))
        <flux:badge color="lime" class="mb-3 w-full">{{session()->get('successMessage')}}</flux:badge>
    @elseif(session()->has('errorMessage'))
        <flux:badge color="red" class="mb-3 w-full">{{session()->get('errorMessage')}}</flux:badge>
    @endif

    <form action="{{ route('products.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <flux:input label="Name" name="name" class="mb-3" />
        <flux:input label="Slug" name="slug" class="mb-3" />
        <flux:input label="SKU" name="sku" class="mb-3" />
        <flux:input type="number" label="Price" name="price" class="mb-3" step="0.01" />
        <flux:input type="number" label="Stock" name="stock" class="mb-3" />
        <flux:select label="Category" name="product_category_id" class="mb-3">
            <option value="">-- Select Category --</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </flux:select>
        <flux:textarea label="Description" name="description" class="mb-3" />
        <flux:input type="file" label="Image" name="image" class="mb-3" />
        <flux:select label="Is Active?" name="is_active" class="mb-3">
            <option value="1">Yes</option>
            <option value="0">No</option>
        </flux:select>
        <flux:button type="submit" variant="primary">Save</flux:button>
    </form>
</x-layouts.app>
