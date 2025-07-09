<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Kelola Fasilitas</h2>

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
                        <h5>Tambah ruang</h5>
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
                <h5>Daftar Fasilitas</h5>
            </div>
        </div>
        <div class="card-body">
            <div class="d-flex gap-3 mb-4 align-items-center justify-content-center">
                <div class="col form-group">
                    <label for="" class="form-label">Nama Fasilitas</label>
                    <input type="text" class="form-control @error('nama') is-invalid @enderror"
                        wire:model='nama'>
                    @error('nama')
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
                            <th>Nama Fasilitas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($fasilitas ?? [] as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-warning btn-sm" wire:click="edit({{ $item->id }})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" type="submit"
                                        wire:click="delete({{ $item->id }})"
                                        wire:confirm="Apakah Anda yakin ingin menghapus ruang ini?">
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

