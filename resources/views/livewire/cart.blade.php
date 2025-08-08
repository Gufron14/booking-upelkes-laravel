<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Keranjang Booking</h5>
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
                     
                     @if(count($carts) > 0)
                        @foreach($carts as $cart)
                            <div class="d-flex mb-4 pb-4 border-bottom">
                                <div class="flex-shrink-0">
                                    <img src="{{ $cart->layanan->gambar->first()->path ?? 'https://via.placeholder.com/150' }}" 
                                         alt="{{ $cart->layanan->nama_layanan }}" 
                                         class="img-fluid rounded" 
                                         style="width: 120px; height: 120px; object-fit: cover;">
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5>{{ $cart->layanan->nama_layanan }}</h5>
                                    {{-- <p class="mb-1">
                                        <span class="fw-bold">Tanggal:</span> 
                                        {{ $cart->tanggal_checkin->format('d M Y') }} - {{ $cart->tanggal_checkout->format('d M Y') }}
                                    </p> --}}
                                    @if($cart->jam_mulai)
                                        {{-- <p class="mb-1">
                                            <span class="fw-bold">Jam:</span> 
                                            {{ $cart->jam_mulai->format('H:i') }} - {{ $cart->jam_selesai->format('H:i') }}
                                        </p> --}}
                                    @endif
                                    <p class="mb-1">
                                        <span class="fw-bold">Jumlah Orang:</span> {{ $cart->jumlah_orang }}
                                    </p>
                                    <p class="mb-2">
                                        <span class="fw-bold">Total Biaya:</span> 
                                        Rp {{ number_format($cart->total_biaya, 0, ',', '.') }}
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <button class="btn btn-outline-danger btn-sm" wire:click="removeItem({{ $cart->id }})">
                                            <i class="fas fa-trash me-1"></i> Hapus
                                        </button>
                                        <span class="badge bg-{{ $cart->status == 'pending' ? 'warning' : 'success' }} text-dark">
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
                    
                    @if(count($carts) > 0)
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
</div>