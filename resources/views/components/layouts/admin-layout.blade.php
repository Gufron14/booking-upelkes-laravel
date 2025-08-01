<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Page Title' }}</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/img/logo.png') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/styles.min.css') }}" />
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    @livewireStyles
    @vite('resources/js/app.js')
</head>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">

        <!--  App Topstrip -->
        <div class="bg-dark py-3 px-4 w-100 d-lg-flex align-items-center justify-content-between sticky-top">
            <div class="d-none d-sm-flex align-items-center justify-content-center gap-9 mb-3 mb-lg-0">
                <a class="navbar-brand fw-bold fs-6 text-light fw-bold ms-2" href="#">
                    {{-- <img src="{{ asset('assets/img/logo.png') }}" alt="logo Upelkes" width="100" height=""> --}}
                    Upelkes Jabar Panel
                </a>
            </div>

            <div class="d-lg-flex align-items-center gap-3">
                <div class="d-sm-flex align-items-center justify-content-center gap-8">
                    <div class="d-flex align-items-center justify-content-center gap-8">
                        <span class="text-light me-3">Selamat datang, &nbsp; {{ auth()->user()->nama }}</span>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-danger"
                                wire:confirm="Anda yakin ingin keluar?">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
        <!-- Sidebar Start -->
        @include('components.sidebar')
        <!--  Sidebar End -->
        <!--  Main wrapper -->
        <div class="body-wrapper">
            <!--  Header Start -->

            <!--  Header End -->
            <div class="container p-5">
                <!--  Row 1 -->
                {{ $slot }}

                {{-- <div class="py-6 px-6 text-center">
                        <p class="mb-0 fs-4">Design and Developed by <a href="https://adminmart.com/"
                                target="_blank"
                                class="pe-1 text-primary text-decoration-underline">AdminMart.com</a></p>
                    </div> --}}
            </div>
        </div>
    </div>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/id.min.js'></script>
    <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/sidebarmenu.js"></script>
    <script src="../assets/js/app.min.js"></script>
    <script src="../assets/libs/apexcharts/dist/apexcharts.min.js"></script>
    <script src="../assets/libs/simplebar/dist/simplebar.js"></script>
    <script src="../assets/js/dashboard.js"></script>
    <!-- solar icons -->
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>

    <!-- CDN Links -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    @livewireScripts
</body>

</html>
