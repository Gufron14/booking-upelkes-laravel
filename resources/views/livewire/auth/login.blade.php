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
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <form wire:submit.prevent="login">
                                <h2 class="mb-4 text-center">Login</h2>
                                
                                <!-- Email input -->
                                <div class="form-floating mb-4">
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
                                <div class="form-floating mb-4">
                                    <input type="password" 
                                           id="password" 
                                           class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                           wire:model="password"
                                           placeholder="Masukkan password Anda" />
                                    <label class="form-label" for="password">Password</label>
                                    @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
        
                                <!-- Remember me checkbox -->
                                <div class="form-check mb-4">
                                    <input class="form-check-input" type="checkbox" id="remember" wire:model="remember">
                                    <label class="form-check-label" for="remember">
                                        Ingat saya
                                    </label>
                                </div>
        
                                <!-- Submit button -->
                                <button type="submit" 
                                        class="btn btn-primary btn-lg btn-block w-100 mb-3"
                                        wire:loading.attr="disabled">
                                    <span wire:loading.remove>Login</span>
                                    <span wire:loading>
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        Loading...
                                    </span>
                                </button>
        
                                <div class="text-center">
                                    <p>Belum punya akun? <a href="{{ route('register') }}" class="text-decoration-none">Daftar di sini</a></p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
