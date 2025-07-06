<div>
    <section class="vh-100">
        <div class="container py-4 h-100">
            <div class="row d-flex align-items-center justify-content-center h-100">
                <div class="col-md-8 col-lg-7 col-xl-6">
                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/draw2.svg"
                        class="img-fluid" alt="Phone image">
                </div>
                <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
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
                    
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <form wire:submit.prevent="register">
                                <h2 class="mb-4 text-center">Daftar</h2>
                                
                                <!-- Nama input -->
                                <div class="form-floating mb-3">
                                    <input type="text" 
                                           id="nama" 
                                           class="form-control form-control-lg @error('nama') is-invalid @enderror" 
                                           wire:model="nama"
                                           placeholder="Masukkan nama lengkap" />
                                    <label class="form-label" for="nama">Nama Lengkap</label>
                                    @error('nama')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
        
                                <!-- Email input -->
                                <div class="form-floating mb-3">
                                    <input type="email" 
                                           id="email" 
                                           class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                           wire:model="email"
                                           placeholder="Masukkan email Anda" />
                                    <label class="form-label" for="email">Email address</label>
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
        
                                <!-- Password input -->
                                <div class="form-floating mb-3">
                                    <input type="password" 
                                           id="password" 
                                           class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                           wire:model="password"
                                           placeholder="Masukkan password" />
                                    <label class="form-label" for="password">Password</label>
                                    @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
        
                                <!-- Confirm Password input -->
                                <div class="form-floating mb-3">
                                    <input type="password" 
                                           id="password_confirmation" 
                                           class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror" 
                                           wire:model="password_confirmation"
                                           placeholder="Konfirmasi password" />
                                    <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
                                    @error('password_confirmation')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
        
                                <!-- No HP input -->
                                <div class="form-floating mb-3">
                                    <input type="number" 
                                           id="no_hp" 
                                           class="form-control form-control-lg @error('no_hp') is-invalid @enderror" 
                                           wire:model="no_hp"
                                           placeholder="Masukkan nomor HP (opsional)" />
                                    <label class="form-label" for="no_hp">Nomor HP</label>
                                    @error('no_hp')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
        
                                <!-- Alamat input -->
                                <div class="form-floating mb-4">
                                    <textarea id="alamat" 
                                              class="form-control @error('alamat') is-invalid @enderror" 
                                              wire:model="alamat"
                                              rows="3"
                                              placeholder="Masukkan alamat (opsional)"></textarea>
                                    <label class="form-label" for="alamat">Alamat</label>
                                    @error('alamat')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
        
                                <!-- Submit button -->
                                <button type="submit" 
                                        class="btn btn-primary btn-lg btn-block w-100 mb-3"
                                        wire:loading.attr="disabled">
                                    <span wire:loading.remove>Daftar</span>
                                    <span wire:loading>
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        Loading...
                                    </span>
                                </button>
        
                                <div class="text-center">
                                    <p>Sudah punya akun? <a href="{{ route('login') }}" class="text-decoration-none">Login di sini</a></p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
