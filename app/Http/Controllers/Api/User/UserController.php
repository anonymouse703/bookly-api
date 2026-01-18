<?php

namespace App\Http\Controllers\Api\User;

use App\Cache\User\UserById;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Api\UserResource;
use App\Http\Controllers\Traits\ApiResponses;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
     use ApiResponses;

    protected string $resource = UserResource::class;
    
    public function __construct(protected UserRepositoryInterface $userRepository)
    {}

    public function show(Request $request)
    {
        $user = Auth::user();
        try {
            $user = (new UserById($user->id))->fetchOrFail();
            
            return $this->successWithResource($user,
                code: Response::HTTP_OK
            );

        } catch (ModelNotFoundException $exception) {
            sleep(1);
            return $this->failed(
                message: __('Invalid credentials.'), 
                code: Response::HTTP_UNAUTHORIZED
            );
        }
    }
}
