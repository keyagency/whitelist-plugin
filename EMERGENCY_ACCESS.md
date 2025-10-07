# Emergency Access Feature

## Overview

The emergency access feature provides a secure way to regain access to your site if you accidentally lock yourself out with the IP whitelist.

## How It Works

### 1. Configuration

Navigate to **Settings → Security → IP Whitelist → Emergency Access** tab:

- **Enable Emergency Access**: Turn on the feature
- **Admin Email Addresses**: Enter email addresses (one per line) that will receive access requests
- **Token Duration**: How long the access token remains valid (default: 24 hours)
- **Require Manual Approval**:
  - **Enabled (recommended)**: Admin must click approval link in email
  - **Disabled**: IP is added automatically when request is made
- **Access Request Button Text**: Customize the button text (default: "Need access?") - keep it discreet to avoid spam

### 2. Requesting Access

When a user is blocked by the IP whitelist:

1. They see the blocked page with their IP address
2. At the bottom of the page, a discreet link appears (if emergency access is enabled)
3. Clicking the link creates an access request and logs the IP address
4. An email is sent to all configured admin addresses

### 3. Email Notification

Admins receive an email with:
- The requesting IP address
- Timestamp of the request
- Token expiration time
- **Approve Access Request** button

### 4. Approval Process

#### Manual Approval Mode (Recommended)
1. Admin receives email notification
2. Admin clicks "Approve Access Request" link
3. IP is permanently added to the whitelist
4. User can now access the site

#### Auto-Approval Mode
1. Request is made
2. IP is immediately added to whitelist
3. Email is sent for logging purposes only

### 5. Token Expiration

- Tokens expire after the configured duration (default: 24 hours)
- Expired tokens are automatically cleaned up daily at 3:00 AM
- Manual cleanup: `php artisan whitelist:cleanup-tokens`

## Security Features

### Discreet Access Request
- Button text is customizable
- Appears as a small, subtle link at the bottom of the blocked page
- Reduces spam/abuse potential

### Token-Based Approval
- Each request generates a unique, cryptographically secure token
- Tokens are single-use and time-limited
- Token URLs cannot be guessed or brute-forced (64 character hex string)

### Request Tracking
- All requests are logged in the database
- Tracks IP, timestamp, status (pending/approved/denied/expired)
- Automatic cleanup of expired entries

### Email Validation
- Only valid email addresses receive notifications
- Multiple admin emails supported for redundancy

### No Duplicate Requests
- Prevents multiple pending requests from the same IP
- User sees a warning if request already exists

## Routes

The feature adds two public routes:

- `GET /whitelist/emergency-access/request` - Create access request
- `GET /whitelist/emergency-access/approve/{token}` - Approve request

These routes are accessible even when IP whitelist is active.

## Database

New table: `key_whitelist_emergency_access`

Fields:
- `id` - Primary key
- `ip_address` - Requesting IP (indexed)
- `token` - Unique approval token (64 chars, indexed)
- `status` - pending/approved/denied/expired (indexed)
- `expires_at` - Token expiration timestamp (indexed)
- `approved_at` - Approval timestamp
- `created_at` - Request timestamp
- `updated_at` - Last update

## Commands

### Cleanup Expired Tokens
```bash
php artisan whitelist:cleanup-tokens
```

Automatically runs daily at 3:00 AM via Laravel scheduler.

## Email Templates

Located in `views/mail/`:
- `emergency_access_request.htm` - HTML email template
- `emergency_access_request.txt` - Plain text email template

Customize these templates to match your branding.

## Workflow Example

### Scenario: Admin Accidentally Locks Themselves Out

1. Admin enables IP whitelist but forgets to add their current IP
2. Admin is immediately blocked from accessing the site
3. Admin sees the blocked page with "Need access?" link
4. Admin clicks link → request is created
5. Admin receives email to their configured address
6. Admin clicks "Approve Access Request" in email
7. Their IP is added to whitelist
8. Admin can now access the site normally
9. Admin logs into backend and properly configures whitelist

## Best Practices

### Security
- Always use **manual approval mode** in production
- Use a discreet button text like "Support" or "Help" instead of "Request Access"
- Configure multiple admin emails for redundancy
- Regularly review emergency access logs
- Use a short token duration (1-4 hours) for high-security sites

### Configuration
- Test the feature before you need it
- Keep admin emails up to date
- Document the process for your team
- Consider using a monitored email address

### Monitoring
- Check logs periodically: `key_whitelist_emergency_access` table
- Watch for suspicious patterns (multiple requests from different IPs)
- Set up email filtering rules to prioritize emergency access emails

## Troubleshooting

### "Emergency access is not enabled"
- Go to Settings → IP Whitelist → Emergency Access
- Enable the feature and configure admin emails

### "An access request for this IP address is already pending"
- Check your email for the previous request
- Wait for token to expire (check token duration setting)
- Admin can manually delete pending request from database if needed

### Not receiving emails
- Verify email addresses are correct in settings
- Check spam folder
- Verify October CMS mail configuration is working
- Test with `php artisan tinker` → `Mail::send(...)`

### Token expired
- Tokens have limited lifetime (default: 24 hours)
- User must create a new request
- Consider increasing token duration if this happens frequently

## Migration

Run migration to create the database table:

```bash
php artisan october:migrate
```

This will create the `key_whitelist_emergency_access` table.
