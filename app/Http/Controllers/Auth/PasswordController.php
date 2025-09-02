<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $this->onUpdate($request);

        return back()->with('status', 'password-updated');
    }

    public function updateAPI(Request $request): JsonResponse
    {
        $this->onUpdate($request);

        return response()->json([
            'status' => 'password-updated',
            'back' => true,
        ]);
    }

    /**
     * Update the user's password.
     */
    protected function onUpdate(Request $request){
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);
    }
}
