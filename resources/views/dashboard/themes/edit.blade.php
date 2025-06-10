<x-layouts.app :title="__('Theme')">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl">Update Theme</flux:heading>
        <flux:subheading size="lg" class="mb-6">Manage data Theme</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    @if(session()->has('successMessage'))
        <flux:badge color="lime" class="mb-3 w-full">{{session()->get('successMessage')}}</flux:badge>
    @elseif(session()->has('errorMessage'))
        <flux:badge color="red" class="mb-3 w-full">{{session()->get('errorMessage')}}</flux:badge>
    @endif

    <form action="{{ route('themes.update', $theme->id) }}" method="post" enctype="multipart/form-data">
        @method('patch')
        @csrf

        <flux:input label="Name" name="name" value="{{ $theme->name }}" class="mb-3" />

        <flux:textarea label="Description" name="description" class="mb-3">{{ $theme->description }}</flux:textarea>

        <flux:input label="Folder" name="folder" value="{{ $theme->folder }}" class="mb-3" />

        <flux:field>
            <flux:label>Status</flux:label>
            <flux:select name="status" class="w-full mb-3">
                <option value="">-- Pilih Status --</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </flux:select>
            <flux:error name="status" />
        </flux:field> 

        <flux:separator variant="subtle" />

        <div class="mt-4">
            <flux:button type="submit" variant="primary">Update</flux:button>
            <flux:link href="{{ route('themes.index') }}" variant="ghost" class="ml-3">Back</flux:link>
        </div>
    </form>
</x-layouts.app>