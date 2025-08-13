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
<form wire:submit.prevent="searchCustomer">
    <div class="input-group mb-3">
        <input type="text" class="form-control" placeholder="Cari customer..." wire:model="searchTerm">
        <button class="btn btn-primary" type="submit">
            <i class="fas fa-search"></i>
        </button>
    </div>
</form>
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
                    <td>Instansi</td>
                    <td>Role</td>
                    <td>Surat Tugas</td>
                    <td>Tanggal Daftar</td>
                    {{-- <td>Aksi</td> --}}
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-semibold">{{ $user->nama }}</span>
                                <small class="text-muted">NIP. {{ $user->nip }}</small>
                            </div>
                        </td>
                        <td>{{ $user->no_hp }}</td>
                        <td>{{ $user->alamat }}</td>
                        <td>{{ $user->nama_instansi }}</td>
                        <td>{{ $user->jabatan_instansi }}</td>

                        <td>
                            @if($user->foto_id_card)
                                <img src="{{ asset('storage/' . $user->foto_id_card) }}" 
                                     alt="KTP {{ $user->nama }}" 
                                     class="img-thumbnail" 
                                     style="max-width: 100px; max-height: 100px; cursor: pointer;"
                                     data-bs-toggle="modal" 
                                     data-bs-target="#imageModal{{ $user->id }}">
                            @else
                                <span class="text-muted">Tidak ada foto</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        {{-- <td>
                            <button class="btn btn-danger rounded-pill" wire:click="delete({{ $user->id }})"
                                wire:confirm="Apakah Anda yakin ingin menghapus customer ini?">
                                <i class="fas fa-trash me-1"></i>
                                Hapus
                            </button>
                        </td> --}}
                    @empty
                        <td colspan="8" class="text-center text-muted">
                            Belum ada Customer.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Image Modals -->
    @foreach ($users as $user)
        @if($user->foto_id_card)
            <div class="modal fade" id="imageModal{{ $user->id }}" tabindex="-1" aria-labelledby="imageModalLabel{{ $user->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="imageModalLabel{{ $user->id }}">Surat Tugas {{ $user->nama }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img src="{{ asset('storage/' . $user->foto_id_card) }}" 
                                 alt="KTP {{ $user->nama }}" 
                                 class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
</div>
