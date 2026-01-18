<?php

namespace App\Repositories;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Contracts\BookingRepositoryInterface;

class BookingRepository extends BaseRepository implements BookingRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(app(Booking::class));
    }

     public function filterByKeyword(?string $keyword = null): self
    {
        if (!empty($keyword)) {
            return $this->filter(static function (Builder $builder) use ($keyword) {
                $builder->where(function ($query) use ($keyword) {
                    $query->whereHas('user', function (Builder $query) use ($keyword) {
                        $query->where('name', 'like', "%{$keyword}%");
                    });
                });
            });
        }

        return $this;
    }

    public function filterByStatus(?string $status = null): self
    {
        if (!empty($status)) {
            return $this->filter(static function (Builder $builder) use ($status) {
                $builder->where('status', $status);
            });
        }

        return $this;
    }

    public function filterByDateRange(?string $startDate = null, ?string $endDate = null): self
    {
        if (!empty($startDate) && !empty($endDate)) {
            return $this->filter(static function (Builder $builder) use ($startDate, $endDate) {
                $builder->where('date', '>=', $startDate)
                    ->where('date', '<=', $endDate);
            });
        }

        return $this;
    }

    public function filterByTimeRange(?string $startTime = null, ?string $endTime = null): self
    {
        if (!empty($startTime) && !empty($endTime)) {
            return $this->filter(static function (Builder $builder) use ($startTime, $endTime) {
                $builder->where('start_time', '>=', $startTime)
                    ->where('end_time', '<=', $endTime);
            });
        }

        return $this;
    }

    public function filterByUserId(int $userId): self
    {
        return $this->filter(static function (Builder $builder) use ($userId) {
            $builder->where('user_id', $userId);
        });
    }
}
