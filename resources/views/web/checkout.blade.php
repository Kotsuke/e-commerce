<x-layout>
    <x-slot name="title">Checkout</x-slot>

    <div class="container my-5">
        <h1 class="mb-4">Checkout</h1>

        <div class="row g-4">
            <!-- Formulir Checkout -->
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="mb-3">Informasi Pengiriman</h5>
                        <form action="{{ route('checkout.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ auth()->guard('customer')->user()->name }}"
                                    readonly>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Alamat Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ auth()->guard('customer')->user()->email }}"
                                    readonly>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Nomor Telepon</label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                    value="{{ old('phone') }}" placeholder="08xxxxxxxxxx" required>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Alamat</label>
                                <textarea class="form-control" id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="notes" class="form-label">Catatan Tambahan</label>
                                <textarea class="form-control" id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
                            </div>
                            <hr class="my-4">
                            <h5 class="mb-3">Metode Pembayaran</h5>
                            <div class="mb-3">
                                <label for="payment_method" class="form-label">Pilih Metode Pembayaran</label>
                                <select class="form-select" id="payment_method" name="payment_method" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                                    <option value="cod" {{ old('payment_method') == 'cod' ? 'selected' : '' }}>Bayar di Tempat (COD)</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 mt-3">Lanjutkan Pembayaran</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Ringkasan Pesanan -->
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-3">Ringkasan Pesanan</h5>
                        @if($cart && count($cart->items))
                            <ul class="list-group mb-3">
                                @foreach($cart->items as $item)
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="me-3">
                                            <img src="{{ $item->itemable->image_url ? Storage::url($item->itemable->image_url) : 'https://via.placeholder.com/50?text=Product' }}"
                                                 alt="{{ $item->itemable->name }}" style="width: 50px; height: 50px; object-fit: cover;" class="rounded">
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-semibold">{{ $item->itemable->name }}</div>
                                            <small class="text-muted">Qty: {{ $item->quantity }}</small>
                                        </div>
                                        <div>
                                            <span class="text-muted">Rp{{ number_format($item->itemable->price * $item->quantity, 0, ',', '.') }}</span>
                                        </div>
                                    </li>
                                @endforeach
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Subtotal</span>
                                    <strong>Rp{{ number_format($cart->calculatedPriceByQuantity(), 0, ',', '.') }}</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Ongkir</span>
                                    <strong>Rp0</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Total</span>
                                    <strong>Rp{{ number_format($cart->calculatedPriceByQuantity(), 0, ',', '.') }}</strong>
                                </li>
                            </ul>
                            <div class="alert alert-info mt-2">
                                Gratis ongkir untuk pesanan di atas Rp50.000!
                            </div>
                        @else
                            <div class="alert alert-warning">
                                Keranjang kosong. Silakan tambahkan produk terlebih dahulu.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
