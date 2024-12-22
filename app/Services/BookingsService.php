<?php

namespace App\Services;

use App\Booking;
use Illuminate\Support\Facades\Log;

class BookingsService
{
    public function deleteBooking($id): bool | null
    {
        if (!$id) {
            return false;
        }
        $booking = Booking::find($id);
        if ($booking) {
            try {
                $booking->delete();
            } catch (\Exception $e) {
                Log::error('An error occured -> deleteBooking: ' . $e->getMessage());
                return null;
            }
            return true;
        }
        return false;
    }
}
