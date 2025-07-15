@props([
    'title' => null,
    'badge' => null, // untuk ganti 'icon'
])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-zinc-900 rounded-lg shadow-sm p-6']) }}>
    @if ($title || $badge)
        <div class="flex justify-between items-center mb-2">
            @if ($title)
                <h3 class="text-sm font-medium text-zinc-600 dark:text-zinc-300">{{ $title }}</h3>
            @endif

            @if ($badge)
                <span class="bg-pink-100 dark:bg-pink-900/30 text-pink-700 dark:text-pink-500 rounded-md px-2 py-1 text-xs font-medium">
                    {{ $badge }}
                </span>
            @endif
        </div>
    @endif

    {{ $slot }}
</div>
