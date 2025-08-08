<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Kelola Kamar</h2>

        <button class="btn btn-primary rounded-pill px-4" wire:click="openModal">
            <i class="fas fa-plus me-1"></i>
            Tambah Kamar
        </button>
    </div>
    {{-- <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header mb-0">
                    <div class="card-title mb-0">
                        <h5>Tambah Kamar</h5>
                    </div>
                </div>
                <div class="card-body">
                    .
                </div>
            </div>
        </div>
        <div class="col"> --}}
    <div class="card">
        <div class="card-header mb-0">
            <div class="card-title mb-0">
                <h5>Daftar Kamar</h5>
            </div>
        </div>
        <div class="card-body">
            <!-- Modal -->
            <div wire:ignore.self class="modal fade" id="kamarModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="kamarModalLabel">
                                @if($editingId) Edit Kamar @else Tambah Kamar @endif
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="closeModal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="d-flex gap-3 mb-4 align-items-center justify-content-center">
                                <div class="col form-group">
                                    <label for="" class="form-label">Layanan</label>
                                    <select name="" id="" class="form-select @error('layanan_id') is-invalid @enderror""
                                        wire:model="layanan_id">
                                        <option value="">-- Pilih Layanan --</option>
                                        @foreach ($layanan as $item)
                                            <option value="{{ $item->id }}">{{ $item->nama_layanan }}</option>
                                        @endforeach
                                    </select>
                                    @error('layanan_id')
                                        <small class="text-danger">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>
                                <div class="col form-group">
                                    <label for="" class="form-label">Nomor Kamar</label>
                                    <input type="text" class="form-control @error('nomor_kamar') is-invalid @enderror"
                                        wire:model='nomor_kamar'>
                                    @error('nomor_kamar')
                                        <small class="text-danger">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label for="images" class="form-label">Gambar Kamar</label>
                                <input type="file" class="form-control" wire:model="images" multiple>
                                @error('images.*')
                                    <small class="text-danger">
                                        {{ $message }}
                                    </small>
                                @enderror
                                
                                @if($editingId && isset($kamar))
                                    @php
                                        $currentKamar = collect($kamar)->firstWhere('id', $editingId);
                                    @endphp
                                    @if($currentKamar && $currentKamar->gambarRuangs && $currentKamar->gambarRuangs->count() > 0)
                                        <div class="mt-3">
                                            <label class="form-label">Gambar Saat Ini:</label>
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($currentKamar->gambarRuangs as $gambar)
                                                    <div class="position-relative">
                                                        <img src="{{ asset('storage/' . $gambar->path) }}" 
                                                             alt="Gambar Kamar" 
                                                             class="img-thumbnail" 
                                                             style="width: 80px; height: 80px; object-fit: cover;">
                                                        <button type="button" 
                                                                class="btn btn-danger btn-sm position-absolute top-0 end-0 rounded-circle p-1"
                                                                style="width: 20px; height: 20px; font-size: 10px;"
                                                                wire:click="deleteImage({{ $gambar->id }})"
                                                                wire:confirm="Hapus gambar ini?">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click="closeModal">Tutup</button>
                            @if($editingId)
                                <button class="btn btn-primary" type="submit" wire:click="update">
                                    Update
                                </button>
                            @else
                                <button class="btn btn-primary" type="submit" wire:click="save">
                                    Simpan
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @if (session()->has('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Layanan</th>
                            <th>Nomor Kamar</th>
                            <th>Gambar</th>
                            {{-- <th>Status</th> --}}
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kamar ?? [] as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->layanan->nama_layanan ?? '-' }}</td>
                                <td>{{ $item->nomor_kamar }}</td>
                                <td>
                                    @if($item->gambarRuangs && $item->gambarRuangs->count() > 0)
                                        <div class="d-flex gap-1">
                                            @foreach($item->gambarRuangs->take(3) as $gambar)
                                                <img src="{{ asset('storage/' . $gambar->path) }}" 
                                                     alt="Gambar Kamar" 
                                                     class="img-thumbnail" 
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            @endforeach
                                            @if($item->gambarRuangs->count() > 3)
                                                <span class="badge bg-secondary align-self-center">+{{ $item->gambarRuangs->count() - 3 }}</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">Tidak ada gambar</span>
                                    @endif
                                </td>
                                {{-- <td>
                                    <span
                                        class="badge 
                                        @if ($item->status == 'tersedia') bg-success
                                        @else
                                            bg-primary @endif
                                        ">{{ $item->status }}</span>
                                </td> --}}
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-warning btn-sm" wire:click="edit({{ $item->id }})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" type="submit"
                                        wire:click="delete({{ $item->id }})"
                                        wire:confirm="Apakah Anda yakin ingin menghapus kamar ini?">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{-- </div>
    </div> --}}
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('showKamarModal', () => {
            const modal = new bootstrap.Modal(document.getElementById('kamarModal'));
            modal.show();
        });

        Livewire.on('hideKamarModal', () => {
            const modal = bootstrap.Modal.getInstance(document.getElementById('kamarModal'));
            if (modal) {
                modal.hide();
            }
        });
    });
</script>