<?php

namespace App\Services\Responser\Traits;

use Exception;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponses
{
    /**
     * Respond a success status.
     *
     * @param  array|null  $data
     * @param  string|null  $message
     * @param  int|null  $code
     */
    public function success(?array $data = null, ?string $message = null, ?int $code = null): JsonResponse
    {
        return response()->json([
            'data' => $data ?? [],
            'message' => $message ?? __('Success.'),
            'code' => $code ?? Response::HTTP_OK,
            'success' => true,
        ], $code ?? Response::HTTP_OK);
    }

    /**
     * Respond a success status with the resource.
     *
     * @param Model|Collection|LengthAwarePaginator|Paginator  $resource
     * @param  string|null  $message
     * @param  int|null  $code
     */
    public function successWithResource(Model|Collection|LengthAwarePaginator|Paginator $resource, ?string $message = null, ?int $code = null, ?array $data = null): JsonResource
    {
        $this->requireResourceClass();

        if ($resource instanceof Model) {
            $resource = (new $this->resource($resource));
        } else {
            $resource = $this->resource::collection($resource);
        }

        return $resource->additional(array_merge([
            'message' => $message ?? __('Success.'),
            'code' => $code ?? Response::HTTP_OK,
            'success' => true
        ], $data ?? []));
    }

    /**
     * Respond a validation error.
     *
     * @param  array  $errors
     * @param  string|null  $message
     * @param  int|null  $code
     */
    public function validationError(array $errors, ?string $message = null, ?int $code = null): JsonResponse
    {
        return response()->json([
            'errors' => $errors,
            'message' => $message ?? data_get(collect($errors)->first(), 0),
            'code' => $code ?? Response::HTTP_UNPROCESSABLE_ENTITY,
            'success' => false
        ], $code ?? Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Respond an error indicating that the resource does not exist.
     *
     * @param  string|null  $message
     * @param  int|null  $code
     */
    public function notFound(?string $message = null, ?int $code = null): JsonResponse
    {
        return response()->json([
            'message' => $message ?? __('Not found.'),
            'code' => $code ?? Response::HTTP_NOT_FOUND,
            'success' => false
        ], $code ?? Response::HTTP_NOT_FOUND);
    }

    /**
     * Respond a throttled error.
     *
     * @param  string|null  $message
     * @param  int|null  $code
     * @param  int|null  $availableInSeconds
     */
    public function throttled(?string $message = null, ?int $code = null, ?int $availableInSeconds = null): JsonResponse
    {
        return response()->json(
            array_merge([
                'message' => $message ?? __('Too many attempts.'),
                'code' => $code ?? Response::HTTP_TOO_MANY_REQUESTS
            ], isset($availableInSeconds) ? ['available_in_seconds' => $availableInSeconds] : []),
            $code ?? Response::HTTP_TOO_MANY_REQUESTS
        );
    }

    /**
     * Respond a forbidden error.
     *
     * @param  string|null  $message
     * @param  int|null  $code
     */
    public function forbidden(?string $message = null, ?int $code = null): JsonResponse
    {
        return response()->json([
            'message' => $message ?? __('This action is forbidden.'),
            'code' => $code ?? Response::HTTP_FORBIDDEN,
            'success' => false
        ], $code ?? Response::HTTP_FORBIDDEN);
    }

    /**
     * Return a failed response.
     *
     * @param  string|null  $message
     * @param  int|null  $code
     */
    public function failed(?string $message = null, ?int $code = null): JsonResponse
    {
        return response()->json([
            'message' => $message ?? __('Something went wrong.'),
            'code' => $code ?? Response::HTTP_INTERNAL_SERVER_ERROR,
            'success' => false
        ], $code ?? Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Requires the resource class property.
     */
    protected function requireResourceClass(): void
    {
        if (! property_exists($this, 'resource')) {
            throw new Exception("The 'resource' property must be defined.");
        }
    }
}
