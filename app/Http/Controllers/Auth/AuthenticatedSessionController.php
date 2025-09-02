<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Authenticate user or Login
     * 
     * @return Response No content
     */
    public function storeAPI(LoginRequest $request): Response
    {
        $this->onStore($request);

        return response()->noContent();
    }

    /**
     * Authenticate user or Login
     * 
     * @return Response route /home
     */
    public function store(LoginRequest $request): Response
    {
        $this->onStore($request);

        return redirect()->intended(route('home', absolute: false));
    }

    /**
     * Handle an incoming authentication request.
     */
    protected function onStore(LoginRequest $request): Response
    {
        $request->authenticate();

        $request->session()->regenerate();

        return response()->noContent();
    }

    public function destroy(Request $request): Response
    {
        $this->onDestroy($request);

        return redirect('/');
    }

    public function destroyAPI(Request $request): Response
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
