<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Keranjang Reservasi</h5>
                </div>
                <div class="card-body">
                    @if (session()->has('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                    @endif
                    @if (session()->has('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (count($carts) > 0)
                        @foreach ($carts as $cart)
                            <div class="d-flex mb-4 pb-4 border-bottom cart-item">
                                <div class="flex-shrink-0">
                                    @php
                                        $imagePath = null;
                                        
                                        // Prioritas: gambar kamar -> gambar ruang -> gambar layanan
                                        if ($cart->kamar && $cart->kamar->gambarRuangs->count() > 0) {
                                            $imagePath = 'storage/' . $cart->kamar->gambarRuangs->first()->path;
                                        } elseif ($cart->ruang && $cart->ruang->gambarRuangs->count() > 0) {
                                            $imagePath = 'storage/' . $cart->ruang->gambarRuangs->first()->path;
                                        } elseif ($cart->layanan && $cart->layanan->gambar->count() > 0) {
                                            $imagePath = 'storage/' . $cart->layanan->gambar->first()->path;
                                        }
                                    @endphp
                                    
                                    <img src="{{ $imagePath ? asset($imagePath) : 'https://via.placeholder.com/150x150/e9ecef/6c757d?text=No+Image' }}"
                                        alt="{{ $cart->layanan->nama_layanan }}" class="img-fluid cart-item-image"
                                        style="width: 120px; height: 120px; object-fit: cover;">
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5>{{ $cart->layanan->nama_layanan }}</h5>
                                    
                                    @if ($cart->kamar)
                                        <p class="mb-1 text-muted">
                                            <i class="fas fa-bed me-1"></i>
                                            <span class="fw-bold">Kamar:</span> {{ $cart->kamar->nomor_kamar }}
                                        </p>
                                    @endif
                                    
                                    @if ($cart->ruang)
                                        <p class="mb-1 text-muted">
                                            <i class="fas fa-door-open me-1"></i>
                                            <span class="fw-bold">Ruang:</span> {{ $cart->ruang->kode_ruang }}
                                        </p>
                                    @endif
                                    
                                    <p class="mb-1">
                                        <span class="fw-bold">Kegiatan:</span> {{ $cart->nama_kegiatan }}
                                    </p>
                                    
                                    @if ($cart->tanggal_checkin)
                                        <p class="mb-1">
                                            <span class="fw-bold">Tanggal:</span> 
                                            @if ($cart->tanggal_checkout && $cart->tanggal_checkin != $cart->tanggal_checkout)
                                                {{ \Carbon\Carbon::parse($cart->tanggal_checkin)->format('d M Y') }} - {{ \Carbon\Carbon::parse($cart->tanggal_checkout)->format('d M Y') }}
                                            @else
                                                {{ \Carbon\Carbon::parse($cart->tanggal_checkin)->format('d M Y') }}
                                            @endif
                                        </p>
                                    @endif
                                    
                                    @if ($cart->jam_mulai && $cart->jam_selesai)
                                        <p class="mb-1">
                                            <span class="fw-bold">Jam:</span> 
                                            {{ \Carbon\Carbon::parse($cart->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($cart->jam_selesai)->format('H:i') }}
                                        </p>
                                    @else
                                        
                                    @endif
                                    
                                    <p class="mb-1">
                                        <span class="fw-bold">Jumlah Orang:</span> {{ $cart->jumlah_orang }}
                                    </p>
                                    <p class="mb-2">
                                        <span class="fw-bold">Total Biaya:</span>
                                        Rp {{ number_format($cart->total_biaya, 0, ',', '.') }}
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <button class="btn btn-outline-danger btn-sm"
                                            wire:click="removeItem({{ $cart->id }})">
                                            <i class="fas fa-trash me-1"></i> Hapus
                                        </button>
                                        <span
                                            class="badge bg-{{ $cart->status == 'pending' ? 'warning' : 'success' }} text-dark">
                                            {{ ucfirst($cart->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Keranjang Anda Kosong</h5>
                            {{-- <a href="{{ route('layanan.index') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-plus me-1"></i> Tambah Booking
                            </a> --}}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Ringkasan</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Item:</span>
                        <span>{{ count($carts) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="fw-bold">Total Biaya:</span>
                        <span class="fw-bold">Rp {{ number_format($carts->sum('total_biaya'), 0, ',', '.') }}</span>
                    </div>

                    @if (count($carts) > 0)
                        <button class="btn btn-primary w-100 mb-2" wire:click="checkout">
                            <i class="fas fa-credit-card me-1"></i> Checkout
                        </button>
                        {{-- <a href="{{ route('layanan.index') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-plus me-1"></i> Tambah Booking Lain
                        </a> --}}
                    @endif
                </div>
            </div>
        </div>
    </div>
    <style>
        .card {
            border-radius: 15px;
            border: none;
        }
        
        .card-header {
            border-radius: 15px 15px 0 0 !important;
        }
        
        .cart-item-image {
            border-radius: 10px;
            border: 3px solid #f8f9fa;
            transition: all 0.3s ease;
        }
        
        .cart-item-image:hover {
            border-color: #0d6efd;
            transform: scale(1.02);
        }
        
        .cart-item {
            transition: all 0.3s ease;
            padding: 1rem;
            border-radius: 10px;
        }
        
        .cart-item:hover {
            background-color: #f8f9fa;
        }
        
        .btn-outline-danger:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
        }
        
        .badge {
            font-size: 0.75rem;
            padding: 0.5rem 0.75rem;
        }
    </style>
</div>

