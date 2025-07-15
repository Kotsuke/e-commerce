<?php
use App\Models\Product;
use App\Models\Categories;
use App\Models\Order;
?>

<x-layouts.app :title="__('Dashboard')">
    <div class="p-6 space-y-6">

        <!-- Stats -->
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <x-card>
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-sm font-semibold text-zinc-600 dark:text-zinc-300">Total Products</h3>
                    <span class="text-xs bg-pink-100 text-pink-700 dark:bg-pink-900/30 dark:text-pink-500 px-2 py-1 rounded">
                        Products
                    </span>
                </div>
                <p class="text-3xl font-bold text-zinc-900 dark:text-white">{{ Product::count() }}</p>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">
                    Active: {{ Product::where('is_active', true)->count() }}
                </p>
            </x-card>

            <x-card>
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-sm font-semibold text-zinc-600 dark:text-zinc-300">Total Categories</h3>
                    <span class="text-xs bg-pink-100 text-pink-700 dark:bg-pink-900/30 dark:text-pink-500 px-2 py-1 rounded">
                        Categories
                    </span>
                </div>
                <p class="text-3xl font-bold text-zinc-900 dark:text-white">{{ Categories::count() }}</p>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">
                    With Products: {{ Categories::whereHas('products')->count() }}
                </p>
            </x-card>

            <x-card>
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-sm font-semibold text-zinc-600 dark:text-zinc-300">Total Orders</h3>
                    <span class="text-xs bg-pink-100 text-pink-700 dark:bg-pink-900/30 dark:text-pink-500 px-2 py-1 rounded">
                        Orders
                    </span>
                </div>
                <p class="text-3xl font-bold text-zinc-900 dark:text-white">{{ Order::count() }}</p>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">
                    Pending: {{ Order::where('status', 'pending')->count() }} |
                    Processing: {{ Order::where('status', 'processing')->count() }}
                </p>
            </x-card>
        </div>

        <!-- Activity -->
        <div class="grid gap-6 md:grid-cols-2">
            <!-- Recent Orders -->
            <x-card>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-base font-semibold text-zinc-800 dark:text-white">Recent Orders</h3>
                    <a href="{{ route('admin.orders.index') }}" class="text-sm text-pink-600 hover:underline dark:text-pink-400">View All</a>
                </div>
                <div class="space-y-4">
                    @forelse(Order::with('customer')->latest()->take(5)->get() as $order)
                        <div class="flex justify-between items-start border-b pb-3 dark:border-zinc-700">
                            <div>
                                <p class="font-medium text-zinc-900 dark:text-white">
                                    #{{ $order->id }} - {{ $order->customer?->name ?? 'N/A' }}
                                </p>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400">
                                    {{ $order->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <span class="text-xs font-medium px-3 py-1 rounded-full"
                                @class([
                                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' => $order->status === 'pending',
                                    'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' => $order->status === 'processing',
                                    'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' => $order->status === 'completed',
                                    'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' => $order->status === 'cancelled',
                                ])>
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">No recent orders found.</p>
                    @endforelse
                </div>
            </x-card>

            <!-- Recent Products -->
            <x-card>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-base font-semibold text-zinc-800 dark:text-white">Recent Products</h3>
                    <a href="{{ route('products.index') }}" class="text-sm text-pink-600 hover:underline dark:text-pink-400">View All</a>
                </div>
                <div class="space-y-4">
                    @forelse(Product::with('category')->latest()->take(5)->get() as $product)
                        <div class="flex items-start gap-4 border-b pb-3 dark:border-zinc-700">
                            <div class="h-10 w-10 flex-shrink-0">
                                @if($product->image_url)
                                    <img src="{{ Storage::url($product->image_url) }}" alt="{{ $product->name }}" class="h-10 w-10 rounded object-cover">
                                @else
                                    <div class="h-10 w-10 flex items-center justify-center bg-zinc-100 dark:bg-zinc-800 rounded">
                                        <svg class="h-6 w-6 text-zinc-500 dark:text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-zinc-900 dark:text-white">{{ $product->name }}</p>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $product->category?->name ?? 'No Category' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-zinc-900 dark:text-white">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </p>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400">
                                    Stock: {{ $product->stock }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">No recent products found.</p>
                    @endforelse
                </div>
            </x-card>
        </div>
    </div>
</x-layouts.app>
