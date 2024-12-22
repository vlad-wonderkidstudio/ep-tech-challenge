<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BookingsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingsController extends Controller
{
    protected $bookingsService;

    public function __construct(BookingsService $clientService)
    {
        $this->bookingsService = $clientService;
    }

    public function destroy($id): JsonResponse
    {
        $deleted = $this->bookingsService->deleteBooking($id);
        return ($deleted)
            ? response()->json([
                'success' => true,
                'message' => 'Booking deleted'
            ])
            : response()->json([
                'success' => false,
                'message' => 'An error occurred'
            ], 500);
    }
}
