<?php

namespace App\Http\Controllers\Api\Customer;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\Api\UserResource;
use App\Http\Controllers\Traits\ApiResponses;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Repositories\Contracts\UserRepositoryInterface;

class RegisterController extends Controller
{
    use ApiResponses;

    protected string $resource = UserResource::class;

    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    public function __invoke(RegisterRequest $request)
    {
        $payload = $request->safe()->only([
            'name',
            'email',
            'mobile',
            'password',
        ]);

        $user = new User();
        $user->name = $payload['name'];
        $user->email = $payload['email'];
        $user->password = Hash::make($payload['password']);

        $this->userRepository->save($user); 

        return $this->successWithResource(
            resource: $user, 
            message: __('User registered successfully.'),
            code: Response::HTTP_CREATED
        );
    }
}
