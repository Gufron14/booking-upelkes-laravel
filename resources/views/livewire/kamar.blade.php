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

    </div>

    <!-- Cards Section -->
    <div class="row g-4">
        @forelse($kamarList as $kamar)
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 shadow-sm border-0 position-relative overflow-hidden">
                    <!-- Badge Kategori -->
                    <div class="position-absolute top-0 start-0 z-3 m-3">
                        <span
                            class="badge bg-primary">
                            <i class="fas fa-building me-1"></i>
                            {{ ucfirst($kamar->layanan->nama_layanan) }}
                        </span>
                    </div>

                    <!-- Gambar -->
                    <div class="position-relative">
                        @if ($kamar->gambarRuangs->count() > 0)
                            <img src="{{ asset('storage/' . $kamar->gambarRuangs->first()->path) }}"
                                class="card-img-top" alt="{{ $kamar->nomor_kamar }}"
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
                        <h5 class="card-title fw-bold text-dark mb-2">Kamar Nomor {{ $kamar->nomor_kamar }}</h5>

                        <!-- Deskripsi -->
                        <p class="card-text text-muted mb-3" style="font-size: 0.9rem;">
                            {{ Str::limit($kamar->layanan->deskripsi, 100) }}
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

                        <!-- Kamar Info -->
                        <div class="mb-3">
                            <small class="text-muted fw-semibold">Nomor Kamar:</small>
                            <div class="d-flex flex-wrap gap-1 mt-1">
                                <span class="badge bg-light text-dark border">
                                    {{ $kamar->nomor_kamar }}
                                </span>
                            </div>
                        </div>

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
                                <button class="btn btn-success btn-sm"
                                    wire:click="openCartModal({{ $kamar->id }})">
                                    <i class="fas fa-cart-plus me-1"></i>
                                    Keranjang
                                </button>

