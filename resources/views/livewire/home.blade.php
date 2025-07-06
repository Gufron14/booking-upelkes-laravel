<div>
    <!-- Hero Section -->
    <section class="hero-section position-relative overflow-hidden" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh;">
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background-image: url('assets/img/k101.png'); background-size: cover; background-position: center; opacity: 0.1;"></div>
        
        <div class="container position-relative z-2 d-flex align-items-center" style="min-height: 100vh;">
            <div class="row w-100 align-items-center">
                <div class="col-lg-6">
                    <div class="hero-content text-white">
                        <h1 class="display-4 fw-bold mb-4 animate__animated animate__fadeInUp">
                            Selamat Datang di <br>
                            <span class="text-warning">UPELKES</span>
                        </h1>
                        <p class="lead mb-4 animate__animated animate__fadeInUp animate__delay-1s">
                            Sistem booking layanan kesehatan terpadu dengan fasilitas modern dan pelayanan terbaik untuk kebutuhan Anda.
                        </p>
                        <div class="d-flex gap-3 animate__animated animate__fadeInUp animate__delay-2s">
                            <a href="#layanan" class="btn btn-warning btn-lg px-4 py-3 rounded-pill fw-semibold">
                                <i class="fas fa-search me-2"></i>
                                Jelajahi Layanan
                            </a>
                            <a href="#tentang" class="btn btn-outline-light btn-lg px-4 py-3 rounded-pill fw-semibold">
                                <i class="fas fa-info-circle me-2"></i>
                                Pelajari Lebih
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-image text-center animate__animated animate__fadeInRight animate__delay-1s">
                        <div class="position-relative">
                            <div class="bg-white bg-opacity-10 rounded-4 p-4 backdrop-blur">
                                <i class="fas fa-hospital fa-8x text-warning mb-3"></i>
                                <h3 class="text-white fw-bold">Layanan 24/7</h3>
                                <p class="text-white-50">Siap melayani kapan saja</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="position-absolute bottom-0 start-50 translate-middle-x mb-4">
            <div class="scroll-indicator animate__animated animate__bounce animate__infinite">
                <i class="fas fa-chevron-down text-white fa-2x"></i>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card text-center p-4 bg-white rounded-4 shadow-sm h-100">
                        <div class="stat-icon mb-3">
                            <i class="fas fa-bed fa-3x text-primary"></i>
                        </div>
                        <h3 class="fw-bold text-primary mb-1">{{ $totalKamar }}</h3>
                        <p class="text-muted mb-0">Kamar Tersedia</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card text-center p-4 bg-white rounded-4 shadow-sm h-100">
                        <div class="stat-icon mb-3">
                            <i class="fas fa-door-open fa-3x text-success"></i>
                        </div>
                        <h3 class="fw-bold text-success mb-1">{{ $totalRuang }}</h3>
                        <p class="text-muted mb-0">Ruang Fasilitas</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card text-center p-4 bg-white rounded-4 shadow-sm h-100">
                        <div class="stat-icon mb-3">
                            <i class="fas fa-cogs fa-3x text-warning"></i>
                        </div>
                        <h3 class="fw-bold text-warning mb-1">{{ $totalFasilitas }}</h3>
                        <p class="text-muted mb-0">Fasilitas</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card text-center p-4 bg-white rounded-4 shadow-sm h-100">
                        <div class="stat-icon mb-3">
                            <i class="fas fa-users fa-3x text-info"></i>
                        </div>
                        <h3 class="fw-bold text-info mb-1">{{ $kategoriStats['umum'] + $kategoriStats['pemerintah'] }}</h3>
                        <p class="text-muted mb-0">Total Layanan</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Services Section -->
    <section id="layanan" class="py-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <h2 class="display-5 fw-bold text-dark mb-3">Layanan Unggulan</h2>
                    <p class="lead text-muted">Pilihan layanan terbaik dengan fasilitas modern</p>
                    <div class="divider mx-auto bg-primary" style="width: 80px; height: 4px; border-radius: 2px;"></div>
                </div>
            </div>
            
            <div class="row g-4">
                @foreach($featuredLayanan as $layanan)
                <div class="col-lg-4 col-md-6">
                    <div class="service-card card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="position-relative">
                            @if($layanan->gambar->count() > 0)
                                <img src="{{ asset('storage/' . $layanan->gambar->first()->path) }}" 
                                     class="card-img-top" 
                                     alt="{{ $layanan->nama_layanan }}"
                                     style="height: 250px; object-fit: cover;">
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
                                <span class="badge {{ $layanan->kategori == 'pemerintah' ? 'bg-success' : 'bg-primary' }} px-3 py-2 rounded-pill">
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
                            @if($layanan->fasilitas->count() > 0)
                                <div class="mb-3">
                                    <small class="text-muted fw-semibold">Fasilitas:</small>
                                    <div class="d-flex flex-wrap gap-1 mt-1">
                                        @foreach($layanan->fasilitas->take(3) as $fasilitas)
                                            <span class="badge bg-light text-dark border">
                                                {{ $fasilitas->nama }}
                                            </span>
                                        @endforeach
                                        @if($layanan->fasilitas->count() > 3)
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
                                <a href="/kamar" class="btn btn-primary rounded-pill px-4">
                                    <i class="fas fa-arrow-right me-1"></i>
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="text-center mt-5">
                <a href="/kamar" class="btn btn-outline-primary btn-lg px-5 py-3 rounded-pill">
                    <i class="fas fa-th-large me-2"></i>
                    Lihat Semua Layanan
                </a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="tentang" class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="about-content">
                        <h2 class="display-5 fw-bold text-dark mb-4">Tentang UPELKES</h2>
                        <p class="lead text-muted mb-4">
                            Unit Pelayanan Kesehatan (UPELKES) adalah fasilitas kesehatan modern yang menyediakan berbagai layanan kesehatan berkualitas tinggi dengan teknologi terdepan.
                        </p>
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="feature-item d-flex">
                                    <div class="feature-icon me-3">
                                        <i class="fas fa-check-circle fa-2x text-success"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold">Layanan 24/7</h6>
                                        <p class="text-muted mb-0">Siap melayani kapan saja</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="feature-item d-flex">
                                    <div class="feature-icon me-3">
                                        <i class="fas fa-user-md fa-2x text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold">Tenaga Ahli</h6>
                                        <p class="text-muted mb-0">Dokter dan perawat berpengalaman</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="feature-item d-flex">
                                    <div class="feature-icon me-3">
                                        <i class="fas fa-hospital fa-2x text-info"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold">Fasilitas Modern</h6>
                                        <p class="text-muted mb-0">Peralatan medis terkini</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="feature-item d-flex">
                                    <div class="feature-icon me-3">
                                        <i class="fas fa-shield-alt fa-2x text-warning"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold">Keamanan Terjamin</h6>
                                        <p class="text-muted mb-0">Protokol kesehatan ketat</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-image text-center">
                        <div class="position-relative">
                            <div class="bg-primary bg-opacity-10 rounded-4 p-5">
                                <i class="fas fa-heartbeat fa-8x text-primary mb-4"></i>
                                <h4 class="fw-bold text-primary">Kesehatan Anda Prioritas Kami</h4>
                                <p class="text-muted">Melayani dengan sepenuh hati</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <h2 class="display-5 fw-bold text-dark mb-3">Kategori Layanan</h2>
                    <p class="lead text-muted">Layanan yang disesuaikan dengan kebutuhan Anda</p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="category-card card border-0 shadow-lg rounded-4 overflow-hidden h-100">
                        <div class="card-body p-5 bg-gradient-primary text-white">
                            <div class="d-flex align-items-center mb-4">
                                <div class="category-icon me-4">
                                    <i class="fas fa-users fa-4x"></i>
                                </div>
                                <div>
                                    <h3 class="fw-bold mb-2">Layanan Umum</h3>
                                    <p class="mb-0 opacity-75">Terbuka untuk masyarakat umum</p>
                                </div>
                            </div>
                            
                            <div class="row g-3 mb-4">
                                <div class="col-6">
                                    <div class="stat-item text-center">
                                        <h4 class="fw-bold">{{ $kategoriStats['umum'] }}</h4>
                                        <small class="opacity-75">Layanan Tersedia</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-item text-center">
                                        <h4 class="fw-bold">24/7</h4>
                                        <small class="opacity-75">Jam Operasional</small>
                                    </div>
                                </div>
                            </div>
                            
                            <a href="/kamar?kategori=umum" class="btn btn-light btn-lg w-100 rounded-pill">
                                <i class="fas fa-arrow-right me-2"></i>
                                Lihat Layanan Umum
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="category-card card border-0 shadow-lg rounded-4 overflow-hidden h-100">
                        <div class="card-body p-5 bg-gradient-success text-white">
                            <div class="d-flex align-items-center mb-4">
                                <div class="category-icon me-4">
                                    <i class="fas fa-building fa-4x"></i>
                                </div>
                                <div>
                                    <h3 class="fw-bold mb-2">Layanan Pemerintah</h3>
                                    <p class="mb-0 opacity-75">Khusus instansi pemerintah</p>
                                </div>
                            </div>
                            
                            <div class="row g-3 mb-4">
                                <div class="col-6">
                                    <div class="stat-item text-center">
                                        <h4 class="fw-bold">{{ $kategoriStats['pemerintah'] }}</h4>
                                        <small class="opacity-75">Layanan Tersedia</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-item text-center">
                                        <h4 class="fw-bold">VIP</h4>
                                        <small class="opacity-75">Pelayanan Khusus</small>
                                    </div>
                                </div>
                            </div>
                            
                            <a href="/kamar?kategori=pemerintah" class="btn btn-light btn-lg w-100 rounded-pill">
                                <i class="fas fa-arrow-right me-2"></i>
                                Lihat Layanan Pemerintah
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5 bg-primary text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h2 class="display-6 fw-bold mb-3">Siap untuk Booking Layanan?</h2>
                    <p class="lead mb-0">Dapatkan pelayanan kesehatan terbaik dengan sistem booking yang mudah dan cepat.</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="/kamar" class="btn btn-warning btn-lg px-5 py-3 rounded-pill fw-semibold">
                        <i class="fas fa-calendar-plus me-2"></i>
                        Booking Sekarang
                    </a>
                </div>
            </div>
        </div>
    </section>




<!-- CDN Links -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

<!-- Smooth Scroll Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
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