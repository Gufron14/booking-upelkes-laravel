<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Daftar Customer</h2>
        <a href="{{ route('daftar.layanan') }}" class="btn btn-primary rounded-pill px-4">
            <i class="fas fa-arrow-left me-1"></i>
            Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row g-3 mb-3">
                <div class="col">
                    <input type="text" class="form-control" placeholder="Cari nama atau email..."
                        wire:model.debounce.500ms="search">
                </div>
                <div class="col">
                    <input type="date" class="form-control" wire:model.lazy="date_from">
                </div>
                <div class="col">
                    <input type="date" class="form-control" wire:model.lazy="date_to">
                </div>
                <div class="col">
                    <button type="button" class="btn btn-secondary w-100" wire:click="resetFilters">
                        Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <td>#</td>
                    <td>Customer</td>
                    <td>No. Telepon</td>
                    <td>Alamat</td>
                    <td>Aksi</td>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-semibold">{{ $user->nama }}</span>
                                <small class="text-muted">{{ $user->email }}</small>
                            </div>
                        </td>
                        <td>{{ $user->no_hp }}</td>
                        <td>{{ $user->alamat }}</td>
                        <td>
                            <button class="btn btn-danger rounded-pill" wire:click="delete({{ $user->id }})"
                                wire:confirm="Apakah Anda yakin ingin menghapus customer ini?">
                                <i class="fas fa-trash me-1"></i>
                                Hapus
                            </button>
                        </td>
                    @empty
                        <td colspan="6" class="text-center text-muted">
                            Belum ada Customer.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
