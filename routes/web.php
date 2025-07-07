<?php

use App\Livewire\Home;
use App\Livewire\Kamar;
use App\Livewire\Booking;
use App\Livewire\Payment;
use App\Livewire\Riwayat;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Admin\Customer;
use App\Livewire\Admin\Dashboard;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Admin\Layanan\Index;
use Illuminate\Support\Facades\Route;
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
Route::get('kamar', Kamar::class)->name('kamar');
Route::get('booking', Booking::class)->name('booking');
Route::get('riwayat', Riwayat::class)->name('riwayat');

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

    Route::get('payment/{booking}', Payment::class)->name('payment');
});

Route::middleware(['auth', 'role:admin'])->group( function () {

    Route::prefix('admin')->group(function () {        
        Route::get('dashboard', Dashboard::class)->name('dashboard');
    
        // ADMIN
        Route::get('layanan', Index::class)->name('layanan');
        Route::get('layanan/create', CreateLayanan::class)->name('layanan.create');
        Route::get('layanan/edit', EditLayanan::class)->name('layanan.edit');
    
        // RESEPSIONIS
        Route::get('booking', KelolaBooking::class)->name('kelola.booking');
    
        Route::get('customer', Customer::class)->name('customer');
    });
});