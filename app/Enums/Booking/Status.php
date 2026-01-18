<?php

namespace App\Enums\Booking;

enum Status: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Cancelled = 'cancelled';
    case Completed = 'completed';
    case Rescheduled = 'rescheduled';
    case RefundRequested = 'refund_requested';
    case Refunded = 'refunded';
    case RefundDenied = 'refund_denied';
    case NoShow = 'no_show';

    public function canBeCancelled(): bool
    {
        return in_array($this, [
            self::Pending,
            self::Approved,
        ]);
    }

    public function canRequestRefund(): bool
    {
        return in_array($this, [
            self::Approved,
            self::Completed,
        ]);
    }

    public function canBeRescheduled(): bool
    {
        return in_array($this, [
            self::Pending,
            self::Approved,
        ]);
    }

    public function isActive(): bool
    {
        return in_array($this, [
            self::Pending,
            self::Approved,
            self::Rescheduled,
        ]);
    }
}