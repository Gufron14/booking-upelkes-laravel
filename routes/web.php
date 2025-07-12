<?php

use App\Livewire\Home;
use App\Livewire\Kamar;
use App\Livewire\Booking;
use App\Livewire\Payment;
use App\Livewire\Riwayat;
use App\Livewire\Auth\Login;
use App\Livewire\Admin\Ruangan;
use App\Livewire\Auth\Register;
use App\Livewire\Admin\Customer;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Fasilitas;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Admin\Layanan\Index;
use Illuminate\Support\Facades\Route;
use App\Livewire\Resepsionis\Transaksi;
use App\Livewire\Resepsionis\CetakLaporan;
use App\Livewire\Admin\Layanan\EditLayanan;
use App\Livewire\Resepsionis\KelolaBooking;
use App\Livewire\Admin\Layanan\CreateLayanan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', Home::class)->name('/');
Route::get('layanan', Kamar::class)->name('layanan');


Route::middleware('guest')->group(function () {
    Route::get('login', Login::class)->name('login');
    Route::get('register', Register::class)->name('register');
});

Route::middleware('auth')->group(function () {
    // Fungsi Logout Langsung
    Route::post('logout', function () {
        Auth::logout();
        return redirect()->route('login');
    })->name('logout');

    Route::get('booking', Booking::class)->name('booking');
    Route::get('riwayat', Riwayat::class)->name('riwayat');
    Route::get('payment/{booking}', Payment::class)->name('payment');
});

Route::middleware(['auth', 'role:admin'])->group( function () {

    Route::prefix('admin')->group(function () {        
        Route::get('dashboard', Dashboard::class)->name('dashboard');
    
        // ADMIN
        Route::get('layanan', Index::class)->name('daftar.layanan');
        Route::get('layanan/create', CreateLayanan::class)->name('layanan.create');
        Route::get('layanan/edit/{id}', EditLayanan::class)->name('layanan.edit');
        Route::get('kamar', \App\Livewire\Admin\Kamar::class)->name('kamar');
        Route::get('ruangan', Ruangan::class)->name('ruangan');
        Route::get('fasilitas', Fasilitas::class)->name('fasilitas');
    
        // RESEPSIONIS
        Route::get('booking', KelolaBooking::class)->name('kelola.booking');
        Route::get('transaksi', Transaksi::class)->name('transaksi');

        // Tambahkan route ini
        Route::get('cetak-laporan', CetakLaporan::class)
        ->name('cetak-laporan');

    
        Route::get('customer', Customer::class)->name('customer');
    });
});