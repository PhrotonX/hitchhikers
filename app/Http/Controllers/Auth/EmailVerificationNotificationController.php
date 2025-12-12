<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuditLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * @see onStore
     * @return RedirectResponse Previous page with status message
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $this->onStore($request);

        return back()->with('status', 'verification-link-sent');
    }

    /**
     * @see onStore
     * @return JsonResponse Status message
     */
    public function storeAPI(Request $request): JsonResponse|RedirectResponse
    {
        $this->onStore($request);

        return response()->json(['status' => 'verification-link-sent']);
    }

    /**
     * Send a new email verification notification.
     */
    protected function onStore(Request $request){
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended('/');
        }

        $request->user()->sendEmailVerificationNotification();
        
        // Log email verification notification sent
        $auditService = new AuditLogService();
        $auditService->log(
            'email_verification_sent',
            'users',
            $request->user()->id,
            null,
            ['email' => $request->user()->email]
        );
    }
}
