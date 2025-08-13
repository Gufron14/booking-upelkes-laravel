<nav class="navbar navbar-expand-lg sticky-top top-0" style="
background-color: #ffbb00;
z-index: 9999;
">
    <div class="container p-1">
        <a class="navbar-brand fw-bold" href="#">
            <img src="{{ asset('assets/img/logo.png') }}" alt="logo Upelkes" width="160" height="">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-3">
                <x-nav-link :active="request()->routeIs('/')" href="{{ route('/') }}">Beranda</x-nav-link>
                <x-nav-link :active="request()->routeIs('kamarShow')" href="{{ route('kamarShow') }}">Kamar</x-nav-link>
                <x-nav-link :active="request()->routeIs('ruanganShow')" href="{{ route('ruanganShow') }}">Ruangan</x-nav-link>
                @auth
                    <x-nav-link :active="request()->routeIs('riwayat')" href="{{ route('riwayat') }}">Riwayat</x-nav-link>
                @endauth
                {{-- <x-nav-link :active="request()->routeIs('')" href="">Ruangan</x-nav-link> --}}
            </ul>
            @auth
                @php
                    // Ambil inisial dari nama user
                    $nama = Auth::user()->nama;
                    $inisial = collect(explode(' ', $nama))
                        ->map(fn($word) => strtoupper(substr($word, 0, 1)))
                        ->join('');
                    // badge count cart
                    $countCart = \App\Models\Cart::count();
                @endphp

                <div class="d-flex align-items-center gap-4">
                    <!-- Cart Icon -->
                    <a href="{{ route('cart') }}" class="position-relative text-decoration-none">
                        <i class="fa-solid fa-cart-shopping text-light fs-5"></i>
                        <!-- Tambahkan badge kalau perlu -->
                        @if ($countCart > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $countCart }}
                            </span>
                        @endif
                    </a>

                    <!-- User Avatar Dropdown -->
                    <div class="dropdown">
                        <button class="btn d-flex align-items-center justify-content-center rounded-circle" type="button"
                            id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                            style="width: 40px; height: 40px; background-color: #495057; color: white; font-weight: 600;">
                            @if (Auth::user()->avatar)
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar"
                                    style="width: 36px; height: 36px; object-fit: cover; border-radius: 50%;">
                            @else
                                {{ $inisial }}
                            @endif
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="userDropdown">
                            <li class="px-3 py-2">
                                <small class="text-muted">Halo,</small><br>
                                <strong>{{ Auth::user()->nama }}</strong>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('profil') }}">
                                    <i class="bi bi-person-lines-fill me-2"></i> Profil
                                </a>
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"
                                        wire:confirm="Anda yakin ingin keluar?">
                                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            @endauth


            @guest
                <div class="d-flex gap-2">
                    <a href="{{ route('register') }}" class="btn btn-outline-light fw-bold">Daftar</a>
                    <a href="{{ route('login') }}" class="btn btn-light fw-bold">Login</a>
                </div>
            @endguest
        </div>
    </div>
</nav>
