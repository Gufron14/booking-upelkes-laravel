<div class="container-fluid">
    <!-- Print Button -->
    <div class="no-print mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print"></i> Cetak Laporan
            </button>
            <button onclick="window.close()" class="btn btn-secondary">
                <i class="fas fa-times"></i> Tutup
            </button>
        </div>
        <hr>
    </div>

    <!-- Header -->
    <div class="text-center mb-4">
        <div class="row align-items-center">
            <div class="col-2">
                <!-- Logo bisa ditambahkan di sini -->
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="header-logo" style="display: none;">
            </div>
            <div class="col-8">
                <h3 class="mb-1">UPELKES JABAR</h3>
                <h4 class="mb-1">LAPORAN TRANSAKSI BOOKING</h4>
                <p class="mb-0">
                    Periode: {{ Carbon\Carbon::parse($tanggal_mulai)->format('d F Y') }} - 
                    {{ Carbon\Carbon::parse($tanggal_selesai)->format('d F Y') }}
                </p>
                @if($search)
                    <p class="mb-0 text-muted">Filter: "{{ $search }}"</p>
                @endif
            </div>
            <div class="col-2 text-end">
                <small>
                    Dicetak pada:<br>
                    {{ now()->format('d F Y') }}<br>
                    {{ now()->format('H:i') }} WIB
                </small>
            </div>
        </div>
    </div>

    <!-- Summary -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Transaksi</h5>
                    <h3 class="text-primary">{{ number_format($totalTransaksi) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Total Pendapatan</h5>
                    <h3 class="text-success">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    {{-- <th width="8%">ID Booking</th> --}}
                    <th width="15%">Customer</th>
                    <th width="15%">Layanan</th>
                    <th width="12%">Kamar/Ruang</th>
                    <th width="10%">Check-in</th>
                    <th width="10%">Check-out</th>
                    <th width="8%">Durasi</th>
                    <th width="12%">Total Biaya</th>
                    <th width="5%">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($bookings as $booking)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        {{-- <td>#{{ $booking->id }}</td> --}}
                        <td>
                            <strong>{{ $booking->user->nama }}</strong><br>
                            <small>{{ $booking->user->email }}</small>
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
                        <td>{{ $booking->duration }} hari</td>
                        <td>Rp {{ number_format($booking->calculateTotalCost(), 0, ',', '.') }}</td>
                        <td>Confirmed</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">
                            Tidak ada data transaksi untuk periode yang dipilih
                        </td>
                    </tr>
                @endforelse
            </tbody>
            @if($bookings->count() > 0)
                <tfoot>
                    <tr class="">
                        <th colspan="7" class="text-end">TOTAL:</th>
                        <th>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</th>
                        <th>{{ $totalTransaksi }} transaksi</th>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>

    <!-- Signature Section -->
    <div class="signature-section">
        <div class="row">
            <div class="col-6">
                <div class="text-center">
                    <p>Mengetahui,</p>
                    <p>Kepala UPELKES JABAR</p>
                    <br><br><br>
                    <p>_________________________</p>
                    <p>NIP. </p>
                </div>
            </div>
            <div class="col-6">
                <div class="text-center">
                    <p>{{ now()->format('d F Y') }}</p>
                    <p>Resepsionis</p>
                    <br><br><br>
                    <p>_________________________</p>
                    <p>{{ auth()->user()->nama ?? 'Admin' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="text-center mt-4">
        <small class="text-muted">
            Laporan ini digenerate secara otomatis oleh Sistem Booking UPELKES JABAR
        </small>
    </div>
</div>
