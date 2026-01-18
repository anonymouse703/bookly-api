<?php

namespace App\Repositories\Contracts;

use App\Models\Booking;

/**
 * @method Booking|null find(mixed $id)
 * @method Booking|null first()
 */
interface BookingRepositoryInterface extends RepositoryInterface
{
	//define set of methods that BookingRepositoryInterface Repository must implement
	public function filterByKeyword(string|null $keyword = null): self;
	public function filterByStatus(string|null $status = null): self;
	public function filterByDateRange(string|null $startDate = null, string|null $endDate = null): self;
	public function filterByTimeRange(string|null $startTime = null, string|null $endTime = null): self;
	public function filterByUserId(int $userId) : self;
}
