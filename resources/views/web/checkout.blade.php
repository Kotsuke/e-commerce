<x-layout>
    <x-slot name="title">Checkout</x-slot>

    <div class="container my-5">
        <h1 class="mb-4 fw-bold">Checkout</h1>

        <div class="row g-4">
            {{-- Form Checkout --}}
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <form action="{{ route('checkout.store') }}" method="POST">
                            @csrf
                            <h5 class="mb-4 fw-semibold">Informasi Pengiriman</h5>

                            {{-- Nama --}}
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ auth()->guard('customer')->user()->name }}" readonly>
                            </div>

                            {{-- Email --}}
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ auth()->guard('customer')->user()->email }}" readonly>
                            </div>

                            {{-- Telepon --}}
                            <div class="mb-3">
                                <label for="phone" class="form-label">Telepon</label>
                                <input type="text" class="form-control" name="phone" value="{{ old('phone') }}"
                                    placeholder="08xxxxxxxxxx" required>
                            </div>

                            {{-- Alamat --}}
                            <div class="mb-3">
                                <label for="address" class="form-label">Alamat</label>
                                <textarea name="address" class="form-control" rows="3" required>{{ old('address') }}</textarea>
                            </div>

                            {{-- Catatan --}}
                            <div class="mb-3">
                                <label for="notes" class="form-label">Catatan</label>
                                <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                            </div>

                            <hr class="my-4">

                            {{-- Metode Pembayaran --}}
                            <h5 class="mb-3 fw-semibold">Metode Pembayaran</h5>
                            <div class="mb-3">
                                <select name="payment_method" class="form-select" required>
                                    <option value="">-- Pilih Metode Pembayaran --</option>
                                    <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                                    <option value="cod" {{ old('payment_method') == 'cod' ? 'selected' : '' }}>Dana</option>
                                    <option value="cod" {{ old('payment_method') == 'cod' ? 'selected' : '' }}>Shopeepay</option>
                                    <option value="cod" {{ old('payment_method') == 'cod' ? 'selected' : '' }}>COD (Bayar di Tempat)</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 mt-3">
                                Lanjutkan Pembayaran
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Ringkasan Pesanan --}}
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-3 fw-semibold">Ringkasan Pesanan</h5>

                        @if($cart && count($cart->items))
                            <ul class="list-group mb-3">
                                @foreach($cart->items as $item)
                                    <li class="list-group-item d-flex align-items-start gap-3">
                                        <img src="{{ $item->itemable->image_url ? Storage::url($item->itemable->image_url) : 'https://via.placeholder.com/50?text=Product' }}"
                                             alt="{{ $item->itemable->name }}"
                                             class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                        <div class="flex-grow-1">
                                            <div class="fw-semibold">{{ $item->itemable->name }}</div>
                                            <small class="text-muted">Qty: {{ $item->quantity }}</small>
                                        </div>
                                        <div class="text-end text-muted">
                                            Rp{{ number_format($item->itemable->price * $item->quantity, 0, ',', '.') }}
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
                            <div class="alert alert-info text-center small mb-0">
                                Gratis ongkir untuk pesanan di atas Rp50.000!
                            </div>
                        @else
                            <div class="alert alert-warning">
                                Keranjang Anda kosong.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>