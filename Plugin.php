<?php namespace Key\Whitelist;

use Backend;
use System\Classes\PluginBase;
use System\Classes\SettingsManager;
use Key\Whitelist\Middleware\WhitelistMiddleware;

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
            'name'        => 'Backend IP Whitelist',
            'description' => 'Restricts backend access to whitelisted IP addresses and ranges.',
            'author'      => 'Key',
            'icon'        => 'icon-shield',
            'homepage'    => ''
        ];
    }

    /**
     * Register plugin settings
     */
    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'IP Whitelist',
                'description' => 'Configure IP addresses and ranges allowed to access the backend.',
                'category'    => 'Security',
                'icon'        => 'icon-shield',
                'class'       => 'Key\Whitelist\Models\Settings',
                'order'       => 500,
                'keywords'    => 'security ip whitelist backend access'
            ]
        ];
    }

    /**
     * Boot method, called right before the request route.
     */
    public function boot()
    {
        // Apply middleware to all requests, but filter inside middleware
        $this->app['router']->pushMiddlewareToGroup('web', WhitelistMiddleware::class);
    }
}