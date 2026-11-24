<?php namespace Key\Whitelist\Classes;

use Illuminate\Http\Request;
use Key\Whitelist\Models\Settings;

/**
 * ClientIp determines the address the whitelist is checked against.
 *
 * Request headers such as X-Forwarded-For, X-Real-IP or CF-Connecting-IP are
 * set by the client and can be forged, so they are only used when the request
 * comes from a proxy listed under "Trusted proxies". Without trusted proxies
 * the connecting address is used (Request::ip(), which also honours a
 * TrustProxies configuration of the application itself).
 */
class ClientIp
{
    /**
     * resolve returns the client IP address for the given request.
     */
    public static function resolve(Request $request, ?Settings $settings = null): string
    {
        $settings = $settings ?: Settings::instance();
        $remoteIp = (string) $request->ip();

        $trustedProxies = $settings->getTrustedProxies();
        if (!$trustedProxies || !self::matchesAny($settings, $remoteIp, $trustedProxies)) {
            return $remoteIp;
        }

        // Walk X-Forwarded-For from right to left: every proxy appends the
        // address it received the request from, so the first address that is
        // not a trusted proxy is the client. Entries left of it are untrusted.
        $forwardedFor = array_reverse(array_map('trim', explode(',', (string) $request->server('HTTP_X_FORWARDED_FOR'))));

        foreach ($forwardedFor as $ip) {
            if (!filter_var($ip, FILTER_VALIDATE_IP)) {
                break;
            }

            if (!self::matchesAny($settings, $ip, $trustedProxies)) {
                return $ip;
            }
        }

        return $remoteIp;
    }

    /**
     * matchesAny reports whether the IP matches one of the patterns.
     */
    protected static function matchesAny(Settings $settings, string $ip, array $patterns): bool
    {
        foreach ($patterns as $pattern) {
            if ($settings->matchesIpPattern($ip, $pattern)) {
                return true;
            }
        }

        return false;
    }
}
