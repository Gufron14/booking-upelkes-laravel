<nav class="navbar navbar-expand-lg" style="background-color: #ffbb00">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">Upelkes Jabar</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <x-nav-link :active="request()->routeIs('/')" href="{{ route('/') }}">Beranda</x-nav-link>
                <x-nav-link :active="request()->routeIs('kamar')" href="{{ route('kamar') }}">Kamar</x-nav-link>
                <x-nav-link :active="request()->routeIs('')" href="">Ruangan</x-nav-link>
            </ul>
            <a href="" class="btn btn-outline-light fw-bold">Login</a>
        </div>
    </div>
</nav>
