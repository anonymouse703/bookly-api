<?php

namespace App\Repositories\Contracts;

use App\Models\Service;

/**
 * @method Service|null find(mixed $id)
 * @method Service|null first()
 */
interface ServiceRepositoryInterface extends RepositoryInterface
{
	//define set of methods that ServiceRepositoryInterface Repository must implement
	public function filterById(string $serviceId): static;
	public function filterByCategoryId(string $categoryId): static;
}
