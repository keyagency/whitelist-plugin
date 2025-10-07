<?php namespace Key\Whitelist\Console;

use Illuminate\Console\Command;
use Key\Whitelist\Models\EmergencyAccess;

/**
 * CleanupExpiredTokens Command
 *
 * This command cleans up expired emergency access tokens
 */
class CleanupExpiredTokens extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'whitelist:cleanup-tokens';

    /**
     * @var string The console command description.
     */
    protected $description = 'Clean up expired emergency access tokens';

    /**
     * Execute the console command.
     * @return int
     */
    public function handle()
    {
        $this->info('Cleaning up expired emergency access tokens...');

        $count = EmergencyAccess::cleanupExpired();

        if ($count > 0) {
            $this->info("Successfully cleaned up {$count} expired token(s).");
        } else {
            $this->info('No expired tokens found.');
        }

        return 0;
    }
}
