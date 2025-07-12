<div>
    <div class="d-flex justify-content-between align-items-center mb-4 gap-4">
        <div class="col">
            <h2 class="fw-bold">Kelola Layanan</h2>
        </div>
        <div class="col">
            {{-- Search Bar --}}
            <div class="input-group">
                <input type="text" class="form-control rounded-5" placeholder="Cari layanan" wire:model.live="search">
            </div>
        </div>
        <div class="col">
            {{-- Filter Kategori --}}
            <select class="form-select rounded-5" wire:model.live="kategoriFilter">
                <option value="">Semua Kategori</option>
                <option value="umum">Umum</option>
                <option value="pemerintah">Pemerintah</option>
            </select>
        </div>
        <div class="col text-end">
            {{-- Button Tambah Layanan --}}
            <a href="{{ route('layanan.create') }}" class="btn btn-primary rounded-pill px-4">
                <i class="fas fa-plus me-1"></i>
                Tambah Layanan
            </a>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    <div class="row g-4">
        @foreach ($this->featuredLayanan as $layanan)
            <div class="col-lg-4 col-md-6">
                <div class="service-card card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="position-relative">
                        @if ($layanan->gambar->count() > 0)
                            <img src="{{ asset('storage/' . $layanan->gambar->first()->path) }}" class="card-img-top"
                                alt="{{ $layanan->nama_layanan }}" style="height: 250px; object-fit: cover;">
                        @else
                            <div class="bg-gradient-primary d-flex align-items-center justify-content-center text-white"
                                style="height: 250px;">
                                <div class="text-center">
                                    <i class="fas fa-image fa-4x mb-3 opacity-50"></i>
                                    <p class="mb-0">{{ $layanan->nama_layanan }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Category Badge -->
                        <div class="position-absolute top-0 start-0 m-3">
                            <span
                                class="badge {{ $layanan->kategori == 'pemerintah' ? 'bg-success' : 'bg-primary' }} px-3 py-2 rounded-pill">
                                {{ ucfirst($layanan->kategori) }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <h5 class="card-title fw-bold mb-3">{{ $layanan->nama_layanan }}</h5>
                        <p class="card-text text-muted mb-3">
                            {{ Str::limit($layanan->deskripsi, 100) }}
                        </p>

                        <!-- Service Info -->
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <small class="text-muted d-flex align-items-center">
                                    <i class="fas fa-users text-primary me-2"></i>
                                    {{ $layanan->kapasitas ?? 'N/A' }} orang
                                </small>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-flex align-items-center">
                                    <i class="fas fa-clock text-warning me-2"></i>
                                    {{ $layanan->satuan }}
                                </small>
                            </div>
                        </div>

                        <!-- Facilities -->
                        @if ($layanan->fasilitas->count() > 0)
                            <div class="mb-3">
                                <small class="text-muted fw-semibold">Fasilitas:</small>
                                <div class="d-flex flex-wrap gap-1 mt-1">
                                    @foreach ($layanan->fasilitas->take(3) as $fasilitas)
                                        <span class="badge bg-light text-dark border">
                                            {{ $fasilitas->nama }}
                                        </span>
                                    @endforeach
                                    @if ($layanan->fasilitas->count() > 3)
                                        <span class="badge bg-secondary">
                                            +{{ $layanan->fasilitas->count() - 3 }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Price and Action -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="text-primary fw-bold mb-0">
                                    Rp {{ number_format($layanan->tarif, 0, ',', '.') }}
                                </h5>
                                <small class="text-muted">{{ $layanan->satuan }}</small>
                            </div>
                            <a href="{{ route('layanan.edit', $layanan->id) }}"
                                class="btn btn-primary rounded-pill px-4">
                                <i class="fas fa-arrow-right me-1"></i>
                                Edit
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>

    <!-- Smooth Scroll Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Scroll indicator click
            document.querySelector('.scroll-indicator')?.addEventListener('click', function() {
                document.querySelector('#layanan').scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</div>
