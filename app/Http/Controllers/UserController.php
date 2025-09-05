<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use AuthorizesRequests;
    
    /**
     * @return View User account information page with user as a response data.
     */
    public function show(User $user){
        return view('pages.user.view', [
            'user' => $user,
        ]);
    }

    /**
     * @return View Edit Page with user as a response data.
     */
    public function edit(User $user){
        $this->onEdit($user);

        return view('pages.user.edit', [
            'user' => $user,
        ]);
    }

    public function editAPI(User $user) : JsonResponse{
        $this->onEdit($user);

        return response()->json([
            'redirect' => 'pages.user.edit',
            'user' => $user,
        ]);
    }

    protected function onEdit(User $user){
        $this->authorize('edit', $user);
    }
}
