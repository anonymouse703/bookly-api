<?php

namespace App\Services\Responser\Facades;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Facade;

/**
 * @method static JsonResponse success(?array $data = null, ?string $message = null, ?int $code = null)
 * @method static JsonResource successWithResource(Model|Collection|LengthAwarePaginator|Paginator $resource, ?string $message = null, ?int $code = null, ?array $data = null)
 * @method static JsonResponse validationError(array $errors, ?string $message = null, ?int $code = null)
 * @method static JsonResponse notFound(?string $message = null, ?int $code = null)
 * @method static JsonResponse throttled(?string $message = null, ?int $code = null, ?int $availableInSeconds = null)
 * @method static JsonResponse forbidden(?string $message = null, ?int $code = null)
 * @method static JsonResponse failed(?string $message = null, ?int $code = null)
 *
 * @see \App\Services\Responser\Responser
 */
class Responser extends Facade
{
    public static function getFacadeAccessor()
	{
		return 'responser';
	}
}
