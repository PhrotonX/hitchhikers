<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ConfirmablePasswordController extends Controller
{
    /**
     * Show the confirm password view.
     */
    public function show(): View
    {
        return view('auth.confirm-password');
    }

    /**
     * @see onStore
     * @return RedirectResponse Route /home
     */
    public function store(Request $request): RedirectResponse
    {
        $this->onStore($request);

        return redirect()->intended(route('home', absolute: false));
    }

    /**
     * @see onStore
     * @return JsonResponse JSON: redirect
     */
    public function storeAPI(Request $request): JsonResponse
    {
        $this->onStore($request);

        return response()->json([
            'redirect' => 'home'
        ]);
    }

    /**
     * Confirm the user's password.
     */
    private function onStore(Request $request){
        if (! Auth::guard('web')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        $request->session()->put('auth.password_confirmed_at', time());
    }
}
