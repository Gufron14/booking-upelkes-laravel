<div>
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">Laporan Transaksi</h2>
                    <p class="text-muted mb-0">Laporan transaksi booking dengan status confirmed</p>
                </div>
                <div class="text-end">
                    <small class="text-muted">{{ now()->format('d F Y, H:i') }} WIB</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Transaksi
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalTransaksi) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-receipt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Pendapatan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Laporan</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Search Bar -->
                <div class="col-md-4 mb-3">
                    <label for="search" class="form-label">Pencarian</label>
                    <input type="text" 
                           class="form-control" 
                           id="search"
                           wire:model.live.debounce.300ms="search" 
                           placeholder="Cari nama customer, email, atau ID booking...">
                </div>
                
                <!-- Date Range -->
                <div class="col-md-3 mb-3">
                    <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                    <input type="date" 
                           class="form-control" 
                           id="tanggal_mulai"
                           wire:model.live="tanggal_mulai">
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                    <input type="date" 
                           class="form-control" 
                           id="tanggal_selesai"
                           wire:model.live="tanggal_selesai">
                </div>
                
                <!-- Actions -->
                <div class="col-md-2 mb-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="button" 
                                class="btn btn-secondary btn-sm" 
                                wire:click="resetFilter">
                            <i class="fas fa-undo"></i> Reset
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Print Button -->
            <div class="row">
                <div class="col-12">
                    <button type="button" 
                            class="btn btn-success" 
                            wire:click="cetakLaporan"
                            @if($totalTransaksi == 0) disabled @endif>
                        <i class="fas fa-print me-2"></i>
                        Cetak Laporan
                        @if($tanggal_mulai && $tanggal_selesai)
                            ({{ Carbon\Carbon::parse($tanggal_mulai)->format('d/m/Y') }} - {{ Carbon\Carbon::parse($tanggal_selesai)->format('d/m/Y') }})
                        @endif
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Data Transaksi</h6>
            <div class="d-flex align-items-center">
                <label class="me-2">Show:</label>
                <select wire:model.live="perPage" class="form-select form-select-sm" style="width: auto;">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm table-hover">
                    <thead class="table-light">
                        <tr class="align-middle text-center">
                            <th width="5%">No</th>
                            {{-- <th width="10%">ID Booking</th> --}}
                            <th width="15%" class="text-start">Customer</th>
                            <th width="15%">Layanan</th>
                            <th width="12%">Kamar/Ruang</th>
                            <th width="10%">Check-in</th>
                            <th width="10%">Check-out</th>
                            <th width="8%">Durasi</th>
                            <th width="12%">Total Biaya</th>
                            <th width="8%">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bookings as $booking)
                            <tr class="align-middle">
                                <td class="text-center">{{ ($bookings->currentPage() - 1) * $bookings->perPage() + $loop->iteration }}</td>
                                {{-- <td>
                                    <span class="badge bg-primary">#{{ $booking->id }}</span>
                                </td> --}}
                                <td>
                                    <div>
                                        <strong>{{ $booking->user->nama }}</strong><br>
                                        <small class="text-muted">{{ $booking->user->email }}</small>
                                    </div>
                                </td>
                                <td>{{ $booking->layanan->nama_layanan }}</td>
                                <td>
                                    @if($booking->kamar)
                                        Kamar {{ $booking->kamar->nomor_kamar }}
                                    @elseif($booking->ruang)
                                        {{ $booking->ruang->nama_ruang }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $booking->tanggal_checkin->format('d/m/Y') }}</td>
                                <td>{{ $booking->tanggal_checkout->format('d/m/Y') }}</td>
                                <td class="text-center">{{ $booking->duration }} hari</td>
                                <td>
                                    <strong class="text-success">Rp {{ number_format($booking->calculateTotalCost(), 0, ',', '.') }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-success">Confirmed</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <p>Tidak ada data transaksi yang ditemukan</p>
                                        @if($search || $tanggal_mulai || $tanggal_selesai)
                                            <button type="button" class="btn btn-sm btn-outline-primary" wire:click="resetFilter">
                                                Reset Filter
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($bookings->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Menampilkan {{ $bookings->firstItem() }} sampai {{ $bookings->lastItem() }} 
                        dari {{ $bookings->total() }} hasil
                    </div>
                    <div>
                        {{ $bookings->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Loading Indicator -->
    <div wire:loading class="position-fixed top-50 start-50 translate-middle">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</div>
