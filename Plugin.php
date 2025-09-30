<?php namespace Key\Whitelist;

use System\Classes\PluginBase;
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
            'name'        => 'key.whitelist::lang.plugin.name',
            'description' => 'key.whitelist::lang.plugin.description',
            'author'      => 'Key',
            'icon'        => 'icon-shield',
            'homepage'    => '',
            'version'     => '1.0.3'
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
     * Boot method, called right before the request route.
     */
    public function boot()
    {
        // Apply middleware to all requests, but filter inside middleware
        $this->app['router']->pushMiddlewareToGroup('web', WhitelistMiddleware::class);
    }
}