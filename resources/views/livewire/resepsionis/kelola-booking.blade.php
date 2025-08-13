<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Daftar Reservasi</h2>
        <a href="{{ route('dashboard') }}" class="btn btn-primary rounded-pill px-4">
            <i class="fas fa-arrow-left me-1"></i>
            Kembali
        </a>
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

    {{-- Filter Section --}}
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col">
                    <label for="search" class="form-label">Cari</label>
                    <input type="text" class="form-control" id="search" wire:model.live="search"
                        placeholder="Nama customer atau layanan...">
                </div>
                <div class="col">
                    <label for="statusFilter" class="form-label">Status</label>
                    <select class="form-select" id="statusFilter" wire:model.live="statusFilter">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <div class="col">
                    <label for="dateFrom" class="form-label">Tanggal Dari</label>
                    <input type="date" class="form-control" id="dateFrom" wire:model.live="dateFrom">
                </div>
                <div class="col">
                    <label for="dateTo" class="form-label">Tanggal Sampai</label>
                    <input type="date" class="form-control" id="dateTo" wire:model.live="dateTo">
                </div>
                <div class="col d-flex align-items-end">
                    <button type="button" class="btn btn-outline-secondary" wire:click="resetFilters">
                        <i class="fas fa-refresh me-1"></i>Reset Filter
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Booking Table --}}
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Layanan</th>
                            <th class="text-center">Tanggal Checkin</th>
                            <th class="text-center">Tanggal Checkout</th>
                            <th>Total Biaya</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bookings as $booking)
                            <tr class="align-middle">
                                <td>{{ $bookings->firstItem() + $loop->index }}</td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-semibold">{{ $booking->user->nama }}</span>
                                        <small class="text-muted">{{ $booking->user->email }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-semibold">{{ $booking->layanan->nama_layanan }}</span>
                                        <small class="text-muted">
                                            @if ($booking->kamar)
                                                Kamar {{ $booking->kamar->nomor_kamar }}
                                            @elseif($booking->ruang)
                                                Ruang {{ $booking->ruang->kode_ruang }}
                                            @endif
                                        </small>
                                    </div>
                                </td>
                                <td class="text-center">{{ $booking->formatted_checkin }}</td>
                                <td class="text-center">{{ $booking->formatted_checkout }}</td>
                                <td>Rp {{ number_format($booking->calculateTotal(), 0, ',', '.') }}</td>
                                <td>
                                    @if ($booking->status === 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($booking->status === 'booked')
                                        <span class="badge bg-success">Confirmed</span>
                                    @elseif($booking->status === 'cancelled')
                                        <span class="badge bg-danger">Cancelled</span>
                                    @elseif($booking->status === 'completed')
                                        <span class="badge bg-info">Completed</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                            wire:click="viewBookingDetail({{ $booking->id }})" data-bs-toggle="modal"
                                            data-bs-target="#detailModal">
                                            <i class="fas fa-eye me-1"></i>Detail
                                        </button>

                                        @if ($booking->status === 'pending')
                                            <button type="button" class="btn btn-sm btn-success"
                                                wire:click="confirmBooking({{ $booking->id }})"
                                                wire:confirm="Apakah Anda yakin ingin mengkonfirmasi booking ini?">
                                                <i class="fas fa-check me-1"></i>Konfirmasi
                                            </button>

                                            <button type="button" class="btn btn-sm btn-danger"
                                                wire:click="cancelBooking({{ $booking->id }})"
                                                wire:confirm="Apakah Anda yakin ingin membatalkan booking ini?">
                                                <i class="fas fa-times me-1"></i>Batal
                                            </button>
                                        @endif

                                        @if ($booking->status === 'booked')
                                            <a href="{{ route('receipt', $booking->id) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-eye"></i> Receipt
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <p>Tidak ada reservasi yang ditemukan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($bookings->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="pagination-info">
                        <span class="text-muted">
                            Menampilkan {{ $bookings->firstItem() }} sampai {{ $bookings->lastItem() }}
                            dari {{ $bookings->total() }} reservasi
                        </span>
                    </div>
                    <div class="pagination-controls">
                        <nav aria-label="Pagination Navigation">
                            <ul class="pagination pagination-sm mb-0">
                                {{-- Previous Page Link --}}
                                @if ($bookings->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link">
                                            <i class="fas fa-chevron-left"></i>
                                        </span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <button class="page-link" wire:click="previousPage"
                                            wire:loading.attr="disabled">
                                            <i class="fas fa-chevron-left"></i>
                                        </button>
                                    </li>
                                @endif

                                {{-- Pagination Elements --}}
                                @foreach ($bookings->getUrlRange(1, $bookings->lastPage()) as $page => $url)
                                    @if ($page == $bookings->currentPage())
                                        <li class="page-item active">
                                            <span class="page-link">{{ $page }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <button class="page-link" wire:click="gotoPage({{ $page }})"
                                                wire:loading.attr="disabled">
                                                {{ $page }}
                                            </button>
                                        </li>
                                    @endif
                                @endforeach

                                {{-- Next Page Link --}}
                                @if ($bookings->hasMorePages())
                                    <li class="page-item">
                                        <button class="page-link" wire:click="nextPage" wire:loading.attr="disabled">
                                            <i class="fas fa-chevron-right"></i>
                                        </button>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link">
                                            <i class="fas fa-chevron-right"></i>
                                        </span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                </div>
            @else
                <div class="text-center mt-4">
                    <span class="text-muted">Total {{ $bookings->total() }} booking</span>
                </div>
            @endif
        </div>
    </div>

    {{-- Detail Modal --}}
    @if ($selectedBooking)
        <div class="modal fade modal-fullscreen-sm-down" id="detailModal" tabindex="-1"
            aria-labelledby="detailModalLabel" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detailModalLabel">Detail Reservasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            wire:click="closeDetailModal"></button>
                    </div>
                    <div class="modal-body p-5">
                        <div class="row">
                            {{-- KIRI --}}
                            <div class="col-md-6">
                                {{-- Identitas Customer --}}
                                <div class="mb-4">
                                    <h6 class="fw-bold mb-3">Identitas Customer</h6>
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td class="fw-semibold">Nama</td>
                                            <td>: {{ $selectedBooking->user->nama }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Email</td>
                                            <td>: {{ $selectedBooking->user->email }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">No. Telepon</td>
                                            <td>: {{ $selectedBooking->user->no_hp ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Alamat</td>
                                            <td>: {{ $selectedBooking->user->alamat ?? '-' }}</td>
                                        </tr>
                                    </table>
                                </div>

                                {{-- Informasi Instansi --}}
                                <div class="mb-4">
                                    <h6 class="fw-bold mb-3">Informasi Instansi</h6>
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td class="fw-semibold">Instansi</td>
                                            <td>: {{ $selectedBooking->user->nama_instansi ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Role</td>
                                            <td>: {{ $selectedBooking->user->jabatan_instansi ?? '-' }}</td>
                                        </tr>
                                    </table>
                                </div>

                                {{-- Informasi Booking --}}
                                <div class="mb-4">
                                    <h6 class="fw-bold mb-3">Informasi Reservasi</h6>
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td class="fw-semibold">Layanan</td>
                                            <td>: {{ $selectedBooking->layanan->nama_layanan }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Ruang/Kamar</td>
                                            <td>:
                                                @if ($selectedBooking->kamar)
                                                    Kamar {{ $selectedBooking->kamar->nomor_kamar }}
                                                @elseif($selectedBooking->ruang)
                                                    Ruang {{ $selectedBooking->ruang->kode_ruang }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Nama Kegiatan</td>
                                            <td>: {{ $selectedBooking->nama_kegiatan ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Jumlah Orang</td>
                                            <td>: {{ $selectedBooking->jumlah_orang ?? '-' }}</td>
                                        </tr>
                                        @if($selectedBooking->layanan && $selectedBooking->layanan->satuan === 'per_jam')
                                        <tr>
                                            <td class="fw-semibold">Jam Mulai</td>
                                            <td>:
                                                {{ $selectedBooking->jam_mulai ? Carbon\Carbon::parse($selectedBooking->jam_mulai)->format('H:i') : '-' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Jam Selesai</td>
                                            <td>:
                                                {{ $selectedBooking->jam_selesai ? Carbon\Carbon::parse($selectedBooking->jam_selesai)->format('H:i') : '-' }}
                                            </td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td class="fw-semibold">Tanggal Checkin</td>
                                            <td>: {{ $selectedBooking->formatted_checkin }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Tanggal Checkout</td>
                                            <td>: {{ $selectedBooking->formatted_checkout }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Total Biaya</td>
                                            <td class="fw-bold text-success">
                                                : Rp
                                                {{ number_format($selectedBooking->calculateTotal(), 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Deadline Pembayaran</td>
                                            <td>:
                                                {{ $selectedBooking->payment_deadline ? Carbon\Carbon::parse($selectedBooking->payment_deadline)->format('d M Y H:i') : '-' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Status</td>
                                            <td>:
                                                @if ($selectedBooking->status === 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif($selectedBooking->status === 'booked')
                                                    <span class="badge bg-success">Confirmed</span>
                                                @elseif($selectedBooking->status === 'cancelled')
                                                    <span class="badge bg-danger">Cancelled</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            {{-- KANAN --}}
                            <div class="col-md-6">
                                {{-- Bukti Pembayaran --}}
                                <div class="mb-4">
                                    <h6 class="fw-bold mb-3">Bukti Pembayaran</h6>
                                    @if ($selectedBooking->payment && $selectedBooking->payment->bukti_transfer)
                                        <img src="{{ asset('storage/' . $selectedBooking->payment->bukti_transfer) }}"
                                            class="img-fluid img-thumbnail" alt="Bukti Pembayaran"
                                            style="max-height: 300px; cursor: pointer;"
                                            onclick="window.open(this.src, '_blank')">
                                    @else
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            Bukti pembayaran belum diupload
                                        </div>
                                    @endif
                                </div>

                                {{-- Catatan Customer --}}
                                @if ($selectedBooking->catatan)
                                    <div class="mb-4">
                                        <h6 class="fw-bold mb-3">Catatan Customer</h6>
                                        <div class="alert alert-info">
                                            {{ $selectedBooking->catatan }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        @if ($selectedBooking->status === 'pending')
                            <button type="button" class="btn btn-success"
                                wire:click="confirmBooking({{ $selectedBooking->id }})"
                                wire:confirm="Apakah Anda yakin ingin mengkonfirmasi booking ini?"
                                data-bs-dismiss="modal">
                                <i class="fas fa-check me-1"></i> Konfirmasi Reservasi
                            </button>

                            <button type="button" class="btn btn-danger"
                                wire:click="cancelBooking({{ $selectedBooking->id }})"
                                wire:confirm="Apakah Anda yakin ingin membatalkan booking ini?"
                                data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i> Batalkan Reservasi
                            </button>
                        @endif

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            wire:click="closeDetailModal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
