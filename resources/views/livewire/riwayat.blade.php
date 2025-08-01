<div class="container">
    {{-- Alert Success Session --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Alert Error Session --}}
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-4" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif    

    {{-- Header Section --}}
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="fw-bold text-primary mb-1">
                            <i class="fa-solid fa-clock-rotate-left"></i>Riwayat Booking
                        </h2>
                        <p class="text-muted mb-0">Kelola dan pantau semua booking Anda</p>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-info fs-6">Total: {{ $bookings->count() }} Booking</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Booking Cards --}}
        @if($bookings->count() > 0)
            <div class="row g-4">
                @foreach($bookings as $booking)
                    <div class="col-12">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body p-4">
                                <div class="row align-items-center">
                                    {{-- Booking Info --}}
                                    <div class="col-lg-8">
                                        <div class="row">
                                            {{-- Room/Service Image --}}
                                            <div class="col-md-3">
                                                <div class="position-relative">
                                                    @if($booking->kamar && $booking->kamar->gambar)
                                                        <img src="{{ asset('storage/' . $booking->kamar->gambar) }}" 
                                                             alt="{{ $booking->kamar->nama_kamar }}" 
                                                             class="img-fluid rounded-3 w-100" 
                                                             style="height: 120px; object-fit: cover;">
                                                    @elseif($booking->ruang && $booking->ruang->gambar)
                                                        <img src="{{ asset('storage/' . $booking->ruang->gambar) }}" 
                                                             alt="{{ $booking->ruang->nama_ruang }}" 
                                                             class="img-fluid rounded-3 w-100" 
                                                             style="height: 120px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light rounded-3 d-flex align-items-center justify-content-center" 
                                                             style="height: 120px;">
                                                            <i class="fas fa-image text-muted fa-2x"></i>
                                                        </div>
                                                    @endif
                                                    
                                                    {{-- Status Badge --}}
                                                    <span class="position-absolute top-0 start-0 m-2 badge 
                                                        @if($booking->status == 'confirmed') bg-success
                                                        @elseif($booking->status == 'pending') bg-warning
                                                        @elseif($booking->status == 'cancelled') bg-danger
                                                        @else bg-secondary
                                                        @endif">
                                                        {{ ucfirst($booking->status) }}
                                                    </span>
                                                </div>
                                            </div>

                                            {{-- Booking Details --}}
                                            <div class="col-md-9">
                                                <div class="ms-md-3">
                                                    {{-- Service & Room Name --}}
                                                    <h5 class="fw-bold text-dark mb-2">
                                                    {{ $booking->layanan->nama_layanan ?? 'Layanan Tidak Tersedia' }}
                                                        <span class="badge bg-primary ms-2 fs-6">{{ $booking->layanan->satuan_label ?? '' }}</span>
                                                     </h5>
                                                    
                                                    <div class="row g-3">
                                                        @if($booking->kamar || $booking->ruang)
                                                        <div class="col-sm-6">
                                                        <div class="d-flex align-items-center mb-2">
                                                            @if($booking->kamar)
                                                                    <i class="fas fa-bed text-primary me-2"></i>
                                                                    <small class="text-muted">Kamar:</small>
                                                            @else
                                                                    <i class="fas fa-door-open text-primary me-2"></i>
                                                                        <small class="text-muted">Ruang:</small>
                                                                     @endif
                                                                 </div>
                                                                 <p class="fw-semibold mb-0">
                                                                     {{ $booking->kamar->nomor_kamar ?? $booking->ruang->kode_ruang ?? 'Tidak Tersedia' }}
                                                                 </p>
                                                             </div>
                                                         @endif
                                                        
                                                        <div class="col-sm-6">
                                                        <div class="d-flex align-items-center mb-2">
                                                        <i class="fas fa-calendar text-primary me-2"></i>
                                                        <small class="text-muted">
                                                                @if($booking->layanan->satuan === 'per_jam')
                                                                    Tanggal & Waktu:
                                                            @elseif($booking->layanan->satuan === 'per_orang_kunjungan')
                                                                    Tanggal Kunjungan:
                                                                @else
                                                                        Periode:
                                                                     @endif
                                                                 </small>
                                                             </div>
                                                             @if($booking->layanan->satuan === 'per_jam')
                                                                 <p class="fw-semibold mb-0">
                                                                     {{ $booking->formatted_checkin }}
                                                                 </p>
                                                                 @if($booking->jam_mulai && $booking->jam_selesai)
                                                                     <small class="text-muted">
                                                                         {{ $booking->jam_mulai }} - {{ $booking->jam_selesai }}
                                                                         @php
                                                                             try {
                                                                                 $jamMulaiStr = is_object($booking->jam_mulai) ? $booking->jam_mulai->format('H:i') : $booking->jam_mulai;
                                                                                 $jamSelesaiStr = is_object($booking->jam_selesai) ? $booking->jam_selesai->format('H:i') : $booking->jam_selesai;
                                                                                 $jamMulai = \Carbon\Carbon::parse($booking->tanggal_checkin . ' ' . $jamMulaiStr);
                                                                                 $jamSelesai = \Carbon\Carbon::parse($booking->tanggal_checkin . ' ' . $jamSelesaiStr);
                                                                                 $totalJam = $jamMulai->diffInHours($jamSelesai);
                                                                                 echo "({$totalJam} jam)";
                                                                             } catch (\Exception $e) {
                                                                                 echo "";
                                                                             }
                                                                         @endphp
                                                                     </small>
                                                                 @endif
                                                             @elseif($booking->layanan->satuan === 'per_orang_kunjungan')
                                                                 <p class="fw-semibold mb-0">
                                                                     {{ $booking->formatted_checkin }}
                                                                 </p>
                                                             @else
                                                                 <p class="fw-semibold mb-0">
                                                                     {{ $booking->formatted_checkin }} - {{ $booking->formatted_checkout }}
                                                                 </p>
                                                                 <small class="text-muted">({{ $booking->duration }} hari)</small>
                                                             @endif
                                                             </div>

                                                             {{-- Person Count for applicable satuan types --}}
                                                             @if($booking->layanan->requiresPersonCount() && $booking->jumlah_orang)
                                                             <div class="col-sm-6">
                                                             <div class="d-flex align-items-center mb-2">
                                                                     <i class="fas fa-users text-primary me-2"></i>
                                                                     <small class="text-muted">Jumlah Orang:</small>
                                                             </div>
                                                                 <p class="fw-semibold mb-0">{{ $booking->jumlah_orang }} orang</p>
                                                                 </div>
                                                         @endif
                                                         
                                                         <div class="col-sm-6">
                                                             <div class="d-flex align-items-center mb-2">
                                                                 <i class="fas fa-money-bill text-primary me-2"></i>
                                                                 <small class="text-muted">Total Biaya:</small>
                                                             </div>
                                                             <p class="fw-bold text-success mb-0 fs-5">
                                                                 Rp {{ number_format($booking->total_biaya, 0, ',', '.') }}
                                                             </p>
                                                             <small class="text-muted">{{ $booking->layanan->satuan_label ?? '' }}</small>
                                                         </div>
                                                        
                                                        <div class="col-sm-6">
                                                            <div class="d-flex align-items-center mb-2">
                                                                <i class="fas fa-credit-card text-primary me-2"></i>
                                                                <small class="text-muted">Status Pembayaran:</small>
                                                            </div>
                                                            @if($booking->payment)
                                                                <span class="badge 
                                                                    @if($booking->payment->status == 'terverifikasi') bg-success
                                                                    @elseif($booking->payment->status == 'pending') bg-warning
                                                                    @else bg-danger
                                                                    @endif">
                                                                    {{ ucfirst($booking->payment->status) }}
                                                                </span>
                                                            @else
                                                                <span class="badge bg-secondary">Belum Bayar</span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    {{-- Catatan --}}
                                                    @if($booking->catatan)
                                                        <div class="mt-3">
                                                            <div class="d-flex align-items-center mb-2">
                                                                <i class="fas fa-sticky-note text-primary me-2"></i>
                                                                <small class="text-muted">Catatan:</small>
                                                            </div>
                                                            <p class="text-muted mb-0 fst-italic">{{ $booking->catatan }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Action Buttons --}}
                                    <div class="col-lg-4">
                                        <div class="text-lg-end mt-3 mt-lg-0">
                                            <div class="d-flex flex-column gap-2">
{{-- Payment Button --}}
@if($booking->status == 'waiting_payment' && (!$booking->payment || $booking->payment->status != 'terverifikasi'))
    <a href="{{ route('payment', $booking->id) }}" 
       class="btn btn-success btn-lg fw-semibold d-flex align-items-center justify-content-center position-relative"
       id="btn-bayar-{{ $booking->id }}">
        <i class="fas fa-credit-card me-2"></i>
        Bayar Sekarang&nbsp;
        (<span id="countdown-{{ $booking->id }}"></span>)
    </a>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if($booking->payment_deadline)
                let deadline{{ $booking->id }} = @json(\Carbon\Carbon::parse($booking->payment_deadline)->format('Y-m-d H:i:s'));
                let countDownDate{{ $booking->id }} = new Date(deadline{{ $booking->id }}.replace(/-/g, '/')).getTime();
                let btnBayar{{ $booking->id }} = document.getElementById('btn-bayar-{{ $booking->id }}');
                let timer{{ $booking->id }} = document.getElementById('countdown-{{ $booking->id }}');
                let x{{ $booking->id }} = setInterval(function() {
                    let now = new Date().getTime();
                    let distance = countDownDate{{ $booking->id }} - now;
                    if (distance > 0) {
                        let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        let seconds = Math.floor((distance % (1000 * 60)) / 1000);
                        timer{{ $booking->id }}.innerHTML = minutes + 'm ' + seconds + 's';
                        btnBayar{{ $booking->id }}.classList.remove('disabled');
                    } else {
                        timer{{ $booking->id }}.innerHTML = 'Expired';
                        btnBayar{{ $booking->id }}.classList.add('disabled');
                        btnBayar{{ $booking->id }}.setAttribute('tabindex', '-1');
                        btnBayar{{ $booking->id }}.setAttribute('aria-disabled', 'true');
                        clearInterval(x{{ $booking->id }});
                    }
                }, 1000);
            @endif
        });
    </script>
