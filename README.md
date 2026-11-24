# Backend IP Whitelist Plugin

A comprehensive security plugin for October CMS that restricts backend access to whitelisted IP addresses and ranges.

**Developed by [Key Agency](https://key.agency)** - Expert October CMS & Laravel Development

## Features

### 🔒 Core Security Features
- **IP Address Whitelisting**: Allow specific IP addresses to access the backend
- **CIDR Range Support**: Full support for IPv4 and IPv6 CIDR notation (e.g., 192.168.1.0/24)
- **Custom Block Messages**: Configurable error messages for blocked users
- **Security Logging**: Optional logging of blocked access attempts with detailed information

### 🛡️ Advanced Protection
- **Plugin State Awareness**: Automatically disables restrictions when plugin is deactivated
- **Localhost Option**: Optionally always allow localhost (127.0.0.1, ::1); off by default
- **Proxy & Load Balancer Support**: Reads the real client IP from X-Forwarded-For only for requests from your own trusted proxies (Cloudflare, load balancers, reverse proxies), so the IP cannot be spoofed

### 🚨 Emergency Access System
- **Email-Based Recovery**: Request access when locked out via discreet link
- **Token-Based Security**: Cryptographically secure, time-limited approval tokens
- **Manual or Auto Approval**: Choose between admin approval or automatic whitelisting
- **Multi-Admin Support**: Send notifications to multiple administrators
- **Spam Prevention**: Customizable button text and duplicate request blocking
- **Automatic Cleanup**: Expired tokens cleaned up daily

### 🎯 User Experience
- **Easy Backend Interface**: Intuitive settings panel with comprehensive help
- **Real-time Feedback**: Blocked users see their detected IP address
- **Development Friendly**: Safe defaults that won't lock developers out
- **Instant Control**: Disable plugin to immediately remove all restrictions
- **Multilingual Support**: Full internationalization with English, Dutch, German, and French translations
- **Role-Based Access**: Permission-based settings access for enhanced security

## Installation

1. Place the plugin files in `plugins/key/whitelist/`
2. Run the migration command:
   ```bash
   php artisan october:migrate
   ```
3. Clear cache:
   ```bash
   php artisan cache:clear
   ```

## Configuration

Navigate to **Settings → Security → IP Whitelist** in your backend:

### General Settings

- **Enable IP Whitelist**: Master toggle for whitelist protection
- **Allowed IP Addresses**: Multi-line input for IP addresses and CIDR ranges
- **Block Message**: Custom message displayed to blocked users
- **Protect Entire Site**: Apply whitelist to entire website (not just backend)
- **Always Allow Localhost**: Allow 127.0.0.1 and ::1 (off by default; leave off when a proxy on the same server forwards requests)
- **Trusted Proxies**: IPs or CIDR ranges of your own proxies, load balancers or CDN, one per line
- **Log Blocked Attempts**: Enable security event logging

### Emergency Access Settings

- **Enable Emergency Access**: Allow blocked users to request access via email
- **Admin Email Addresses**: Email addresses that receive access requests (one per line)
- **Token Duration**: How long access tokens remain valid (in hours)
- **Require Manual Approval**: Admin must click approval link (recommended for security)
- **Access Request Button Text**: Customize the button text (keep discreet to prevent spam)

### Supported IP Formats

- **Single IPv4**: `192.168.1.100`
- **IPv4 CIDR Range**: `192.168.1.0/24`
- **Single IPv6**: `2001:db8::1`
- **IPv6 CIDR Range**: `2001:db8::/32`

## Permissions & Access Control

### Role-Based Permissions

The plugin includes built-in permission controls:
- **Access Settings Permission**: `key.whitelist.access_settings`
- Restrict who can view and modify IP whitelist settings
- Assign permissions via **Settings → Administrators → Manage Roles**

## Safety & Security

### Built-in Safety Features

- **Spoof-Proof Client IP**: Forwarding headers are ignored unless the request comes from a trusted proxy
- **Plugin Control**: Instant disable capability
- **Clear Error Messages**: Users see their IP and helpful information
- **Automatic Failsafe**: If no IPs configured, access is allowed
- **Permission Protected**: Settings access controlled via role-based permissions

### Client IP Detection

Headers such as `X-Forwarded-For`, `X-Real-IP` and `CF-Connecting-IP` are sent by the client and can be forged. The plugin therefore uses the connecting address (`Request::ip()`, which also honours your application's own TrustProxies configuration) by default.

When the site runs behind Cloudflare, a load balancer or another reverse proxy, add the addresses of those proxies under **Trusted Proxies**. For requests from a trusted proxy, the plugin walks `X-Forwarded-For` from right to left and uses the first address that is not a trusted proxy.

For Cloudflare, use the published ranges from https://www.cloudflare.com/ips/.

The backend path is compared after URL decoding and per path segment, so `/%62ackend` or `//backend` cannot skip the check.

## Troubleshooting

### Locked Out?

#### Quick Recovery Options

1. **Method 1 - Emergency Access** (Recommended):
   - If emergency access is enabled, click the discreet "Need access?" link on the blocked page
   - Admin receives email with approval link
   - Click approval link to whitelist your IP

2. **Method 2 - Plugin Disable**:
   - Disable plugin in Settings → System → Updates → Manage plugins

3. **Method 3 - File System**:
   - Temporarily rename the plugin folder

4. **Method 4 - Cache Clear**:
   - Run: `php artisan cache:clear`

#### Emergency Access Not Working?

- Verify emergency access is enabled in settings
- Check admin email addresses are configured
- Look in spam folder for approval emails
- Verify October CMS mail configuration is working
- Check database for pending requests: `key_whitelist_emergency_access` table

### Testing Checklist

1. ✅ Add your current IP to whitelist
2. ✅ Save settings
3. ✅ Enable whitelist
4. ✅ Test from different IP/device
5. ✅ Behind a proxy or CDN: add it under Trusted Proxies and check that the blocked page shows your real IP

## Technical Details

### File Structure
```
plugins/key/whitelist/
├── Plugin.php                                # Main plugin registration & routes
├── middleware/WhitelistMiddleware.php        # IP validation & emergency access check
├── controllers/EmergencyAccess.php           # Emergency access request handler
├── models/
│   ├── Settings.php                          # Settings with IP parsing & validation
│   └── EmergencyAccess.php                   # Emergency access request model
├── console/CleanupExpiredTokens.php          # Artisan command for token cleanup
├── lang/                                     # Internationalization files
│   ├── en/lang.php                           # English translations
│   ├── nl/lang.php                           # Dutch translations
│   ├── de/lang.php                           # German translations
│   └── fr/lang.php                           # French translations
├── views/
│   ├── blocked.htm                           # Professional error page
│   └── mail/
│       ├── emergency_access_request.htm      # HTML email template
│       └── emergency_access_request.txt      # Plain text email template
├── updates/
│   ├── version.yaml                          # Version tracking
│   └── create_emergency_access_table.php     # Emergency access database migration
├── EMERGENCY_ACCESS.md                       # Complete emergency access documentation
└── README.md                                 # This file
```

### Internationalization

The plugin supports multiple languages:
- **English** (en) - Default
- **Dutch** (nl) - Nederlands
- **German** (de) - Deutsch
- **French** (fr) - Français

All interface text, field labels, and help text are fully translatable.

### Console Commands

```bash
# Clean up expired emergency access tokens (runs daily at 3am automatically)
php artisan whitelist:cleanup-tokens
```

### Routes

The plugin registers the following public routes for emergency access:

- `GET /whitelist/emergency-access/request` - Create access request
- `GET /whitelist/emergency-access/approve/{token}` - Approve request via token

### Requirements
- October CMS 3.x or 4.x
- PHP 8.2+
- Configured mail settings (for emergency access emails)

## Changelog

The full version history is in [`updates/version.yaml`](updates/version.yaml) and on the **Release Notes** tab of the plugin settings.

### 1.1.2 (2026-11-24)
- Added this changelog and corrected the release notes history (dates and 1.0.1/1.0.2 content).

### 1.1.1 (2026-11-24) - security release
- Forwarding headers (CF-Connecting-IP, X-Real-IP, X-Forwarded-For, Client-IP) are no longer trusted by default, so the client IP can no longer be spoofed.
- New **Trusted Proxies** setting: X-Forwarded-For is only read for requests from these addresses.
- The backend path check decodes the URL, so percent-encoded paths (such as `/%62ackend`) can no longer skip the whitelist.
- Emergency access requests use the same client IP resolution as the whitelist.
- **Always Allow Localhost** is now off by default for new installations.
- Removed the `composer/installers` requirement; October CMS already includes it.
- **Upgrade note:** behind Cloudflare or a load balancer, add its addresses under Trusted Proxies. Existing settings are kept, so check whether Always Allow Localhost should stay on in production.

### 1.1.0 (2025-10-07)
- Emergency access system for IP whitelist recovery when locked out: email requests, token-based approval (manual or automatic), configurable expiry, spam prevention and a daily token cleanup (`php artisan whitelist:cleanup-tokens`).

### 1.0.3 (2025-09-30)
- Release Notes tab on the settings page with the full version history.

### 1.0.2 (2025-09-30)
- Role-based permissions for settings access.
- Translations for English, Dutch, German and French.

### 1.0.1 (2025-09-16)
- Improved IP detection for load balancers and proxy servers.

### 1.0.0 (2025-09-16)
- Initial release: backend IP whitelist with IPv4/IPv6 addresses and CIDR ranges.

## Additional Documentation

- **[EMERGENCY_ACCESS.md](EMERGENCY_ACCESS.md)** - Complete emergency access feature documentation
- **[CLAUDE.md](CLAUDE.md)** - Developer guidance for Claude Code assistant

## Credits

**Developed with ❤️ by [Key Agency](https://key.agency)**

Key Agency specializes in:
- October CMS Development
- Laravel Applications
- Custom Plugin Development
- E-commerce Solutions
- Performance Optimization

For professional October CMS development services, contact [Key Agency](https://key.agency).

## License

This plugin is provided for October CMS projects. Use responsibly in production environments.

---

**Need custom October CMS development?** [Contact Key Agency](https://key.agency) for expert consultation and development services.