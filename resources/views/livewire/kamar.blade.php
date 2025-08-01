<div class="container py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold text-primary mb-1">Daftar kamar</h2>
                    <p class="text-muted mb-0">Pilih kamar atau ruangan sesuai kebutuhan Anda</p>
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
                <input type="text" class="form-control border-start-0 ps-0" placeholder="Cari kamar..."
                    wire:model.live="search">
            </div>
        </div>
        {{-- <div class="col-md-3">
            <select class="form-select" wire:model.live="kategori">
                <option value="">Semua Kategori</option>
                <option value="umum">Umum</option>
                <option value="pemerintah">Pemerintah</option>
            </select>
        </div> --}}
    </div>

    <!-- Cards Section -->
    <div class="row g-4">
        @forelse($kamarList as $kamar)
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 shadow-sm border-0 position-relative overflow-hidden">
                    <!-- Badge Kategori -->
                    <div class="position-absolute top-0 start-0 z-3 m-3">
                        <span
                            class="badge {{ $kamar->kategori == 'pemerintah' ? 'bg-success' : 'bg-primary' }} px-3 py-2 rounded-pill">
                            <i class="fas {{ $kamar->kategori == 'pemerintah' ? 'fa-building' : 'fa-users' }} me-1"></i>
                            {{ ucfirst($kamar->kategori) }}
                        </span>
                    </div>

                    <!-- Gambar -->
                    <div class="position-relative">
                        @if ($kamar->layanan->gambar->count() > 0)
                            <img src="{{ asset('storage/' . $kamar->layanan->gambar->first()->path) }}"
                                class="card-img-top" alt="{{ $kamar->layanan->nama }}"
                                style="height: 250px; object-fit: cover;">
                        @else
                            <div class="bg-gradient-primary d-flex align-items-center justify-content-center"
                                style="height: 250px;">
                                <div class="text-center text-muted">
                                    <i class="fas fa-image fa-3x mb-2"></i>
                                    <p class="mb-0 text-light">{{ $kamar->layanan->nama }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Overlay gradient -->
                        <div class="position-absolute bottom-0 start-0 w-100 h-50"
                            style="background: linear-gradient(transparent, rgba(0,0,0,0.3));"></div>
                    </div>

                    <div class="card-body p-4">
                        <!-- Nama kamar -->
                        <h5 class="card-title fw-bold text-dark mb-2">{{ $kamar->layanan->nama_layanan }}</h5>

                        <!-- Deskripsi -->
                        <p class="card-text text-muted mb-3" style="font-size: 0.9rem;">
                            {{ Str::limit($kamar->deskripsi, 100) }}
                        </p>

                        <!-- Info Grid -->
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-users text-primary me-2"></i>
                                    <small class="text-muted">
                                        <strong>{{ $kamar->layanan->kapasitas ?? 'N/A' }}</strong> orang
                                    </small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-door-open text-success me-2"></i>
                                    <small class="text-muted">
                                        <strong>1</strong> Kamar
                                    </small>
                                </div>
                            </div>
                        </div>

                        @if ($kamar->layanan->fasilitas->count() > 0)
                            <div class="mb-3">
                                <small class="text-muted fw-semibold">Fasilitas:</small>
                                <div class="d-flex flex-wrap gap-1 mt-1">
                                    @foreach ($kamar->layanan->fasilitas->take(5) as $fasilitas)
                                        <span class="badge bg-light text-dark border">
                                            {{ $fasilitas->nama }}
                                        </span>
                                    @endforeach
                                    @if ($kamar->layanan->fasilitas->count() > 5)
                                        <span class="badge bg-secondary">
                                            +{{ $kamar->layanan->fasilitas->count() - 3 }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Kamar Tersedia -->
                        @if ($kamar->count() > 0)
                            <div class="mb-3">
                                <small class="text-muted fw-semibold">Kamar Tersedia:</small>
                                <div class="d-flex flex-wrap gap-1 mt-1">
                                    @foreach ($kamar->layanan->take(5) as $item)
                                        <span class="badge bg-light text-dark border">
                                            {{ $item->nomor_kamar }}
                                        </span>
                                    @endforeach
                                    @if ($kamar->layanan->count() > 5)
                                        <span class="badge bg-secondary">
                                            +{{ $kamar->layanan->count() - 5 }} lainnya
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Harga dan Action -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="text-primary fw-bold mb-0">
                                    Rp {{ number_format($kamar->layanan->tarif, 0, ',', '.') }}
                                </h4>
                                <small class="text-muted">
                                    @if ($kamar->layanan->satuan == 'per_hari')
                                        Per Hari
                                    @elseif ($kamar->layanan->satuan == 'per_jam')
                                        Per Jam
                                    @elseif ($kamar->layanan->satuan == 'per_orang_kunjungan')
                                        Per Orang/Kunjungan
                                    @elseif ($kamar->layanan->satuan == 'per_bulan')
                                        Per Bulan
                                    @elseif ($kamar->layanan->satuan == 'per_orang_hari')
                                        Per Orang/Hari
                                    @elseif ($kamar->layanan->satuan == 'per_kamar_hari')
                                        Per Kamar/Hari
                                    @elseif ($kamar->layanan->satuan == 'per_kegiatan_hari')
                                        Per Kegiatan/Hari
                                    @endif
                                </small>
                            </div>
                            <div class="btn-group">
                                <button class="btn btn-outline-primary btn-sm"
                                    wire:click="selectkamar({{ $kamar->id }})" data-bs-toggle="modal"
                                    data-bs-target="#detailModal">
                                    <i class="fas fa-eye me-1"></i>
                                    Detail
                                </button>
                                {{-- @if ($kamar->kamar->count() > 0 || $kamar->ruang->count() > 0)                                    
                                    <a href="{{ route('bookingId', ['kamar_id' => $kamar->id]) }}"
                                        class="btn btn-primary btn-sm">
                                        <i class="fas fa-calendar-plus me-1"></i>
                                        Booking
                                    </a>
                                @endif --}}

                            </div>
                        </div>
                    </div>

                    <!-- Status Indicator -->
                    <div class="position-absolute top-0 end-0 m-3">
                        <span class="badge bg-success rounded-circle p-2" title="Tersedia">
                            <i class="fas fa-check"></i>
                        </span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-search fa-4x text-muted"></i>
                    </div>
                    <h4 class="text-muted">Tidak ada kamar ditemukan</h4>
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
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Detail kamar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if ($selectedkamar)
                        @php
                            $kamarDetail = $kamarList->find($selectedkamar);
                        @endphp
                        @if ($kamarDetail)
                            <div class="row">
                                <div class="col-md-6">
                                    @if ($kamarDetail->layanan->gambar->count() > 0)
                                        <img src="{{ asset('storage/' . $kamarDetail->layanan->gambar->first()->path) }}"
                                            class="img-fluid rounded" alt="{{ $kamarDetail->nomor_kamar }}">
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
                                    <h4 class="fw-bold">Kamar {{ $kamarDetail->nomor_kamar }}</h4>
                                    {{-- <p class="text-muted">{{ $kamarDetail->deskripsi }}</p> --}}

                                    <div class="row g-3">
                                        <div class="col-6">
                                            <strong>Kategori:</strong><br>
                                            <span
                                                class="badge bg-primary">{{ ucfirst($kamarDetail->layanan->kategori) }}</span>
                                        </div>
                                        <div class="col-6">
                                            <strong>Kapasitas:</strong><br>
                                            {{ $kamarDetail->layanan->kapasitas ?? 'N/A' }} orang
                                        </div>
                                        <div class="col-6">
                                            <strong>Tarif:</strong><br>
                                            Rp {{ number_format($kamarDetail->layanan->tarif, 0, ',', '.') }}
                                        </div>
                                        <div class="col-6">
                                            <strong>Durasi:</strong><br>
                                            @if ($kamar->layanan->satuan == 'per_hari')
                                                Per Hari
                                            @elseif ($kamar->layanan->satuan == 'per_jam')
                                                Per Jam
                                            @elseif ($kamar->layanan->satuan == 'per_orang_kunjungan')
                                                Per Orang/Kunjungan
                                            @elseif ($kamar->layanan->satuan == 'per_bulan')
                                                Per Bulan
                                            @elseif ($kamar->layanan->satuan == 'per_orang_hari')
                                                Per Orang/Hari
                                            @elseif ($kamar->layanan->satuan == 'per_kamar_hari')
                                                Per Kamar/Hari
                                            @elseif ($kamar->layanan->satuan == 'per_kegiatan_hari')
                                                Per Kegiatan/Hari
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <a href="{{ route('bookingId', ['layanan_id' => $kamar->layanan->id]) }}" class="btn btn-primary">Booking
                        Sekarang</a>
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
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
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

        .form-control,
        .form-select {
            border-radius: 0 10px 10px 0;
        }

        .form-select {
            border-radius: 10px;
        }
    </style>

    <!-- Font Awesome (jika belum ada) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</div>