@endif

                                                {{-- Booking Date Info --}}
                                                <div class="mt-2">
                                                    <small class="text-muted">
                                                        <i class="fas fa-clock me-1"></i>
                                                        Dibuat: {{ $booking->created_at->format('d M Y, H:i') }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Summary Stats --}}
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card bg-light border-0">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3">
                                <i class="fas fa-chart-bar me-2"></i>Ringkasan Booking
                            </h5>
                            <div class="row text-center">
                                <div class="col">
                                    <div class="p-3">
                                        <h3 class="fw-bold text-warning">{{ $bookings->where('status', 'pending')->count() }}</h3>
                                        <p class="text-muted mb-0">Pending</p>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="p-3">
                                        <h3 class="fw-bold text-success">{{ $bookings->where('status', 'booked')->count() }}</h3>
                                        <p class="text-muted mb-0">Confirmed</p>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="p-3">
                                        <h3 class="fw-bold text-danger">{{ $bookings->where('status', 'cancelled')->count() }}</h3>
                                        <p class="text-muted mb-0">Cancelled</p>
                                    </div>
                                </div>
                                {{-- <div class="col-md-3">
                                    <div class="p-3">
                                        <h3 class="fw-bold text-primary">Rp {{ number_format($bookings->where('status', '!=', 'cancelled')->sum($booking->calculateTotal()), 0, ',', '.') }}</h3>
                                        <p class="text-muted mb-0">Total Biaya</p>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            {{-- Empty State --}}
            <div class="row">
                <div class="col-12">
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-calendar-times text-muted" style="font-size: 4rem;"></i>
                        </div>
                        <h4 class="fw-bold text-muted mb-3">Belum Ada Riwayat Booking</h4>
                        <p class="text-muted mb-4">Anda belum memiliki riwayat booking.</p>
                        {{-- <a href="{{ route('/') }}" class="btn btn-primary btn-lg px-4">
                            <i class="fas fa-plus me-2"></i>
                            Buat Booking Pertama
                        </a> --}}
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Custom Styles --}}
    <style>
        .card {
            transition: all 0.3s ease;
            border-radius: 15px;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
        }
        
        .badge {
            font-size: 0.75rem;
</style>
</div>
