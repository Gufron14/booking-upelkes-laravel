<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Receipt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
  </head>
</head>
<body>
    @php
        use App\Models\Booking;
        /** @var Booking $booking */
    @endphp
    
    <div class="container my-5">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-header bg-primary text-white text-center py-4 rounded-top-4">
                <h2 class="mb-0 fw-bold">📄 Booking Receipt</h2>
                <small class="text-white-50">Transaction Summary</small>
            </div>
    
            <div class="card-body p-4">
    
                {{-- User Information --}}
                <div class="mb-4">
                    <h5 class="fw-semibold border-bottom pb-2 mb-3">👤 User Information</h5>
                    <ul class="list-group list-group-flush rounded-3">
                        <li class="list-group-item"><strong>Name:</strong> {{ $booking->user->name }}</li>
                        <li class="list-group-item"><strong>Email:</strong> {{ $booking->user->email }}</li>
                    </ul>
                </div>
    
                {{-- Booking Details --}}
                <div class="mb-4">
                    <h5 class="fw-semibold border-bottom pb-2 mb-3">📅 Booking Details</h5>
                    <ul class="list-group list-group-flush rounded-3">
                        <li class="list-group-item"><strong>Activity:</strong> {{ $booking->nama_kegiatan }}</li>
                        <li class="list-group-item"><strong>Status:</strong> 
                            <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </li>
    
                        @if ($booking->layanan->requiresDateRange())
                            <li class="list-group-item"><strong>Check-in:</strong> {{ $booking->formatted_checkin }}</li>
                            <li class="list-group-item"><strong>Check-out:</strong> {{ $booking->formatted_checkout }}</li>
                            <li class="list-group-item"><strong>Duration:</strong> {{ $booking->duration }} days</li>
                        @elseif($booking->layanan->requiresSingleDate())
                            <li class="list-group-item"><strong>Date:</strong> {{ $booking->formatted_checkin }}</li>
                            @if ($booking->layanan->requiresTimeSelection())
                                <li class="list-group-item"><strong>Time:</strong> {{ $booking->jam_mulai }} - {{ $booking->jam_selesai }}</li>
                                <li class="list-group-item"><strong>Duration:</strong> {{ $booking->duration }} hours</li>
                            @endif
                        @endif
                    </ul>
                </div>
    
                {{-- Service Details --}}
                <div class="mb-4">
                    <h5 class="fw-semibold border-bottom pb-2 mb-3">🛎 Service Details</h5>
                    <ul class="list-group list-group-flush rounded-3">
                        <li class="list-group-item"><strong>Service:</strong> {{ $booking->layanan->nama_layanan }}</li>
                        <li class="list-group-item"><strong>Category:</strong> {{ $booking->layanan->kategori }}</li>
                        <li class="list-group-item"><strong>Rate:</strong> Rp {{ number_format($booking->layanan->tarif, 0, ',', '.') }}
                            {{ $booking->layanan->getSatuanLabelAttribute() }}
                        </li>
                        @if ($booking->layanan->requiresPersonCount())
                            <li class="list-group-item"><strong>Number of People:</strong> {{ $booking->jumlah_orang }}</li>
                        @endif
                    </ul>
                </div>
    
                {{-- Room / Space --}}
                @if ($booking->kamar)
                    <div class="mb-4">
                        <h5 class="fw-semibold border-bottom pb-2 mb-3">🛏 Room Details</h5>
                        <div class="alert alert-info mb-0">
                            <strong>Room Number:</strong> {{ $booking->kamar->nomor_kamar }}
                        </div>
                    </div>
                @elseif($booking->ruang)
                    <div class="mb-4">
                        <h5 class="fw-semibold border-bottom pb-2 mb-3">🏢 Space Details</h5>
                        <div class="alert alert-info mb-0">
                            <strong>Space Code:</strong> {{ $booking->ruang->kode_ruang }}
                        </div>
                    </div>
                @endif
    
                {{-- Payment Summary --}}
                <div class="mb-4">
                    <h5 class="fw-semibold border-bottom pb-2 mb-3">💳 Payment Summary</h5>
                    <div class="p-3 bg-light rounded-3">
                        <p class="mb-1"><strong>Total Cost:</strong> <span class="text-success fw-bold">Rp {{ number_format($booking->total_biaya, 0, ',', '.') }}</span></p>
                        <p class="mb-0"><strong>Payment Deadline:</strong> {{ $booking->payment_deadline->format('d M Y H:i') }}</p>
                    </div>
                </div>
    
                {{-- Notes --}}
                @if ($booking->catatan)
                    <div class="mb-4">
                        <h5 class="fw-semibold border-bottom pb-2 mb-3">📝 Notes</h5>
                        <div class="alert alert-warning mb-0">
                            {{ $booking->catatan }}
                        </div>
                    </div>
                @endif
    
            </div>
        </div>
    </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
</body>
</html>

