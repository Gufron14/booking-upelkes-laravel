<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Profil Saya</h4>
                </div>
                <div class="card-body">
                    @if (session()->has('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <form wire:submit.prevent="updateProfil">
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Nama</label>
                                    <input type="text" wire:model="nama" class="form-control" required>
                                    @error('nama')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">NIP</label>
                                    <input type="text" wire:model="nip" class="form-control" disabled>
                                    @error('nip')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Email</label>
                                    <input type="email" wire:model="email" class="form-control bg-light" disabled>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">No HP</label>
                                    <input type="text" wire:model="no_hp" class="form-control">
                                    @error('no_hp')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Alamat</label>
                                    <textarea wire:model="alamat" class="form-control"></textarea>
                                    @error('alamat')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Surat Tugas</label>
                                    <input type="file" wire:model="foto_id_card" class="form-control">
                                    @error('foto_id_card')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Surat Tugas Saat ini</label>
                                    <img src="{{ asset('storage/' . $user->foto_id_card) }}" alt="Foto KTP" class="img-fluid rounded">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary fw-bold btn-lg">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
