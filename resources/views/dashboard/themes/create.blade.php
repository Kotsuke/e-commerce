<x-layouts.app :title="__('Theme')">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl">Add New Theme</flux:heading>
        <flux:subheading size="lg" class="mb-6">Manage data Theme</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    @if(session()->has('successMessage'))
        <flux:badge color="lime" class="mb-3 w-full">{{session()->get('successMessage')}}</flux:badge>
    @elseif(session()->has('errorMessage'))
        <flux:badge color="red" class="mb-3 w-full">{{session()->get('errorMessage')}}</flux:badge>
    @endif

    <form action="{{ route('themes.store') }}" method="post" enctype="multipart/form-data">
        @csrf

        <flux:input label="Name" name="name" class="mb-3" />

        <flux:textarea label="Description" name="description" class="mb-3" />

        <flux:input label="Folder" name="folder" class="mb-3" />

        <flux:field>
            <flux:label>Status</flux:label>
            <flux:select name="status" wire:model.live="status">
                <option value="">-- Pilih Status --</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </flux:select>
            <flux:error name="status" />
        </flux:field>   

        <flux:separator variant="subtle" />

        <div class="mt-4">
            <flux:button type="submit" variant="primary">Save</flux:button>
            <flux:link href="{{ route('products.index') }}" variant="ghost" class="ml-3">Back</flux:link>
        </div>
    </form>
</x-layouts.app>