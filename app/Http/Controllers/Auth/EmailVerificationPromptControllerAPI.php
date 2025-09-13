<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptControllerAPI extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): JsonResponse
    {
        return $request->user()->hasVerifiedEmail()
                    ? response()->json(['redirect' => 'home'])
                    : route('api/verify-email');
    }
}
