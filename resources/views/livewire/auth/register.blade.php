<div>
    <section class="">
        <div class="container py-4">
            {{-- <img src="{{ asset('assets/images/login.png') }}"
                    class="img-fluid" alt="Phone image"> --}}
            <!-- Alert Success -->
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Alert Error -->
            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="card mt-5">
                <div class="card-body p-5">
                    <h2 class="text-center fw-bold mb-5">Daftar Akun</h2>
                    <form wire:submit.prevent="register">
                        <div class="d-flex align-items-center justify-content-center h-100">
                            <div class="col-md-6 col-lg-7 col-xl-6">
                                <!-- Nama input -->
                                <div class="form-floating mb-3">
                                    <input type="text" id="nama"
                                        class="form-control form-control-lg @error('nama') is-invalid @enderror"
                                        wire:model="nama" placeholder="Masukkan nama lengkap" />
                                    <label class="form-label" for="nama">Nama Lengkap</label>
                                    @error('nama')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                {{-- NIP input --}}
                                <div class="form-floating mb-3">
                                    <input type="number" id="nip"
                                        class="form-control form-control-lg @error('nip') is-invalid @enderror"
                                        wire:model="nip" placeholder="Masukkan NIP" />
                                    <label class="form-label" for="nip">NIP</label>
                                    @error('nip')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- No HP input -->
                                <div class="form-floating mb-3">
                                    <input type="number" id="no_hp"
                                        class="form-control form-control-lg @error('no_hp') is-invalid @enderror"
                                        wire:model="no_hp" placeholder="Masukkan nomor HP (opsional)" />
                                    <label class="form-label" for="no_hp">Nomor HP</label>
                                    @error('no_hp')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Alamat input -->
                                <div class="form-floating mb-4">
                                    <textarea id="alamat" class="form-control @error('alamat') is-invalid @enderror" wire:model="alamat" rows="3"
                                        placeholder="Masukkan alamat (opsional)"></textarea>
                                    <label class="form-label" for="alamat">Alamat</label>
                                    @error('alamat')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Foto ID Card input -->
                                <div class="mb-4">
                                    <label for="foto_id_card" class="form-label">Unggah Surat Tugas</label>
                                    <input type="file" id="foto_id_card"
                                        class="form-control @error('foto_id_card') is-invalid @enderror"
                                        wire:model="foto_id_card" accept="image/*" />
                                    @error('foto_id_card')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    {{-- @if ($foto_id_card)
                                        <div class="mt-2">
                                            <img src="{{ $foto_id_card->temporaryUrl() }}" alt="Preview"
                                                class="img-thumbnail" style="max-width: 200px;">
                                        </div>
                                    @endif --}}
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-5 col-xl-5 offset-xl-1">
                                <div class="mb-3">
                                    <label for="jabatan_instansi" class="form-label">Pilih Role</label>
                                    <select id="jabatan_instansi" class="form-select
                                    @error('jabatan_instansi') is-invalid @enderror"
                                    wire:model="jabatan_instansi">
                                        <option value="Peserta Pelatihan">Peserta Pelatihan</option>
                                        <option value="Instansi">Instansi</option>
                                    </select>
                                    @error('jabatan_instansi')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <!-- Email input -->
                                <div class="form-floating mb-3">
                                    <input type="email" id="email"
                                        class="form-control form-control-lg @error('email') is-invalid @enderror"
                                        wire:model="email" placeholder="Masukkan email Anda" />
                                    <label class="form-label" for="email">Email address</label>
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Password input -->
                                <div class="form-floating mb-3">
                                    <input type="password" id="password"
                                        class="form-control form-control-lg @error('password') is-invalid @enderror"
                                        wire:model="password" placeholder="Masukkan password" />
                                    <label class="form-label" for="password">Password</label>
                                    @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Confirm Password input -->
                                <div class="form-floating mb-3">
                                    <input type="password" id="password_confirmation"
                                        class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror"
                                        wire:model="password_confirmation" placeholder="Konfirmasi password" />
                                    <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
                                    @error('password_confirmation')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Submit button -->
                                <button type="submit" class="btn btn-primary btn-lg btn-block w-100 mb-3"
                                    wire:loading.attr="disabled">
                                    <span wire:loading.remove>Daftar</span>
                                    <span wire:loading>
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                        Loading...
                                    </span>
                                </button>

                                <div class="text-center">
                                    <p>Sudah punya akun? <a href="{{ route('login') }}"
                                            class="text-decoration-none">Login di
                                            sini</a></p>
                                </div>


                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </section>
</div>
