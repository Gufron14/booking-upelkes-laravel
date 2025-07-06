<div class="container py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold text-primary mb-1">Daftar Kamar & Layanan</h2>
                    <p class="text-muted mb-0">Pilih kamar sesuai kebutuhan Anda</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0">
                    <i class="fas fa-search text-muted"></i>
                </span>
                <input type="text" 
                       class="form-control border-start-0 ps-0" 
                       placeholder="Cari layanan..." 
                       wire:model.live="search">
            </div>
        </div>
        <div class="col-md-3">
            <select class="form-select" wire:model.live="kategori">
                <option value="">Semua Kategori</option>
                <option value="umum">Umum</option>
                <option value="pemerintah">Pemerintah</option>
            </select>
        </div>
    </div>

    <!-- Cards Section -->
    <div class="row g-4">
        @forelse($layananList as $layanan)
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 shadow-sm border-0 position-relative overflow-hidden">
                    <!-- Badge Kategori -->
                    <div class="position-absolute top-0 start-0 z-3 m-3">
                        <span class="badge {{ $layanan->kategori == 'pemerintah' ? 'bg-success' : 'bg-primary' }} px-3 py-2 rounded-pill">
                            <i class="fas {{ $layanan->kategori == 'pemerintah' ? 'fa-building' : 'fa-users' }} me-1"></i>
                            {{ ucfirst($layanan->kategori) }}
                        </span>
                    </div>

                    <!-- Gambar -->
                    <div class="position-relative">
                        @if($layanan->gambar->count() > 0)
                            <img src="{{ asset('storage/' . $layanan->gambar->first()->path) }}" 
                                 class="card-img-top" 
                                 alt="{{ $layanan->nama_layanan }}"
                                 style="height: 250px; object-fit: cover;">
                        @else
                            <div class="bg-gradient-primary d-flex align-items-center justify-content-center" 
                                 style="height: 250px;">
                                <div class="text-center text-muted">
                                    <i class="fas fa-image fa-3x mb-2"></i>
                                    <p class="mb-0 text-light">{{ $layanan->nama_layanan }}</p>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Overlay gradient -->
                        <div class="position-absolute bottom-0 start-0 w-100 h-50" 
                             style="background: linear-gradient(transparent, rgba(0,0,0,0.3));"></div>
                    </div>

                    <div class="card-body p-4">
                        <!-- Nama Layanan -->
                        <h5 class="card-title fw-bold text-dark mb-2">{{ $layanan->nama_layanan }}</h5>
                        
                        <!-- Deskripsi -->
                        <p class="card-text text-muted mb-3" style="font-size: 0.9rem;">
                            {{ Str::limit($layanan->deskripsi, 100) }}
                        </p>

                        <!-- Info Grid -->
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-users text-primary me-2"></i>
                                    <small class="text-muted">
                                        <strong>{{ $layanan->kapasitas ?? 'N/A' }}</strong> orang
                                    </small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-door-open text-success me-2"></i>
                                    <small class="text-muted">
                                        <strong>{{ $layanan->kamar->count() }}</strong> kamar
                                    </small>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-clock text-warning me-2"></i>
                                    <small class="text-muted">
                                        Tarif <strong>{{ $layanan->satuan }}</strong>
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Kamar Tersedia -->
                        @if($layanan->kamar->count() > 0)
                            <div class="mb-3">
                                <small class="text-muted fw-semibold">Kamar Tersedia:</small>
                                <div class="d-flex flex-wrap gap-1 mt-1">
                                    @foreach($layanan->kamar->take(5) as $kamar)
                                        <span class="badge bg-light text-dark border">
                                            {{ $kamar->nomor_kamar }}
                                        </span>
                                    @endforeach
                                    @if($layanan->kamar->count() > 5)
                                        <span class="badge bg-secondary">
                                            +{{ $layanan->kamar->count() - 5 }} lainnya
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Harga dan Action -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="text-primary fw-bold mb-0">
                                    Rp {{ number_format($layanan->tarif, 0, ',', '.') }}
                                </h4>
                                <small class="text-muted">{{ $layanan->satuan }}</small>
                            </div>
                            <div class="btn-group">
                                <button class="btn btn-outline-primary btn-sm" 
                                        wire:click="selectLayanan({{ $layanan->id }})"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#detailModal">
                                    <i class="fas fa-eye me-1"></i>
                                    Detail
                                </button>
                                <button class="btn btn-primary btn-sm">
                                    <i class="fas fa-calendar-plus me-1"></i>
                                    Booking
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Status Indicator -->
                    <div class="position-absolute top-0 end-0 m-3">
                        @if($layanan->kamar->count() > 0)
                            <span class="badge bg-success rounded-circle p-2" title="Tersedia">
                                <i class="fas fa-check"></i>
                            </span>
                        @else
                            <span class="badge bg-danger rounded-circle p-2" title="Tidak Tersedia">
                                <i class="fas fa-times"></i>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-search fa-4x text-muted"></i>
                    </div>
                    <h4 class="text-muted">Tidak ada layanan ditemukan</h4>
                    <p class="text-muted">Coba ubah kata kunci pencarian atau filter Anda</p>
                    <button class="btn btn-primary" wire:click="$set('search', '')" wire:click="$set('kategori', '')">
                        Reset Filter
                    </button>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Modal Detail -->
    <div class="modal fade" id="detailModal" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Detail Layanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if($selectedLayanan)
                        @php
                            $layananDetail = $layananList->find($selectedLayanan);
                        @endphp
                        @if($layananDetail)
                            <div class="row">
                                <div class="col-md-6">
                                    @if($layananDetail->gambar->count() > 0)
                                        <img src="{{ asset('storage/' . $layananDetail->gambar->first()->path) }}" 
                                             class="img-fluid rounded" 
                                             alt="{{ $layananDetail->nama_layanan }}">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                             style="height: 200px;">
                                            <div class="text-center text-muted">
                                                <i class="fas fa-image fa-3x mb-2"></i>
                                                <p class="mb-0">Tidak ada gambar</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <h4 class="fw-bold">{{ $layananDetail->nama_layanan }}</h4>
                                    <p class="text-muted">{{ $layananDetail->deskripsi }}</p>
                                    
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <strong>Kategori:</strong><br>
                                            <span class="badge bg-primary">{{ ucfirst($layananDetail->kategori) }}</span>
                                        </div>
                                        <div class="col-6">
                                            <strong>Kapasitas:</strong><br>
                                            {{ $layananDetail->kapasitas ?? 'N/A' }} orang
                                        </div>
                                        <div class="col-6">
                                            <strong>Tarif:</strong><br>
                                            Rp {{ number_format($layananDetail->tarif, 0, ',', '.') }}
                                        </div>
                                        <div class="col-6">
                                            <strong>Satuan:</strong><br>
                                            {{ $layananDetail->satuan }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary">Booking Sekarang</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Custom CSS -->
    <style>
        .card {
            transition: all 0.3s ease;
            border-radius: 15px;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
        }
        
        .card-img-top {
            border-radius: 15px 15px 0 0;
        }
        
        .btn-group .btn {
            border-radius: 8px;
        }
        
        .btn-group .btn:not(:last-child) {
            margin-right: 5px;
        }
        
        .badge {
            font-size: 0.75rem;
        }
        
        .input-group-text {
            border-radius: 10px 0 0 10px;
        }
        
        .form-control, .form-select {
            border-radius: 0 10px 10px 0;
        }
        
        .form-select {
            border-radius: 10px;
        }
    </style>
    
    <!-- Font Awesome (jika belum ada) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</div>

