<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ApiResponses;

class LogoutController extends Controller
{
    use ApiResponses;

    public function __invoke(Request $request)
    {
        /** @var User */
        $user = $request->user();

        $user->clearTokens();

        return $this->success(message: __('Logout successful.'));
    }
}
