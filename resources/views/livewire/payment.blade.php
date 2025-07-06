<div class="container py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center mb-3">
                <a href="{{ route('booking') }}" class="btn btn-outline-secondary me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h2 class="mb-0">Pembayaran</h2>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('booking') }}">Booking</a></li>
                    <li class="breadcrumb-item active">Pembayaran</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Alert Messages -->
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Booking Summary -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-receipt me-2"></i>Detail Booking</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary mb-3">Informasi Layanan</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-muted">Booking ID:</td>
                                    <td class="fw-semibold">#{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Layanan:</td>
                                    <td class="fw-semibold">{{ $booking->layanan->nama_layanan }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Kategori:</td>
                                    <td><span class="badge bg-info">{{ ucfirst($booking->layanan->kategori) }}</span></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Check-in:</td>
                                    <td class="fw-semibold">{{ $booking->formatted_checkin }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Check-out:</td>
                                    <td class="fw-semibold">{{ $booking->formatted_checkout }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Durasi:</td>
                                    <td class="fw-semibold">{{ $booking->duration }} hari</td>
                                </tr>
                                @if($booking->kamar)
                                    <tr>
                                        <td class="text-muted">Kamar:</td>
                                        <td class="fw-semibold">{{ $booking->kamar->nomor_kamar }}</td>
                                    </tr>
                                @endif
                                @if($booking->ruang)
                                    <tr>
                                        <td class="text-muted">Ruang:</td>
                                        <td class="fw-semibold">{{ $booking->ruang->kode_ruang }}</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary mb-3">Data Pemesan</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td class="text-muted">Nama:</td>
                                    <td class="fw-semibold">{{ $booking->user->nama }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Email:</td>
                                    <td class="fw-semibold">{{ $booking->user->email }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">No. HP:</td>
                                    <td class="fw-semibold">{{ $booking->user->no_hp }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Alamat:</td>
                                    <td class="fw-semibold">{{ $booking->user->alamat }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Total Cost -->
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <div class="bg-light p-4 rounded">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h6 class="mb-1">Total Pembayaran</h6>
                                        <small class="text-muted">
                                            Rp {{ number_format($booking->layanan->tarif, 0, ',', '.') }} x {{ $booking->duration }} {{ $booking->layanan->satuan }}
                                        </small>
                                    </div>
                                    <div class="col-md-4 text-md-end">
                                        <h3 class="mb-0 text-primary fw-bold">
                                            Rp {{ number_format($booking->calculateTotalCost(), 0, ',', '.') }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Form -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>Upload Bukti Pembayaran</h5>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="submitPayment">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Metode Pembayaran *</label>
                                <select class="form-select @error('metode_pembayaran') is-invalid @enderror" 
                                        wire:model="metode_pembayaran">
                                    <option value="">Pilih Metode</option>
                                    <option value="transfer">Transfer Bank</option>
                                    <option value="cash">Cash</option>
                                </select>
                                @error('metode_pembayaran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Bukti Transfer *</label>
                                <input type="file" class="form-control @error('bukti_transfer') is-invalid @enderror" 
                                       wire:model="bukti_transfer" accept="image/*">
                                @error('bukti_transfer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Format: JPG, PNG, maksimal 2MB</small>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Keterangan (Opsional)</label>
                                <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                          wire:model="keterangan" rows="3" 
                                          placeholder="Tambahkan keterangan jika diperlukan"></textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Preview Image -->
                            @if($bukti_transfer)
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Preview Bukti Transfer:</label>
                                    <div class="text-center">
                                        <img src="{{ $bukti_transfer->temporaryUrl() }}" 
                                             class="img-fluid rounded border" 
                                             style="max-height: 300px;" alt="Preview">
                                    </div>
                                </div>
                            @endif

                            <div class="col-12">
                                <button type="submit" class="btn btn-success btn-lg w-100" 
                                        wire:loading.attr="disabled">
                                    <span wire:loading.remove>
                                        <i class="fas fa-upload me-2"></i>
                                        Upload Bukti Pembayaran
                                    </span>
                                    <span wire:loading>
                                        <i class="fas fa-spinner fa-spin me-2"></i>
                                        Mengupload...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Payment Instructions -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Pembayaran</h6>
                </div>
                <div class="card-body">
                    <div class="payment-info">
                        <h6 class="fw-bold mb-3">Rekening Tujuan:</h6>
                        
                        <div class="bank-info mb-3 p-3 bg-light rounded">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-university text-primary me-2"></i>
                                <strong>Bank BCA</strong>
                            </div>
                            <div class="ms-4">
                                <div>No. Rek: <strong>1234567890</strong></div>
                                <div>A.n: <strong>UPELKES</strong></div>
                            </div>
                        </div>

                        <div class="bank-info mb-3 p-3 bg-light rounded">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-university text-success me-2"></i>
                                <strong>Bank Mandiri</strong>
                            </div>
                            <div class="ms-4">
                                <div>No. Rek: <strong>0987654321</strong></div>
                                <div>A.n: <strong>UPELKES</strong></div>
                            </div>
                        </div>

                        <hr>

                        <h6 class="fw-bold mb-3">Petunjuk Pembayaran:</h6>
                        <ol class="small">
                            <li>Transfer sesuai nominal yang tertera</li>
                            <li>Simpan bukti transfer</li>
                            <li>Upload bukti transfer di</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
