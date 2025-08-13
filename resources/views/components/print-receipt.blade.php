<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Receipt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .invoice-box {
            background: #fff;
            padding: 30px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            max-width: 800px;
            margin: auto;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.05);
        }
        .invoice-header {
            border-bottom: 2px solid #0d6efd;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }
        .invoice-title {
            font-weight: bold;
            font-size: 1.5rem;
            color: #0d6efd;
        }
        .table th {
            background-color: #f1f3f5;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background-color: white;
            }
            .invoice-box {
                box-shadow: none;
                border: none;
            }
        }
    </style>
</head>
<body>

@php
    use App\Models\Booking;
    /** @var Booking $booking */
@endphp

<div class="invoice-box mt-5">
    <div class="d-flex justify-content-between align-items-center invoice-header">
        <div>
            <div class="invoice-title">📄 Booking Receipt</div>
            <small class="text-muted">Transaction Summary</small>
        </div>
        <div>
            <button class="btn btn-primary no-print" onclick="window.print()">🖨 Cetak</button>
        </div>
    </div>

    {{-- User Info --}}
    <h6 class="fw-bold mt-4">👤 User Information</h6>
    <table class="table table-bordered">
        <tr><th>Name</th><td>{{ $booking->user->nama }}</td></tr>
        <tr><th>Email</th><td>{{ $booking->user->email }}</td></tr>
    </table>

    {{-- Booking Details --}}
    <h6 class="fw-bold mt-4">📅 Booking Details</h6>
    <table class="table table-bordered">
        <tr><th>Activity</th><td>{{ $booking->nama_kegiatan }}</td></tr>
        <tr>
            <th>Status</th>
            <td>
                <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'danger') }}">
                    {{ ucfirst($booking->status) }}
                </span>
            </td>
        </tr>
        @if ($booking->layanan->requiresDateRange())
            <tr><th>Check-in</th><td>{{ $booking->formatted_checkin }}</td></tr>
            <tr><th>Check-out</th><td>{{ $booking->formatted_checkout }}</td></tr>
            <tr><th>Duration</th><td>{{ $booking->duration }} @if($booking->layanan->satuan === 'per_bulan') months @else days @endif</td></tr>
        @elseif($booking->layanan->requiresSingleDate())
            <tr><th>Date</th><td>{{ $booking->formatted_checkin }}</td></tr>
            @if ($booking->layanan->requiresTimeSelection())
                <tr><th>Time</th><td>{{ $booking->jam_mulai }} - {{ $booking->jam_selesai }}</td></tr>
                <tr><th>Duration</th><td>{{ $booking->duration }} hours</td></tr>
            @endif
        @endif
    </table>

    {{-- Service Details --}}
    <h6 class="fw-bold mt-4">🛎 Service Details</h6>
    <table class="table table-bordered">
        <tr><th>Service</th><td>{{ $booking->layanan->nama_layanan }}</td></tr>
        <tr><th>Category</th><td>{{ $booking->layanan->kategori }}</td></tr>
        <tr>
            <th>Rate</th>
            <td>Rp {{ number_format($booking->layanan->tarif, 0, ',', '.') }} {{ $booking->layanan->getSatuanLabelAttribute() }}</td>
        </tr>
        @if ($booking->layanan->requiresPersonCount())
            <tr><th>Number of People</th><td>{{ $booking->jumlah_orang }}</td></tr>
        @endif
    </table>

    {{-- Room / Space --}}
    @if ($booking->kamar)
        <h6 class="fw-bold mt-4">🛏 Room Details</h6>
        <table class="table table-bordered">
            <tr><th>Room Number</th><td>{{ $booking->kamar->nomor_kamar }}</td></tr>
        </table>
    @elseif($booking->ruang)
        <h6 class="fw-bold mt-4">🏢 Space Details</h6>
        <table class="table table-bordered">
            <tr><th>Space Code</th><td>{{ $booking->ruang->kode_ruang }}</td></tr>
        </table>
    @endif

    {{-- Payment Summary --}}
    <h6 class="fw-bold mt-4">💳 Payment Summary</h6>
    <table class="table table-bordered">
        <tr><th>Total Cost</th><td><strong class="text-success">Rp {{ number_format($booking->total_biaya ?: $booking->calculateTotal(), 0, ',', '.') }}</strong></td></tr>
        <tr><th>Payment Deadline</th><td>{{ $booking->payment_deadline->format('d M Y H:i') }}</td></tr>
    </table>

    {{-- Notes --}}
    @if ($booking->catatan)
        <h6 class="fw-bold mt-4">📝 Notes</h6>
        <table class="table table-bordered">
            <tr><td>{{ $booking->catatan }}</td></tr>
        </table>
    @endif

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
