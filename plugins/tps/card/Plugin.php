<?php namespace TPS\Card;

use Backend;
use System\Classes\PluginBase;

/**
 * CardApplication Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Card',
            'description' => 'Plastic Card applications management',
            'author'      => 'TPS',
            'icon'        => 'icon-credit-card'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {

    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {

        return [
            'TPS\Card\Components\Application' => 'application',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'tps.card.some_permission' => [
                'tab' => 'Card',
                'label' => 'Some permission'
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
//        return []; // Remove this line to activate

        return [
            'card' => [
                'label'       => 'Card Applications',
                'url'         => Backend::url('tps/card/applications'),
                'icon'        => 'icon-list-alt',
                'permissions' => ['tps.card.*'],
                'order'       => 500,
            ],
        ];
    }

    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'Bank API Settings',
                'description' => 'Manage bank api settings.',
                'category'    => 'Applications',
                'icon'        => 'icon-cog',
                'class'       => 'TPS\Card\Models\Settings',
                'order'       => 500,
                'keywords'    => 'bank card',

            ]
        ];
    }
}
