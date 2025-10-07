<?php namespace Key\Whitelist;

use System\Classes\PluginBase;
use Key\Whitelist\Middleware\WhitelistMiddleware;
use Route;

/**
 * Backend IP Whitelist Plugin
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'key.whitelist::lang.plugin.name',
            'description' => 'key.whitelist::lang.plugin.description',
            'author'      => 'Key',
            'icon'        => 'icon-shield',
            'homepage'    => 'https://keyagency.nl',
            'version'     => '1.1.0'
        ];
    }

    /**
     * Register plugin permissions
     */
    public function registerPermissions()
    {
        return [
            'key.whitelist.access_settings' => [
                'tab'   => 'key.whitelist::lang.settings.label',
                'label' => 'key.whitelist::lang.permissions.access_settings'
            ]
        ];
    }

    /**
     * Register plugin settings
     */
    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'key.whitelist::lang.settings.label',
                'description' => 'key.whitelist::lang.settings.description',
                'category'    => 'Security',
                'icon'        => 'icon-shield',
                'class'       => 'Key\Whitelist\Models\Settings',
                'order'       => 500,
                'keywords'    => 'key.whitelist::lang.settings.keywords',
                'permissions' => ['key.whitelist.access_settings']
            ]
        ];
    }

    /**
     * Register scheduled tasks
     */
    public function registerSchedule($schedule)
    {
        // Run token cleanup daily at 3am
        $schedule->command('whitelist:cleanup-tokens')->dailyAt('03:00');
    }

    /**
     * Register console commands
     */
    public function register()
    {
        $this->registerConsoleCommand('whitelist:cleanup-tokens', \Key\Whitelist\Console\CleanupExpiredTokens::class);
    }

    /**
     * Boot method, called right before the request route.
     */
    public function boot()
    {
        // Apply middleware to all requests, but filter inside middleware
        $this->app['router']->pushMiddlewareToGroup('web', WhitelistMiddleware::class);

        // Register emergency access routes
        $this->registerEmergencyAccessRoutes();
    }

    /**
     * Register emergency access routes
     */
    protected function registerEmergencyAccessRoutes()
    {
        Route::group(['prefix' => 'whitelist/emergency-access'], function () {
            Route::get('request', 'Key\Whitelist\Controllers\EmergencyAccess@request');
            Route::get('approve/{token}', 'Key\Whitelist\Controllers\EmergencyAccess@approve');
        });
    }
}