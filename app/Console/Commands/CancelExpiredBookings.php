<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Carbon\Carbon;

class CancelExpiredBookings extends Command
{
    protected $signature = 'booking:cancel-expired';
    protected $description = 'Cancel bookings that did not pay in time';

    public function handle()
    {
        $expired = Booking::where('status', 'waiting_payment')
            ->where('payment_deadline', '<', now())
            ->get();

        foreach ($expired as $booking) {
            $booking->update(['status' => 'cancelled']);
            // Optionally: send notification to user
        }

        $this->info('Expired bookings cancelled: ' . $expired->count());
    }
}