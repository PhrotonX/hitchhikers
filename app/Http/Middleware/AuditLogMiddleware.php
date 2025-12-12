<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditLogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log certain methods (POST, PUT, PATCH, DELETE)
        $methodsToLog = ['POST', 'PUT', 'PATCH', 'DELETE'];
        
        if (in_array($request->method(), $methodsToLog)) {
            $this->logRequest($request, $response);
        }

        return $response;
    }

    /**
     * Log the HTTP request
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    protected function logRequest(Request $request, Response $response): void
    {
        try {
            // Determine event type based on HTTP method
            $event = $this->getEventFromMethod($request->method());

            // Get the route name or path
            $route = $request->route() ? $request->route()->getName() : $request->path();

            // Sanitize request data (remove sensitive info)
            $requestData = $this->sanitizeData($request->all());

            // Only log successful operations (2xx responses)
            if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
                AuditLog::create([
                    'user_id' => Auth::id(),
                    'event' => $event,
                    'table' => 'http_requests',
                    'data_id' => 0,
                    'old_values' => null,
                    'new_values' => json_encode([
                        'route' => $route,
                        'method' => $request->method(),
                        'data' => $requestData,
                        'status_code' => $response->getStatusCode(),
                    ]),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'url' => $request->fullUrl(),
                ]);
            }
        } catch (\Exception $e) {
            // Silently fail to prevent disrupting the application
            \Log::error('Audit log middleware error: ' . $e->getMessage());
        }
    }

    /**
     * Get event type from HTTP method
     *
     * @param string $method
     * @return string
     */
    protected function getEventFromMethod(string $method): string
    {
        return match ($method) {
            'POST' => 'http_post',
            'PUT' => 'http_put',
            'PATCH' => 'http_patch',
            'DELETE' => 'http_delete',
            default => 'http_request',
        };
    }

    /**
     * Sanitize sensitive data from request
     *
     * @param array $data
     * @return array
     */
    protected function sanitizeData(array $data): array
    {
        $sensitiveKeys = [
            'password',
            'password_confirmation',
            'current_password',
            'new_password',
            'token',
            'api_token',
            'secret',
            'api_secret',
            'credit_card',
            'card_number',
            'cvv',
            'ssn',
        ];

        foreach ($sensitiveKeys as $key) {
            if (isset($data[$key])) {
                $data[$key] = '***REDACTED***';
            }
        }

        // Also check nested arrays
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->sanitizeData($value);
            }
        }

        return $data;
    }
}
