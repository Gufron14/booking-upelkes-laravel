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
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-4">
                <x-nav-link :active="request()->routeIs('/')" href="{{ route('/') }}">Beranda</x-nav-link>
                <x-nav-link :active="request()->routeIs('booking')" href="{{ route('booking') }}">Booking</x-nav-link>
                {{-- <x-nav-link :active="request()->routeIs('booking')" href="{{ route('booking') }}">Booking</x-nav-link> --}}
                <x-nav-link :active="request()->routeIs('riwayat')" href="{{ route('riwayat') }}">Riwayat</x-nav-link>
                {{-- <x-nav-link :active="request()->routeIs('')" href="">Ruangan</x-nav-link> --}}
            </ul>
            @auth
                <div class="dropdown d-inline">
                    <div class="fw-bold dropdown-toggle d-flex align-items-center text-light" type="button"
                        id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ Auth::user()->nama }}
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('profil') }}">
                                <i class="bi bi-person-lines-fill me-2"></i> Profil
                            </a>
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger" wire:confirm="Anda yakin ingin keluar?">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
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
