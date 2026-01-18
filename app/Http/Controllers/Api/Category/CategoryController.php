<?php

namespace App\Http\Controllers\Api\Category;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\CategoryResource;
use App\Services\Responser\Traits\ApiResponses;
use App\Repositories\Contracts\CategoryRepositoryInterface;

class CategoryController extends Controller
{
    use ApiResponses;

    protected string $resource = CategoryResource::class;

    public function __construct(protected CategoryRepositoryInterface $categoryRepository)
    {}

    public function index()
    {
        $categories = $this->categoryRepository->get();

        return $this->successWithResource($categories);
    }   
}
