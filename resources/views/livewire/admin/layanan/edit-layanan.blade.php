<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Edit Layanan</h2>
        <a href="{{ route('layanan') }}" class="btn btn-primary rounded-pill px-4">
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

    <div class="card">
        <div class="card-body">
            <form wire:submit.prevent="update">
                <div class="d-flex gap-3">
                    <div class="col-md-6">
                        <div class="d-flex gap-5">
                            <div>
                                <label class="form-label">Kategori</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kategori"
                                        id="kategori_pemerintah" value="pemerintah" wire:model="kategori">
                                    <label class="form-check-label" for="kategori_pemerintah">
                                        Pemerintah
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kategori" id="kategori_umum"
                                        value="umum" wire:model="kategori">
                                    <label class="form-check-label" for="kategori_umum">
                                        Umum
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="form-label">Jenis</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jenis" id="jenis_kamar"
                                        value="kamar" wire:model="jenis">
                                    <label class="form-check-label" for="jenis_kamar">
                                        Kamar
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jenis" id="jenis_ruangan"
                                        value="ruangan" wire:model="jenis">
                                    <label class="form-check-label" for="jenis_ruangan">
                                        Ruangan
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label class="form-label">Satuan</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="satuan" id="satuan_jam"
                                        value="per jam" wire:model="satuan">
                                    <label class="form-check-label" for="satuan_jam">
                                        Per Jam
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="satuan" id="satuan_hari"
                                        value="per bulan" wire:model="satuan">
                                    <label class="form-check-label" for="satuan_hari">
                                        Per Bulan
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="satuan" id="satuan_hari"
                                        value="per hari" wire:model="satuan">
                                    <label class="form-check-label" for="satuan_hari">
                                        Per Hari
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="satuan" id="satuan_orang_hari"
                                        value="per orang/hari" wire:model="satuan">
                                    <label class="form-check-label" for="satuan_orang_hari">
                                        Per Orang/hari
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="satuan" id="satuan_orang_hari"
                                        value="per kamar/hari" wire:model="satuan">
                                    <label class="form-check-label" for="satuan_orang_hari">
                                        Per Kamar/hari
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="satuan" id="satuan_hari"
                                        value="per hari/kegiatan" wire:model="satuan">
                                    <label class="form-check-label" for="satuan_hari">
                                        Per Hari/Kegiatan
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="satuan" id="satuan_hari"
                                        value="per orang/kunjungan" wire:model="satuan">
                                    <label class="form-check-label" for="satuan_hari">
                                        Per Orang/Kunjungan
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-5">
                            <div>
                                <label class="form-label">Fasilitas</label>
                                @foreach ($fasilitasList as $fasilitum)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="{{ $fasilitum->id }}"
                                            wire:model="selectedFasilitas">
                                        <label class="form-check-label" for="selectedFasilitas">
                                            {{ $fasilitum->nama }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            {{-- Image Preview --}}
                            <div class="mt-3">
                                @if ($gambar)
                                    <img src="{{ $gambar->temporaryUrl() }}" class="img-thumbnail"
                                        style="max-width: 200px; max-height: 150px;" alt="Preview">
                                    <p class="small text-muted mt-1">Preview Gambar Baru</p>
                                @elseif ($currentImage)
                                    <img src="{{ asset('storage/' . $currentImage) }}" class="img-thumbnail"
                                        style="max-width: 200px; max-height: 150px;" alt="Current Image">
                                    <p class="small text-muted mt-1">Gambar Saat Ini</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="gambar" class="form-label">Gambar Kamar/Ruangan</label>
                            <input type="file" class="form-control @error('gambar') is-invalid @enderror"
                                wire:model="gambar" accept="image/*">
                            @error('gambar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar</small>

                            {{-- Loading indicator for image upload --}}
                            <div wire:loading wire:target="gambar" class="mt-2">
                                <div class="spinner-border spinner-border-sm text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <small class="text-muted ms-2">Mengupload gambar...</small>
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control @error('nama_layanan') is-invalid @enderror"
                                id="nama_layanan" placeholder="Kamar Standard" wire:model="nama_layanan">
                            <label for="nama_layanan">Nama Layanan</label>
                            @error('nama_layanan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Conditional Input based on Jenis --}}
                        <div class="mb-3">
                            {{-- Jika Jenis yang dipilih adalah Kamar, Tampilkan Nomor Kamar --}}
                            @if ($jenis === 'kamar')
                                <div class="form-floating">
                                    <input type="text"
                                        class="form-control @error('nomor_kamar') is-invalid @enderror"
                                        id="nomor_kamar" placeholder="101" wire:model="nomor_kamar">
                                    <label for="nomor_kamar">Nomor Kamar</label>
                                    @error('nomor_kamar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                            {{-- Jika Jenis yang dipilih adalah Ruangan, Tampilkan Kode Ruangan --}}
                            @if ($jenis === 'ruangan')
                                <div class="form-floating">
                                    <input type="text"
                                        class="form-control @error('kode_ruang') is-invalid @enderror" id="kode_ruang"
                                        placeholder="AUD-001" wire:model="kode_ruang">
                                    <label for="kode_ruang">Kode Ruangan</label>
                                    @error('kode_ruang')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif
                        </div>

                        <div class="form-floating mb-3">
                            <input type="number" class="form-control @error('tarif') is-invalid @enderror"
                                id="tarif" placeholder="100000" wire:model="tarif">
                            <label for="tarif">Tarif (Rp)</label>
                            @error('tarif')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control @error('kapasitas') is-invalid @enderror"
                                id="kapasitas" placeholder="10" wire:model="kapasitas">
                            <label for="kapasitas">Kapasitas (Orang)</label>
                            @error('kapasitas')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-floating">
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" placeholder="Tuliskan Deskripsi di sini"
                                rows="4" wire:model="deskripsi"></textarea>
                            <label for="deskripsi">Deskripsi</label>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="text-end float-right">
                    <button type="submit" class="btn btn-primary fw-bold mt-5 float-right" 
                            wire:loading.attr="disabled">
                        <span wire:loading.remove>Perbarui</span>
                        <span wire:loading>
                            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                            Memperbarui...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
