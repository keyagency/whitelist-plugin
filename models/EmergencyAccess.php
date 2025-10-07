<?php namespace Key\Whitelist\Models;

use Model;
use Str;
use Carbon\Carbon;

/**
 * EmergencyAccess Model
 */
class EmergencyAccess extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'key_whitelist_emergency_access';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'ip_address',
        'token',
        'status',
        'expires_at',
        'approved_at',
    ];

    /**
     * @var array Date fields
     */
    protected $dates = [
        'expires_at',
        'approved_at',
        'created_at',
        'updated_at',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_DENIED = 'denied';
    const STATUS_EXPIRED = 'expired';

    /**
     * Generate a secure token for emergency access
     */
    public static function generateToken(): string
    {
        return bin2hex(random_bytes(32));
    }

    /**
     * Create a new emergency access request
     */
    public static function createRequest(string $ipAddress, int $hoursValid = 24): self
    {
        $token = self::generateToken();

        return self::create([
            'ip_address' => $ipAddress,
            'token' => $token,
            'status' => self::STATUS_PENDING,
            'expires_at' => Carbon::now()->addHours($hoursValid),
        ]);
    }

    /**
     * Check if there's a pending request for this IP
     */
    public static function hasPendingRequest(string $ipAddress): bool
    {
        return self::where('ip_address', $ipAddress)
            ->where('status', self::STATUS_PENDING)
            ->where('expires_at', '>', Carbon::now())
            ->exists();
    }

    /**
     * Check if an IP has approved access
     */
    public static function hasApprovedAccess(string $ipAddress): bool
    {
        return self::where('ip_address', $ipAddress)
            ->where('status', self::STATUS_APPROVED)
            ->where('expires_at', '>', Carbon::now())
            ->exists();
    }

    /**
     * Find by token
     */
    public static function findByToken(string $token): ?self
    {
        return self::where('token', $token)->first();
    }

    /**
     * Approve the access request
     */
    public function approve(): bool
    {
        if ($this->isExpired()) {
            $this->status = self::STATUS_EXPIRED;
            $this->save();
            return false;
        }

        $this->status = self::STATUS_APPROVED;
        $this->approved_at = Carbon::now();
        $this->save();

        // Add IP to whitelist
        $this->addToWhitelist();

        return true;
    }

    /**
     * Deny the access request
     */
    public function deny(): void
    {
        $this->status = self::STATUS_DENIED;
        $this->save();
    }

    /**
     * Check if the token is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at < Carbon::now();
    }

    /**
     * Add IP to the whitelist settings
     */
    protected function addToWhitelist(): void
    {
        $settings = Settings::instance();
        $currentIps = $settings->allowed_ips ?: '';

        // Check if IP already exists
        $ips = array_filter(array_map('trim', explode("\n", $currentIps)));
        if (!in_array($this->ip_address, $ips)) {
            $ips[] = $this->ip_address;
            $settings->allowed_ips = implode("\n", $ips);
            $settings->save();
        }
    }

    /**
     * Clean up expired requests
     */
    public static function cleanupExpired(): int
    {
        return self::where('expires_at', '<', Carbon::now())
            ->whereIn('status', [self::STATUS_PENDING, self::STATUS_APPROVED])
            ->update(['status' => self::STATUS_EXPIRED]);
    }
}
