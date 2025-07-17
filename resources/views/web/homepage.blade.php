<x-layout>
    <x-slot name="title"> Beranda</x-slot>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center">
                    <h1>Selamat Datang di Toko Kami</h1>
                    <p>Temukan produk terbaik dengan kualitas premium dan harga terjangkau. Belanja sekarang dan dapatkan pengalaman berbelanja yang tak terlupakan!</p>
                    <a href="{{ URL::to('/products') }}" class="hero-cta">Mulai Belanja</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-3">
        <div class="section-header d-flex justify-content-between align-items-center">
            <div>
                <h3>Kategori Product</h3>
                <p class="text-muted mb-0">Jelajahi berbagai kategori produk pilihan kami</p>
            </div>
            <a href="{{ URL::to('/categories') }}" class="btn btn-outline-primary btn-sm">Lihat Semua Kategori</a>
        </div>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
            @foreach($categories as $category)
                <div class="col">
                    <a href="{{ URL::to('/category/'.$category->slug) }}" class="card text-decoration-none">
                        <div class="card category-card text-center h-100 py-3 border-0 shadow-sm">
                            <div class="mx-auto mb-2" style="width:64px;height:64px;display:flex;align-items:center;justify-content:center;background:
                                #f8f9fa;border-radius:50%;">
                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" style="width:36px;height:36px;object-fit:contain;">
                            </div>
                            <div class="card-body p-2">
                                <h6 class="card-title mb-1 text-dark">{{ $category->name }}</h6>
                                <p class="card-text text-muted small text-truncate">{{ $category->description }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <div class="container py-3">
        <div class="section-header d-flex justify-content-between align-items-center">
            <div>
                <h3>Product Kami</h3>
                <p class="text-muted mb-0">Koleksi produk terbaru dan terlaris untuk Anda</p>
            </div>
            <a href="{{ URL::to('/products') }}" class="btn btn-outline-primary btn-sm">Lihat Semua Product</a>
        </div>
        <div class="row">
            @forelse($products as $product)
                <div class="col-md-3 mb-4">
                    <a href="{{ route('product.show', $product->slug) }}" class="text-decoration-none text-dark">
                        <div class="card product-card h-100 shadow-sm">
                            <img src="{{ Storage::url($product->image_url) }}" alt="{{ $product->name }}" class="card-img-top" style="height: 200px; object-fit: contain;">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="card-text text-truncate">{{ $product->description }}</p>
                                <div class="mt-auto">
                                    <span class="fw-bold text-primary">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Belum ada produk tersedia saat ini. Silakan kembali lagi nanti!
                    </div>
                </div>
            @endforelse

            @if($products->hasPages())
                <div class="d-flex justify-content-center w-100 mt-4">
                    {{ $products->links('vendor.pagination.simple-bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>

    <div class="container py-5">
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <h5>Pengiriman Cepat</h5>
                    <p class="text-muted">Gratis ongkir untuk pembelian di atas Rp 50.000</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h5>Garansi Kualitas</h5>
                    <p class="text-muted">Produk berkualitas dengan garansi resmi</p>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h5>Customer Support</h5>
                    <p class="text-muted">Tim support siap membantu Anda 24/7</p>
                </div>
            </div>
        </div>
    </div>
</x-layout>
