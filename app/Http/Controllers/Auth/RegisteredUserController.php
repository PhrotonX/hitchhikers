<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('pages.auth.register');
    }
    
    public function store(Request $request): RedirectResponse
    {
        $this->onStore($request);

        return redirect(route('home', absolute: false));
    }

    
    public function storeAPI(Request $request): JsonResponse
    {
        $this->onStore($request);

        return response()->json([
            'redirect' => 'home',
            'absolute' => false,
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function onStore(Request $request){
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'ext_name' => ['nullable', 'string', 'max:255'],
            'birthdate' => ['required', Rule::date()->beforeOrEqual(today()->subYears(18))],
            'gender' => ['required', 'string'],
            'phone' => ['nullable', 'string', 'max:255', 'unique:'.User::class],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'ext_name' => $request->ext_name,
            'birthdate' => $request->birthdate,
            'gender' => $request->gender,
            'user_type' => 'member',
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->string('password')),
        ]);

        event(new Registered($user));

        Auth::login($user);
    }
}
