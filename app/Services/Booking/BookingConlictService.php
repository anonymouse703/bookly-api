<?php

namespace App\Services\Booking;

use App\Models\Booking as NewBooking;

class BookingConlictService
{
    public function generate(?int $serviceId = null, ?string $date = null): array
    {
        $bookings = NewBooking::query()
            ->when($serviceId, fn ($q) => $q->where('service_id', $serviceId))
            ->when($date, fn ($q) => $q->where('date', $date))
            ->where('status', '!=', 'cancelled')
            ->orderBy('start_time')
            ->get();

        return [
            'overlapping' => $this->findOverlapping($bookings),
            'conflicting' => $this->findConflicting($bookings),
            'gaps'        => $this->findGaps($bookings),
            'summary'     => $this->summary($bookings),
        ];
    }

    protected function findOverlapping($bookings): array
    {
        $overlaps = [];

        for ($i = 0; $i < $bookings->count() - 1; $i++) {
            $current = $bookings[$i];
            $next = $bookings[$i + 1];

            if ($current->end_time > $next->start_time) {
                $overlaps[] = [
                    'booking_a' => $current->id,
                    'booking_b' => $next->id,
                ];
            }
        }

        return $overlaps;
    }

    protected function findConflicting($bookings): array
    {
        return $bookings
            ->groupBy(fn ($b) => $b->date.'-'.$b->start_time.'-'.$b->end_time)
            ->filter(fn ($group) => $group->count() > 1)
            ->map(fn ($group) => $group->pluck('id'))
            ->values()
            ->toArray();
    }

    protected function findGaps($bookings): array
    {
        $gaps = [];

        for ($i = 0; $i < $bookings->count() - 1; $i++) {
            $current = $bookings[$i];
            $next = $bookings[$i + 1];

            if ($current->end_time < $next->start_time) {
                $gaps[] = [
                    'from' => $current->end_time,
                    'to'   => $next->start_time,
                ];
            }
        }

        return $gaps;
    }

    protected function summary($bookings): array
    {
        return [
            'total_bookings' => $bookings->count(),
            'total_overlaps' => count($this->findOverlapping($bookings)),
            'total_gaps'     => count($this->findGaps($bookings)),
        ];
    }
}
