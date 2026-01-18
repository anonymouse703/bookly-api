<?php

namespace App\Http\Controllers\Api\Booking;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Booking\BookingConflictService;

class BookingConflictController extends Controller
{
    // public function index(Request $request, BookingConflictService $service)
    // {
    //     $data = $service->generate(
    //         serviceId: $request->integer('service_id'),
    //         date: $request->string('date')
    //     );

    //     return response()->json([
    //         'data' => $data,
    //     ]);
    // }
}
