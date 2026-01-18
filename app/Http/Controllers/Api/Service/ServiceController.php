<?php

namespace App\Http\Controllers\Api\Service;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Service\ServiceRequest;
use App\Http\Resources\Api\ServiceResource;
use App\Repositories\Contracts\ServiceRepositoryInterface;
use App\Services\Responser\Traits\ApiResponses;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    use ApiResponses;

    protected string $resource = ServiceResource::class;
    
    public function __construct(protected ServiceRepositoryInterface $serviceRepository)
    { }

    public function index(ServiceRequest $request)
    {
        $categoryId = $request->query('category_id');

        $services = $this->serviceRepository
            ->filterByCategoryId($categoryId)
            ->with(['provider','category'])
            ->get();

        return $this->successWithResource($services);
    }
}
