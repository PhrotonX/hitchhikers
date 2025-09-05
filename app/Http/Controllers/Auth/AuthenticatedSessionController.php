<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Authenticate user or Login
     * 
     * @return Response No content
     */
    public function storeAPI(LoginRequest $request): JsonResponse
    {
        $this->onStore($request);

        return response()->noContent();
    }

    /**
     * Authenticate user or Login
     * 
     * @return Response route /home
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $this->onStore($request);

        return redirect()->intended(route('home', absolute: false));
    }

    /**
     * Handle an incoming authentication request.
     */
    protected function onStore(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();
    }

    public function destroy(Request $request): RedirectResponse
    {
        $this->onDestroy($request);

        return redirect('/');
    }

    public function destroyAPI(Request $request): JsonResponse
    {
        $this->onDestroy($request);

        return response()->noContent();
    }

    /**
     * Destroy an authenticated session.
     */
    public function onDestroy(Request $request){
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
    }
}
