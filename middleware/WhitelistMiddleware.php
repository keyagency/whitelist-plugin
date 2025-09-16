<?php namespace Key\Whitelist\Middleware;

use Closure;
use Key\Whitelist\Models\Settings;
use Illuminate\Http\Request;
use Log;
use Config;

class WhitelistMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next)
    {
        // Check if plugin is active first
        $pluginManager = \System\Classes\PluginManager::instance();
        if (!$pluginManager->hasPlugin('Key.Whitelist') || $pluginManager->isDisabled('Key.Whitelist')) {
            return $next($request);
        }

        $settings = Settings::instance();

        // Check if we should protect entire site or just backend
        $protectEntireSite = $settings->get('protect_entire_site', false);

        if (!$protectEntireSite) {
            // Only apply to backend routes if entire site protection is disabled
            $backendUri = Config::get('cms.backendUri', '/backend');
            $requestPath = '/' . ltrim($request->getPathInfo(), '/');

            if (strpos($requestPath, $backendUri) !== 0) {
                return $next($request);
            }
        }

        // Skip if whitelist is disabled
        if (!$settings->get('whitelist_enabled', false)) {
            return $next($request);
        }

        // Get client IP address
        $clientIp = $this->getClientIpAddress($request);

        // Check if IP is whitelisted
        if (!$settings->isIpWhitelisted($clientIp)) {
            // Log blocked attempt if enabled
            if ($settings->get('log_blocked_attempts', true)) {
                // Build stack trace-like format for detailed information
                $stackTrace = $this->buildSecurityStackTrace($request, $clientIp);

                Log::warning("IP Whitelist: Blocked access attempt from {$clientIp} to {$request->getRequestUri()}\nStack trace:\n{$stackTrace}");
            }

            // Return custom error response
            $message = $settings->get('block_message', 'Access denied. Your IP address is not authorized to access this area.');

            return response()->view('key.whitelist::blocked', [
                'message' => $message,
                'ip' => $clientIp
            ], 403);
        }

        return $next($request);
    }

    /**
     * Get the real client IP address, considering proxies and load balancers
     */
    private function getClientIpAddress(Request $request)
    {
        // Check for IPs in order of priority
        $ipSources = [
            'HTTP_CF_CONNECTING_IP',     // Cloudflare
            'HTTP_X_REAL_IP',           // Nginx proxy
            'HTTP_X_FORWARDED_FOR',     // Standard forwarded header
            'HTTP_X_FORWARDED',         // Alternative
            'HTTP_FORWARDED_FOR',       // Alternative
            'HTTP_FORWARDED',           // Alternative
            'HTTP_CLIENT_IP',           // Proxy
            'REMOTE_ADDR'               // Standard
        ];

        foreach ($ipSources as $source) {
            if (!empty($_SERVER[$source])) {
                $ip = $_SERVER[$source];

                // Handle comma-separated IPs (X-Forwarded-For can contain multiple IPs)
                if (strpos($ip, ',') !== false) {
                    $ip = trim(explode(',', $ip)[0]);
                }

                // Validate IP address
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }

                // Also allow private range IPs for local development
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }

        // Fallback to Laravel's method
        return $request->ip();
    }

    /**
     * Build a stack trace-like format for security logging
     */
    private function buildSecurityStackTrace($request, $clientIp)
    {
        $trace = [];
        $trace[] = "#0 Client IP: {$clientIp}";
        $trace[] = "#1 Request Method: " . $request->method();
        $trace[] = "#2 Full URL: " . $request->fullUrl();
        $trace[] = "#3 User Agent: " . ($request->userAgent() ?: 'N/A');
        $trace[] = "#4 Referer: " . ($request->header('referer') ?: 'N/A');
        $trace[] = "#5 Session ID: " . $request->session()->getId();
        $trace[] = "#6 Host Header: " . ($request->header('Host') ?: 'N/A');
        $trace[] = "#7 Accept: " . ($request->header('Accept') ?: 'N/A');
        $trace[] = "#8 Accept-Language: " . ($request->header('Accept-Language') ?: 'N/A');

        // Add proxy headers if present
        $proxyHeaders = [
            'X-Forwarded-For' => $request->header('X-Forwarded-For'),
            'X-Real-IP' => $request->header('X-Real-IP'),
            'CF-Connecting-IP' => $request->header('CF-Connecting-IP')
        ];

        $proxyIndex = 9;
        foreach ($proxyHeaders as $header => $value) {
            if ($value) {
                $trace[] = "#{$proxyIndex} {$header}: {$value}";
                $proxyIndex++;
            }
        }

        // Add server information
        $trace[] = "#{$proxyIndex} Remote Address: " . ($_SERVER['REMOTE_ADDR'] ?? 'N/A');
        $trace[] = "#" . ($proxyIndex + 1) . " Server Name: " . ($_SERVER['SERVER_NAME'] ?? 'N/A');
        $trace[] = "#" . ($proxyIndex + 2) . " Request Time: " . date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME'] ?? time());
        $trace[] = "#" . ($proxyIndex + 3) . " Blocked Reason: IP not in whitelist";

        return implode("\n", $trace);
    }
}