<div class="container py-4">
    <!-- Progress Steps -->
    <div class="row mb-4 mt-5">
        <div class="col-12">
            <div class="progress-steps">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="step {{ $step >= 1 ? 'active' : '' }}">
                        <div class="step-circle">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <span class="step-label">Pilih Layanan & Tanggal</span>
                    </div>
                    <div class="step-line {{ $step >= 2 ? 'active' : '' }}"></div>
                    <div class="step {{ $step >= 2 ? 'active' : '' }}">
                        <div class="step-circle">
                            <i class="fas fa-user"></i>
                        </div>
                        <span class="step-label">Data Diri</span>
                    </div>
                    <div class="step-line {{ $step >= 3 ? 'active' : '' }}"></div>
                    <div class="step {{ $step >= 3 ? 'active' : '' }}">
                        <div class="step-circle">
                            <i class="fas fa-check"></i>
                        </div>
                        <span class="step-label">Konfirmasi</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
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

    <!-- Step 1: Select Service & Date -->
    @if ($step == 1)
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Pilih Layanan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach ($layananList as $layanan)
                                <div class="col-md-6">
                                    <div class="service-option {{ $selectedLayanan == $layanan->id ? 'selected' : '' }}"
                                        wire:click="selectLayanan({{ $layanan->id }})">
                                        <div class="d-flex align-items-center">
                                            @if ($layanan->gambar->count() > 0)
                                                <img src="{{ asset('storage/' . $layanan->gambar->first()->path) }}"
                                                    class="service-thumb me-3" alt="{{ $layanan->nama_layanan }}">
                                            @else
                                                <div
                                                    class="service-thumb bg-light d-flex align-items-center justify-content-center me-3">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">{{ $layanan->nama_layanan }}</h6>
                                                <small class="text-muted">{{ $layanan->kategori }}</small>
                                                <div class="fw-bold text-primary">
                                                    Rp
                                                    {{ number_format($layanan->tarif, 0, ',', '.') }}/{{ $layanan->satuan }}
                                                </div>
                                            </div>
                                            @if ($selectedLayanan == $layanan->id)
                                                <i class="fas fa-check-circle text-success fa-lg"></i>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Date Selection -->
                @if ($selectedLayanan)
                    <div class="card border-0 shadow-sm mt-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-calendar me-2"></i>Pilih Tanggal</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Tanggal Check-in</label>
                                    <input type="date"
                                        class="form-control @error('tanggal_checkin') is-invalid @enderror"
                                        wire:model.live="tanggal_checkin" min="{{ date('Y-m-d') }}">
                                    @error('tanggal_checkin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Tanggal Check-out</label>
                                    <input type="date"
                                        class="form-control @error('tanggal_checkout') is-invalid @enderror"
                                        wire:model.live="tanggal_checkout"
                                        min="{{ $tanggal_checkin ?: date('Y-m-d', strtotime('+1 day')) }}">
                                    @error('tanggal_checkout')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            @if ($tanggal_checkin && $tanggal_checkout)
                                <div class="alert alert-info mt-3">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Durasi:</strong> {{ $totalHari }} hari
                                    <br>
                                    <strong>Estimasi Biaya:</strong> Rp {{ number_format($totalBiaya, 0, ',', '.') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Room/Space Selection -->
                    @if ($tanggal_checkin && $tanggal_checkout)
                        @if ($availableKamar->count() > 0)
                            <div class="card border-0 shadow-sm mt-4">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="mb-0"><i class="fas fa-bed me-2"></i>Pilih Kamar</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        @foreach ($availableKamar as $kamar)
                                            <div class="col-md-4">
                                                <div class="room-option {{ $selectedKamar == $kamar->id ? 'selected' : '' }}"
                                                    wire:click="selectKamar({{ $kamar->id }})">
                                                    <div class="text-center">
                                                        <i class="fas fa-bed fa-2x mb-2"></i>
                                                        <h6>Kamar {{ $kamar->nomor_kamar }}</h6>
                                                        <small class="text-muted">{{ $kamar->status }}</small>
                                                        @if ($selectedKamar == $kamar->id)
                                                            <div class="mt-2">
                                                                <i class="fas fa-check-circle text-success"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($availableRuang->count() > 0 && !$selectedKamar)
                            <div class="card border-0 shadow-sm mt-4">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0"><i class="fas fa-door-open me-2"></i>Pilih Ruang</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        @foreach ($availableRuang as $ruang)
                                            <div class="col-md-4">
                                                <div class="room-option {{ $selectedRuang == $ruang->id ? 'selected' : '' }}"
                                                    wire:click="selectRuang({{ $ruang->id }})">
                                                    <div class="text-center">
                                                        <i class="fas fa-door-open fa-2x mb-2"></i>
                                                        <h6>{{ $ruang->kode_ruang }}</h6>
                                                        @if ($selectedRuang == $ruang->id)
                                                            <div class="mt-2">
                                                                <i class="fas fa-check-circle text-success"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                @endif
            </div>

            <!-- Sidebar Summary -->
            <div class="col-lg-4">
                @if ($selectedLayanan && $layananData)
                    <div class="card border-0 shadow-sm sticky-top">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Ringkasan Booking</h6>
                        </div>
                        <div class="card-body">
                            <div class="booking-summary">
                                <h6 class="fw-bold">{{ $layananData->nama_layanan }}</h6>
                                <p class="text-muted small">{{ $layananData->deskripsi }}</p>

                                @if ($tanggal_checkin && $tanggal_checkout)
                                    <hr>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Check-in:</span>
                                        <span
                                            class="fw-semibold">{{ date('d M Y', strtotime($tanggal_checkin)) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Check-out:</span>
                                        <span
                                            class="fw-semibold">{{ date('d M Y', strtotime($tanggal_checkout)) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Durasi:</span>
                                        <span class="fw-semibold">{{ $totalHari }} hari</span>
                                    </div>

                                    @if ($selectedKamar)
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Kamar:</span>
                                            <span
                                                class="fw-semibold">{{ $availableKamar->find($selectedKamar)->nomor_kamar ?? '' }}</span>
                                        </div>
                                    @endif

                                    @if ($selectedRuang)
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Ruang:</span>
                                            <span
                                                class="fw-semibold">{{ $availableRuang->find($selectedRuang)->kode_ruang ?? '' }}</span>
                                        </div>
                                    @endif

                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-bold">Total:</span>
                                        <span class="fw-bold text-primary">Rp
                                            {{ number_format($totalBiaya, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="d-flex justify-content-end mt-4">
            <button type="button" class="btn btn-primary btn-lg px-4" wire:click="nextStep"
                @if (!$selectedLayanan || !$tanggal_checkin || !$tanggal_checkout) disabled @endif>
                Lanjutkan <i class="fas fa-arrow-right ms-2"></i>
            </button>
        </div>
    @endif

    <!-- Step 2: User Data -->
    @if ($step == 2)
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-user me-2"></i>Data Diri</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama Lengkap *</label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                    wire:model="nama" placeholder="Masukkan nama lengkap">
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    wire:model="email" placeholder="Masukkan email">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nomor HP *</label>
                                <input type="text" class="form-control @error('no_hp') is-invalid @enderror"
                                    wire:model="no_hp" placeholder="Masukkan nomor HP">
                                @error('no_hp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Alamat *</label>
                                <textarea class="form-control @error('alamat') is-invalid @enderror" wire:model="alamat" rows="3"
                                    placeholder="Masukkan alamat lengkap"></textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="d-flex justify-content-between mt-4">
            <button type="button" class="btn btn-outline-secondary btn-lg px-4" wire:click="prevStep">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </button>
            <button type="button" class="btn btn-primary btn-lg px-4" wire:click="nextStep">
                Lanjutkan <i class="fas fa-arrow-right ms-2"></i>
            </button>
        </div>
    @endif

    <!-- Step 3: Confirmation -->
    @if ($step == 3)
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-check me-2"></i>Konfirmasi Booking</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3">Detail Layanan</h6>
                                <table class="table table-borderless">
                                    <tr>
                                        <td>Layanan:</td>
                                        <td class="fw-semibold">{{ $layananData->nama_layanan }}</td>
                                    </tr>
                                    <tr>
                                        <td>Kategori:</td>
                                        <td><span
                                                class="badge bg-primary">{{ ucfirst($layananData->kategori) }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Check-in:</td>
                                        <td class="fw-semibold">{{ date('d M Y', strtotime($tanggal_checkin)) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Check-out:</td>
                                        <td class="fw-semibold">{{ date('d M Y', strtotime($tanggal_checkout)) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Durasi:</td>
                                        <td class="fw-semibold">{{ $totalHari }} hari</td>
                                    </tr>
                                    @if ($selectedKamar)
                                        <tr>
                                            <td>Kamar:</td>
                                            <td class="fw-semibold">
                                                {{ $availableKamar->find($selectedKamar)->nomor_kamar ?? '' }}</td>
                                        </tr>
                                    @endif
                                    @if ($selectedRuang)
                                        <tr>
                                            <td>Ruang:</td>
                                            <td class="fw-semibold">
                                                {{ $availableRuang->find($selectedRuang)->kode_ruang ?? '' }}</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3">Data Pemesan</h6>
                                <table class="table table-borderless">
                                    <tr>
                                        <td>Nama:</td>
                                        <td class="fw-semibold">{{ $nama }}</td>
                                    </tr>
                                    <tr>
                                        <td>Email:</td>
                                        <td class="fw-semibold">{{ $email }}</td>
                                    </tr>
                                    <tr>
                                        <td>No. HP:</td>
                                        <td class="fw-semibold">{{ $no_hp }}</td>
                                    </tr>
                                    <tr>
                                        <td>Alamat:</td>
                                        <td class="fw-semibold">{{ $alamat }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <hr>

                        <!-- Total Cost -->
                        <div class="row">
                            <div class="col-12">
                                <div class="bg-light p-3 rounded">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Total Biaya:</h5>
                                        <h4 class="mb-0 text-primary fw-bold">Rp
                                            {{ number_format($totalBiaya, 0, ',', '.') }}</h4>
                                    </div>
                                    <small class="text-muted">
                                        {{ $layananData->tarif }} x {{ $totalHari }} {{ $layananData->satuan }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="mt-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    Saya menyetujui <a href="#" class="text-primary">syarat dan ketentuan</a>
                                    yang berlaku
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="d-flex justify-content-between mt-4">
            <button type="button" class="btn btn-outline-secondary btn-lg px-4" wire:click="prevStep">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </button>
            <button type="button" class="btn btn-success btn-lg px-4" wire:click="submitBooking">
                <i class="fas fa-credit-card me-2"></i> Lanjut ke Pembayaran
            </button>
        </div>
    @endif


    <!-- Custom CSS -->
    <style>
        .progress-steps {
            margin-bottom: 2rem;
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            position: relative;
        }

        .step-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #e9ecef;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
        }

        .step.active .step-circle {
            background-color: #0d6efd;
            color: white;
        }

        .step-label {
            font-size: 0.9rem;
            font-weight: 500;
            color: #6c757d;
        }

        .step.active .step-label {
            color: #0d6efd;
            font-weight: 600;
        }

        .step-line {
            flex: 1;
            height: 2px;
            background-color: #e9ecef;
            margin: 0 1rem;
            align-self: flex-start;
            margin-top: 25px;
            transition: all 0.3s ease;
        }

        .step-line.active {
            background-color: #0d6efd;
        }

        .service-option {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
        }

        .service-option:hover {
            border-color: #0d6efd;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15);
        }

        .service-option.selected {
            border-color: #0d6efd;
            background-color: #f8f9ff;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15);
        }

        .service-thumb {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }

        .room-option {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
            text-align: center;
        }

        .room-option:hover {
            border-color: #0d6efd;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15);
            transform: translateY(-2px);
        }

        .room-option.selected {
            border-color: #0d6efd;
            background-color: #f8f9ff;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15);
        }

        .booking-summary {
            font-size: 0.9rem;
        }

        .sticky-top {
            top: 2rem;
        }

        @media (max-width: 768px) {
            .step {
                font-size: 0.8rem;
            }

            .step-circle {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }

            .step-line {
                margin-top: 20px;
            }
        }
    </style>

    <!-- Loading Overlay -->
    {{-- <div wire:loading class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
        style="background: rgba(0,0,0,0.5); z-index: 9999;">
        <div class="text-center text-white">
            <div class="spinner-border mb-3" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p>Memproses booking...</p>
        </div>
    </div> --}}

</div>
