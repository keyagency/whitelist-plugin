# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is an October CMS plugin that provides IP whitelist functionality for backend access control. The plugin namespace is `Key\Whitelist` and it's installed at `plugins/key/whitelist/`.

**Requirements:**
- October CMS 3.x
- PHP 8.2+
- Laravel 9.x

## Key Commands

```bash
# Run migrations when changes are made to database schema
php artisan october:migrate

# Clear cache after configuration changes
php artisan cache:clear
```

## Architecture

### Core Components

**Plugin.php** - Main entry point that:
- Registers the plugin with October CMS
- Registers settings under Settings → Security → IP Whitelist
- Applies WhitelistMiddleware to the 'web' middleware group via `boot()` method
- Registers emergency access routes (`/whitelist/emergency-access/*`)
- Registers console command `whitelist:cleanup-tokens`
- Registers scheduled task for daily token cleanup (3am)

**WhitelistMiddleware.php** - Request filtering logic that:
- Checks if plugin is enabled/disabled via PluginManager
- Determines whether to protect entire site or just backend based on `protect_entire_site` setting
- Checks for approved emergency access tokens before blocking
- Extracts client IP from various proxy headers (Cloudflare, Nginx, X-Forwarded-For, etc.)
- Delegates IP validation to Settings model
- Returns 403 response with custom blocked view if IP not whitelisted
- Conditionally shows emergency access button (if enabled and no pending request)
- Optionally logs detailed security information about blocked attempts

**Settings.php** - Settings model that:
- Implements SettingsModel behavior with code `key_whitelist_settings`
- Provides `getWhitelistedIps()` to parse newline-separated IP list
- Provides `isIpWhitelisted($ip)` with logic for:
  - Returning true if whitelist disabled
  - Always allowing localhost IPs (127.0.0.1, ::1) if `allow_localhost` enabled
  - Returning true if no IPs configured (failsafe)
  - Checking IP against whitelist using CIDR matching
- Implements IPv4 and IPv6 CIDR range matching algorithms
- Stores emergency access configuration (emails, token duration, approval mode, button text)

**EmergencyAccess.php (Model)** - Emergency access request management:
- Tracks access requests with status (pending/approved/denied/expired)
- Generates cryptographically secure 64-character tokens
- Validates token expiration
- Provides static helpers: `hasPendingRequest()`, `hasApprovedAccess()`, `createRequest()`
- Automatically adds approved IPs to whitelist settings
- Cleanup expired entries via `cleanupExpired()` method

**EmergencyAccess.php (Controller)** - HTTP request handler:
- `request()` - Creates new access request, sends admin emails
- `approve($token)` - Validates and approves token, adds IP to whitelist
- Validates admin emails, handles auto-approval mode
- Sends HTML/text email notifications using `mail.emergency_access_request` template

**CleanupExpiredTokens.php** - Console command:
- Command: `whitelist:cleanup-tokens`
- Updates expired tokens to 'expired' status
- Runs automatically daily at 3am via scheduler

### IP Detection Priority

The middleware detects client IP from these headers in order:
1. `HTTP_CF_CONNECTING_IP` (Cloudflare)
2. `HTTP_X_REAL_IP` (Nginx)
3. `HTTP_X_FORWARDED_FOR` (Standard proxy)
4. `HTTP_CLIENT_IP` (Proxy)
5. `REMOTE_ADDR` (Direct connection)

### Settings Configuration

Settings are defined in `models/settings/fields.yaml`:

**General Tab:**
- `whitelist_enabled` - Master toggle
- `allowed_ips` - Newline-separated list of IPs and CIDR ranges
- `block_message` - Custom message for blocked users
- `protect_entire_site` - Apply to entire site vs backend only
- `allow_localhost` - Safety feature for local development
- `log_blocked_attempts` - Enable security logging

**Emergency Access Tab:**
- `enable_emergency_access` - Enable/disable emergency access feature
- `emergency_access_emails` - Newline-separated admin email addresses
- `access_token_duration` - Token validity in hours (default: 24)
- `require_manual_approval` - Manual vs automatic approval (default: true)
- `access_request_button_text` - Customizable button text (default: "Need access?")

### Plugin State Awareness

The middleware checks plugin state via `PluginManager` before applying restrictions, ensuring that disabling the plugin immediately removes all restrictions without requiring cache clearing.

### Permissions

The plugin implements role-based access control via `registerPermissions()`:
- `key.whitelist.access_settings` - Required to access and modify IP whitelist settings
- Settings page enforces this permission automatically