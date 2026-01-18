<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Cache\User\UserByEmail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\User\StoreRequest;
use App\Http\Resources\Api\UserResource;
use App\Http\Controllers\Traits\ApiResponses;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Api\Auth\AuthenticationRequest;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AuthController extends Controller
{
    use ApiResponses;

    protected string $resource = UserResource::class;
    
    public function __construct(protected UserRepositoryInterface $userRepository)
    {}

    public function __invoke(AuthenticationRequest $request)
    {
        $payload = $request->safe()->only([
            'email',
            'password',
        ]);

        try {
            $user = (new UserByEmail($payload['email']))->fetchOrFail();
            
            if (!Hash::check($payload['password'], $user->password)) {
                sleep(1);
                return $this->failed(
                    message: __('Invalid credentials.'),
                    code: Response::HTTP_UNAUTHORIZED
                );
            }

            $token = $user->createToken(
                name: 'user-token',
                expiresAt: now()->addDays(30) 
            );

            $data = [
                'user' => new UserResource($user),
                'token' => $token->plainTextToken, 
                'token_type' => 'Bearer',
            ];

            return $this->success(
                data: $data,
                message: __('Login successful.'),
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