{{-- 
                                @if ($kamar->kamar->count() > 0 || $kamar->ruang->count() > 0)                                    
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
                                    @if ($kamarDetail->gambarRuangs->count() > 0)
                                        <img src="{{ asset('storage/' . $kamarDetail->gambarRuangs->first()->path) }}"
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
                                        {{-- <div class="col-6">
                                            <strong>Kategori:</strong><br>
                                            <span
                                                class="badge bg-primary">{{ ucfirst($kamarDetail->layanan->kategori) }}</span>
                                        </div> --}}
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
                                        @if ($kamarDetail->layanan->satuan == 'per_hari')
                                        Per Hari
                                        @elseif ($kamarDetail->layanan->satuan == 'per_jam')
                                        Per Jam
                                        @elseif ($kamarDetail->layanan->satuan == 'per_orang_kunjungan')
                                        Per Orang/Kunjungan
                                        @elseif ($kamarDetail->layanan->satuan == 'per_bulan')
                                        Per Bulan
                                        @elseif ($kamarDetail->layanan->satuan == 'per_orang_hari')
                                        Per Orang/Hari
                                        @elseif ($kamarDetail->layanan->satuan == 'per_kamar_hari')
                                        Per Kamar/Hari
                                        @elseif ($kamarDetail->layanan->satuan == 'per_kegiatan_hari')
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
                    @if ($selectedkamar)
                        @php
                            $kamarDetail = $kamarList->find($selectedkamar);
                        @endphp
                        @if ($kamarDetail)
                            <a href="{{ route('bookingId', ['layanan_id' => $kamarDetail->layanan->id]) }}" class="btn btn-primary">Booking
                                Sekarang</a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Cart -->
    @if($showCartModal)
    <div class="modal fade show" style="display: block;" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Tambah ke Keranjang</h5>
                    <button type="button" class="btn-close" wire:click="$set('showCartModal', false)"></button>
                </div>
                <div class="modal-body">
                    @if($selectedKamarForCart)
                        @php
                            $selectedKamar = $kamarList->find($selectedKamarForCart);
                        @endphp
                        @if($selectedKamar)
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    @if($selectedKamar->layanan->gambar->count() > 0)
                                        <img src="{{ asset('storage/' . $selectedKamar->layanan->gambar->first()->path) }}"
                                            class="img-fluid rounded" alt="{{ $selectedKamar->layanan->nama_layanan }}">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center rounded"
                                            style="height: 150px;">
                                            <i class="fas fa-image fa-3x text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-8">
                                    <h5 class="fw-bold">{{ $selectedKamar->layanan->nama_layanan }}</h5>
                                    <p class="text-muted">Kamar: {{ $selectedKamar->nomor_kamar }}</p>
                                    <p class="mb-1"><strong>Kapasitas:</strong> {{ $selectedKamar->layanan->kapasitas }} orang</p>
                                    <p class="mb-1"><strong>Tarif:</strong> Rp {{ number_format($selectedKamar->layanan->tarif, 0, ',', '.') }} {{ $selectedKamar->layanan->satuan_label }}</p>
                                </div>
                            </div>

                            <form wire:submit="addToCart">
                                <!-- Data Kegiatan -->
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0"><i class="fas fa-calendar me-2"></i>Data Kegiatan</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Nama Kegiatan <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" wire:model="namaKegiatan" placeholder="Masukkan nama kegiatan">
                                                @error('namaKegiatan') <div class="text-danger small">{{ $message }}</div> @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Jumlah Orang <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" wire:model="jumlahOrang" min="1" max="{{ $selectedKamar->layanan->kapasitas }}">
                                                @error('jumlahOrang') <div class="text-danger small">{{ $message }}</div> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Data Tanggal/Waktu -->
                                @if($selectedKamar->layanan->requiresDateRange() || $selectedKamar->layanan->requiresSingleDate() || $selectedKamar->layanan->requiresTimeSelection())
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-success text-white">
                                        <h6 class="mb-0"><i class="fas fa-clock me-2"></i>Data Tanggal & Waktu</h6>
                                    </div>
                                    <div class="card-body">
                                        @if($selectedKamar->layanan->requiresDateRange())
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Tanggal Check-in <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" wire:model="tanggalCheckin" min="{{ date('Y-m-d') }}">
                                                @error('tanggalCheckin') <div class="text-danger small">{{ $message }}</div> @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Tanggal Check-out <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" wire:model="tanggalCheckout" min="{{ $tanggalCheckin ?: date('Y-m-d') }}">
                                                @error('tanggalCheckout') <div class="text-danger small">{{ $message }}</div> @enderror
                                            </div>
                                        </div>
                                        @elseif($selectedKamar->layanan->requiresSingleDate())
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control" wire:model="tanggalCheckin" min="{{ date('Y-m-d') }}">
                                                @error('tanggalCheckin') <div class="text-danger small">{{ $message }}</div> @enderror
                                            </div>
                                        </div>
                                        @endif

                                        @if($selectedKamar->layanan->requiresTimeSelection())
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                                                <input type="time" class="form-control" wire:model="jamMulai">
                                                @error('jamMulai') <div class="text-danger small">{{ $message }}</div> @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                                                <input type="time" class="form-control" wire:model="jamSelesai">
                                                @error('jamSelesai') <div class="text-danger small">{{ $message }}</div> @enderror
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endif

                                <!-- Data Diri -->
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="card border-0 shadow-sm mb-4">
                                            <div class="card-header bg-info text-white">
                                                <h6 class="mb-0"><i class="fas fa-user me-2"></i>Data Diri</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" wire:model="nama" placeholder="Masukkan nama lengkap">
                                                    @error('nama') <div class="text-danger small">{{ $message }}</div> @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                                    <input type="email" class="form-control" wire:model="email" placeholder="Masukkan email">
                                                    @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Nomor HP <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" wire:model="no_hp" placeholder="Masukkan nomor HP">
                                                    @error('no_hp') <div class="text-danger small">{{ $message }}</div> @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Alamat <span class="text-danger">*</span></label>
                                                    <textarea class="form-control" wire:model="alamat" rows="3" placeholder="Masukkan alamat lengkap"></textarea>
                                                    @error('alamat') <div class="text-danger small">{{ $message }}</div> @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Data Instansi -->
                                    <div class="col-lg-6">
                                        <div class="card border-0 shadow-sm mb-4">
                                            <div class="card-header bg-warning text-dark">
                                                <h6 class="mb-0"><i class="fas fa-building me-2"></i>Data Instansi</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Nama Instansi <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" wire:model="nama_instansi" placeholder="Masukkan nama instansi">
                                                    @error('nama_instansi') <div class="text-danger small">{{ $message }}</div> @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Jabatan di Instansi <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" wire:model="jabatan_instansi" placeholder="Masukkan jabatan di instansi">
                                                    @error('jabatan_instansi') <div class="text-danger small">{{ $message }}</div> @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Alamat Instansi <span class="text-danger">*</span></label>
                                                    <textarea class="form-control" wire:model="alamat_instansi" rows="3" placeholder="Masukkan alamat instansi lengkap"></textarea>
                                                    @error('alamat_instansi') <div class="text-danger small">{{ $message }}</div> @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @endif
                    @endif
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" wire:click="$set('showCartModal', false)">Batal</button>
                    <button type="button" class="btn btn-success" wire:click="addToCart">
                        <i class="fas fa-cart-plus me-1"></i>
                        Tambah ke Keranjang
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif

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