<div>
    <!-- Header Dashboard -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">Dashboard</h2>
                    <p class="text-muted mb-0">Selamat datang di sistem booking Upelkes Jabar</p>
                </div>
                <div class="text-end">
                    <small class="text-muted">{{ now()->format('d F Y, H:i') }}</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="row mb-4">
        <!-- Total Bookings -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Bookings
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalBookings) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Customers -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Customers
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalCustomers) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Layanan -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Layanan
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalLayanan) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-concierge-bell fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Pendapatan -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pendapatan Bulan Ini
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp{{ number_format($totalPendapatan) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Booking dan Fasilitas -->
    <div class="row mb-4">
        <!-- Kalender Booking -->
        <div class="mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Kalender Booking</h6>
                </div>
                <div class="card-body">
                    <div id='calendar'></div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            // Wait for calendar function to be available
                            if (typeof window.initBookingCalendar === 'function') {
                                window.initBookingCalendar({!! json_encode($calendarBookings) !!});
                            } else {
                                // Fallback: wait a bit and try again
                                setTimeout(() => {
                                    if (typeof window.initBookingCalendar === 'function') {
                                        window.initBookingCalendar({!! json_encode($calendarBookings) !!});
                                    }
                                }, 500);
                            }
                        });
                    </script>
                </div>
            </div>
        </div>

    </div>

    <div class="row mb-4">
        <!-- Statistik Fasilitas -->
        <div class="col-xl-4 col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Fasilitas & Ruang</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="border-right">
                                <h4 class="font-weight-bold text-primary">{{ $totalKamar }}</h4>
                                <small class="text-muted">Kamar</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <h4 class="font-weight-bold text-info">{{ $totalRuang }}</h4>
                            <small class="text-muted">Ruang</small>
                        </div>
                        <div class="col-6">
                            <div class="border-right">
                                <h4 class="font-weight-bold text-success">{{ $kamarTersedia }}</h4>
                                <small class="text-muted">Kamar Tersedia</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="font-weight-bold text-secondary">{{ $totalFasilitas }}</h4>
                            <small class="text-muted">Fasilitas</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Statistik Periode -->
        <div class="col-xl-4 col-lg-12 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Booking Periode</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h3 class="font-weight-bold text-success">{{ $bookingHariIni }}</h3>
                        <small class="text-muted">Booking Hari Ini</small>
                    </div>
                    <hr>
                    <div class="text-center">
                        <h3 class="font-weight-bold text-info">{{ $bookingBulanIni }}</h3>
                        <small class="text-muted">Booking Bulan Ini</small>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Tabel dan Chart -->
    <div class="row">
        <!-- Booking Terbaru -->
        <div class="col-md-12 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Booking Terbaru</h6>
                    <a href="{{ route('kelola.booking') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr class="align-middle">
                                    <th>Customer</th>
                                    <th>Layanan</th>
                                    <th>Check-in</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentBookings as $booking)
                                <tr>
                                    <td>{{ $booking->user->nama ?? 'N/A' }}</td>
                                    <td>{{ $booking->layanan->nama_layanan ?? 'N/A' }}</td>
                                    <td>{{ $booking->formatted_checkin }}</td>
                                    <td>
                                        @if($booking->status == 'pending')
                                            <span class="badge text-bg-warning">Pending</span>
                                        @elseif($booking->status == 'booked')
                                            <span class="badge text-bg-success">Confirmed</span>
                                        @else
                                            <span class="badge text-bg-danger">Cancelled</span>
                                        @endif
                                    </td>
                                    <td>Rp {{ number_format($booking->calculateTotal(), 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Tidak ada booking terbaru</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
</div>