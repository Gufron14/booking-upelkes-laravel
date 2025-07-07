<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Tambah Layanan</h2>
        <a href="{{ route('layanan') }}" class="btn btn-primary rounded-pill px-4">
            <i class="fas fa-arrow-left me-1"></i>
            Kembali
        </a>
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
                        <td>{{ $user->nama }}</td>
                        <td>{{ $user->no_telepon }}</td>
                        <td>{{ $user->alamat }}</td>
                        <td>
                            <button class="btn btn-danger rounded-pill" wire:click="delete({{ $user->id }})">
                                <i class="fas fa-trash me-1"></i>
                                Hapus
                            </button>
                        </td>
                    @empty
                        <td colspan="6" class="text-center">
                            Belum ada Customer
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
