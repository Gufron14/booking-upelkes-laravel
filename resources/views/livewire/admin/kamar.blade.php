<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Kelola Kamar</h2>

        {{-- Button Tambah Layanan --}}
        {{-- <a href="{{ route('layanan.create') }}" class="btn btn-primary rounded-pill px-4">
            <i class="fas fa-plus me-1"></i>
            Tambah Layanan
        </a> --}}
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
                <div class="col">
                    <button class="btn {{ $editingId ? 'btn-success' : 'btn-primary' }} mt-4" type="submit" 
                            wire:click="{{ $editingId ? 'update()' : 'save()' }}">
                        {{ $editingId ? 'Update' : 'Tambah' }}
                    </button>
                    @if($editingId)
                        <button class="btn btn-secondary mt-4 ms-2" type="button" wire:click="cancelEdit()">
                            Batal
                        </button>
                    @endif
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
                            <th>Status</th>
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
                                    <span
                                        class="badge 
                                        @if ($item->status == 'tersedia') bg-success
                                        @else
                                            bg-primary @endif
                                        ">{{ $item->status }}</span>
                                </td>
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
