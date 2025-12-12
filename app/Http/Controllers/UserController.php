<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VehicleDriver;
use App\Http\Requests\DeleteUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\AuditLogService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
            'driverAccount' => $user->getDriverAccount(),
            'vehicleDrivers' => $user->getVehicleDriver(),
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

    public function update(UpdateUserRequest $request, User $user){
        $this->onUpdate($request, $user);

        return redirect()->route('user.view', ['user' => $user->id])->with('success', __('Profile updated successfully.'));
    }

    protected function onUpdate(UpdateUserRequest $request, User $user){
        $user = Auth::user();

        $validated = $request->validated();
        
        $passwordChanged = false;
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
            $passwordChanged = true;
        }

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();
        
        // Log password change if it occurred
        if ($passwordChanged) {
            $auditService = new AuditLogService();
            $auditService->logPasswordChange($user->id);
        }
    }

    public function delete(Request $request){
        return view('pages.user.delete');
    }

    /**
     * Show the profile pictures page
     */
    public function showProfilePictures(User $user){
        // Only allow users to view their own profile pictures
        // Middleware ensures auth, just check ownership
        if ((int)Auth::id() !== (int)$user->id) {
            return redirect()->route('user.view', ['user' => $user->id])
                ->with('error', 'You can only manage your own profile pictures.');
        }

        return view('pages.user.profile-picture', [
            'user' => $user,
        ]);
    }

    /**
     * Set a profile picture as the primary one
     */
    public function setPrimaryProfilePicture(Request $request, User $user){
        // Check authorization
        if ((int)Auth::id() !== (int)$user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You can only manage your own profile pictures.'
            ], 403);
        }

        $request->validate([
            'pfp_id' => 'required|integer|exists:profile_pictures,pfp_id'
        ]);

        // Verify the profile picture belongs to this user
        $userProfilePicture = \App\Models\UserProfilePicture::where('pfp_id', $request->pfp_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$userProfilePicture) {
            return response()->json([
                'success' => false,
                'message' => 'Profile picture not found.'
            ], 404);
        }

        // Update user's primary profile picture
        $user->profile_picture_id = $request->pfp_id;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Primary profile picture updated successfully.'
        ]);
    }

    public function destroy(DeleteUserRequest $request, User $user){
        $userId = $user->id;
        $userEmail = $user->email;
        
        // Log account deletion before deleting
        $auditService = new AuditLogService();
        $auditService->log(
            'account_deleted',
            'users',
            $userId,
            ['account_status' => $user->account_status],
            ['account_status' => 'deleted', 'email' => $userEmail]
        );
        
        auth()->logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
        // return response()->json([
        //     'redirect' => 'home',
        // ]);
    }
}
