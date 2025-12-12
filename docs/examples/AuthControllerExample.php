<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Example: Authentication Controller with Audit Logging
 * 
 * This is an example showing how to integrate audit logging
 * into your authentication flow.
 */
class AuthController extends Controller
{
    protected $auditService;

    public function __construct(AuditLogService $auditService)
    {
        $this->auditService = $auditService;
    }

    /**
     * Handle user login with audit logging
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Log successful login
            $this->auditService->logLogin($user->id);
            
            // Generate token for API authentication
            $token = $user->createToken('auth_token')->plainTextToken;
            
            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'user' => $user,
                'token' => $token,
            ]);
        }

        // Log failed login attempt
        $this->auditService->logFailedLogin($request->email);
        
        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials',
        ], 401);
    }

    /**
     * Handle user logout with audit logging
     */
    public function logout(Request $request)
    {
        $userId = Auth::id();
        
        // Log logout before actually logging out
        $this->auditService->logLogout($userId);
        
        // Revoke all tokens for the user (if using Sanctum)
        $request->user()->tokens()->delete();
        
        Auth::logout();
        
        return response()->json([
            'success' => true,
            'message' => 'Logout successful',
        ]);
    }

    /**
     * Handle password change with audit logging
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect',
            ], 400);
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        // Log password change
        $this->auditService->logPasswordChange($user->id);

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully',
        ]);
    }

    /**
     * Handle email verification with audit logging
     */
    public function verifyEmail(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'success' => false,
                'message' => 'Email already verified',
            ]);
        }

        $user->markEmailAsVerified();

        // Log email verification
        $this->auditService->logEmailVerified($user->id);

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully',
        ]);
    }

    /**
     * Example: Custom event logging for user profile update
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $oldData = [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'phone' => $user->phone,
        ];

        $request->validate([
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
        ]);

        // Update user
        $user->fill($request->only(['first_name', 'last_name', 'phone']));
        $user->save();

        // Note: The Auditable trait on User model will automatically log this,
        // but here's how you could manually log it if needed:
        /*
        $this->auditService->log(
            'profile_updated',
            'users',
            $user->id,
            $oldData,
            $request->only(['first_name', 'last_name', 'phone'])
        );
        */

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'user' => $user,
        ]);
    }

    /**
     * Example: Log account deletion
     */
    public function deleteAccount(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;

        // Log account deletion before actually deleting
        $this->auditService->log(
            'account_deleted',
            'users',
            $userId,
            ['status' => 'active'],
            ['status' => 'deleted']
        );

        // Soft delete or hard delete the user
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Account deleted successfully',
        ]);
    }

    /**
     * Example: Log security events like password reset
     */
    public function requestPasswordReset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            // Don't reveal if user exists
            return response()->json([
                'success' => true,
                'message' => 'If the email exists, a reset link will be sent.',
            ]);
        }

        // Log password reset request
        $this->auditService->log(
            'password_reset_requested',
            'users',
            $user->id,
            null,
            ['email' => $user->email]
        );

        // Send password reset email...
        // Password::sendResetLink($request->only('email'));

        return response()->json([
            'success' => true,
            'message' => 'Password reset link sent to your email',
        ]);
    }
}
