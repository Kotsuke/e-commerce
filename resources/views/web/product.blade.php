<x-layout>
    <x-slot name="title">{{ $product->name }}</x-slot>

    @if(session('error'))
        <div class="container mt-4">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <div class="container py-5">
        <div class="row g-5">
            <!-- Product Image -->
            <div class="col-md-6">
                <div class="card product-card shadow-sm h-100 p-3">
                    <img src="{{ $product->image_url ? Storage::url($product->image_url) : 'https://via.placeholder.com/350x200?text=No+Image' }}" alt="{{ $product->name }}" class="img-fluid rounded w-100" style="max-height: 400px; object-fit: contain; background: #f8f9fa; padding: 1rem;">
                    <div class="mt-3">
                        <span class="badge bg-secondary">{{ $product->category->name ?? 'Kategori Tidak Diketahui' }}</span>
                    </div>
                </div>
            </div>

            <!-- Product Details -->
            <div class="col-md-6">
                <h1 class="fw-bold mb-3">{{ $product->name }}</h1>
                <div class="mb-3">
                    <span class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    @if($product->old_price)
                        <span class="text-muted text-decoration-line-through ms-2">Rp {{ number_format($product->old_price, 0, ',', '.') }}</span>
                    @endif
                </div>

                <p class="text-muted mb-4">{{ $product->description }}</p>

                <form action="{{ route('cart.add') }}" method="POST" class="mb-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="input-group" style="max-width: 320px;">
                        <input type="number" name="quantity" class="form-control" value="1" min="1" max="{{ $product->stock }}">
                        <button class="btn btn-outline-primary" type="submit">
                            <i class="bi bi-cart-plus me-1"></i> Tambah ke Keranjang
                        </button>
                    </div>
                </form>

                <ul class="list-group list-group-flush mb-4 shadow-sm rounded">
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>Stok:</strong>
                        <span class="{{ $product->stock > 0 ? 'text-success' : 'text-danger' }}">
                            {{ $product->stock > 0 ? $product->stock : 'Habis' }}
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>Kategori:</strong>
                        <span>{{ $product->category->name ?? '-' }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Long Description -->
        <div class="mt-5">
            <h4 class="mb-3">Deskripsi Produk</h4>
            <div class="bg-light p-4 rounded shadow-sm">
                {!! nl2br(e($product->long_description ?? $product->description)) !!}
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <div class="container py-5">
        <div class="section-header d-flex justify-content-between align-items-center">
            <h3>Produk Lainnya</h3>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            @forelse($relatedProducts as $relatedProduct)
                <div class="col">
                    <a href="{{ route('product.show', $relatedProduct->slug) }}" class="text-decoration-none text-dark">
                        <div class="card product-card h-100 shadow-sm">
                            <img src="{{ $relatedProduct->image_url ? Storage::url($relatedProduct->image_url) : 'https://via.placeholder.com/350x200?text=No+Image' }}"
                                alt="{{ $relatedProduct->name }}"
                                class="card-img-top"
                                style="height: 200px; object-fit: contain;">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $relatedProduct->name }}</h5>
                                <p class="card-text text-truncate">{{ $relatedProduct->description }}</p>
                                <div class="mt-auto">
                                    <span class="fw-bold text-primary">Rp {{ number_format($relatedProduct->price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col">
                    <div class="alert alert-info">Tidak ada produk terkait.</div>
                </div>
            @endforelse
        </div>
    </div>
</x-layout>