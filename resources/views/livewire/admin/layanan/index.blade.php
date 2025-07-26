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
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    {{-- <th>Gambar</th> --}}
                    <th>Nama Layanan</th>
                    <th>Kategori</th>
                    {{-- <th>Deskripsi</th> --}}
                    <th>Kapasitas</th>
                    {{-- <th>Satuan</th> --}}
                    <th>Fasilitas</th>
                    <th>Tarif</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($this->featuredLayanan as $layanan)
                    <tr>
                        {{-- <td style="width: 150px;">
                        @if ($layanan->gambar->count() > 0)
                            <img src="{{ asset('storage/' . $layanan->gambar->first()->path) }}" alt="{{ $layanan->nama_layanan }}" class="img-fluid rounded" style="height: 80px; object-fit: cover;">
                        @else
                            <div class="text-muted text-center">
                                <i class="fas fa-image fa-2x"></i><br>
                                Tidak ada gambar
                            </div>
                        @endif
                    </td> --}}
                        <td><strong>{{ $layanan->nama_layanan }}</strong></td>
                        <td>
                            <span class="badge {{ $layanan->kategori == 'pemerintah' ? 'bg-success' : 'bg-primary' }}">
                                {{ ucfirst($layanan->kategori) }}
                            </span>
                        </td>
                        {{-- <td>{{ Str::limit($layanan->deskripsi, 60) }}</td> --}}
                        <td>{{ $layanan->kapasitas ?? 'N/A' }} orang</td>
                        {{-- <td>{{ $layanan->satuan }}</td> --}}
                        <td>
                            @if ($layanan->fasilitas->count() > 0)
                                @foreach ($layanan->fasilitas->take(3) as $fasilitas)
                                    <span class="badge badge-light text-dark border">{{ $fasilitas->nama }}</span>
                                @endforeach
                                @if ($layanan->fasilitas->count() > 3)
                                    <span class="badge bg-secondary">+{{ $layanan->fasilitas->count() - 3 }}</span>
                                @endif
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div>
                                <strong>Rp {{ number_format($layanan->tarif, 0, ',', '.') }}</strong><br>
                                <small class="text-muted">
                                    @if ($layanan->satuan == 'per_hari')
                                        Per Hari
                                    @elseif ($layanan->satuan == 'per_jam')
                                        Per Jam
                                    @elseif ($layanan->satuan == 'per_orang_kunjungan')
                                        Per Orang/Kunjungan
                                    @elseif ($layanan->satuan == 'per_bulan')
                                        Per Bulan
                                    @elseif ($layanan->satuan == 'per_orang_hari')
                                        Per Orang/Hari
                                    @elseif ($layanan->satuan == 'per_kamar_hari')
                                        Per Kamar/Hari
                                    @elseif ($layanan->satuan == 'per_kegiatan_hari')
                                        Per Kegiatan/Hari
                                    @endif
                                </small>
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('layanan.edit', $layanan->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-sm btn-danger" wire:click="deleteLayanan({{ $layanan->id }})"
                                wire:confirm="Apakah Anda yakin ingin menghapus layanan ini?">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-5">
                            <i class="fas fa-exclamation-triangle me-2"></i> Belum ada layanan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
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
