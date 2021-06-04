<?php namespace TPS\Birzha;

use Backend;
use System\Classes\PluginBase;

/**
 * Birzha Plugin Information File
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
            'name'        => 'Birzha',
            'description' => 'No description provided yet...',
            'author'      => 'TPS',
            'icon'        => 'icon-leaf'
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
        return []; // Remove this line to activate

        return [
            'TPS\Birzha\Components\MyComponent' => 'myComponent',
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
            'tps.birzha.some_permission' => [
                'tab' => 'Birzha',
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
        return []; // Remove this line to activate

        return [
            'birzha' => [
                'label'       => 'Birzha',
                'url'         => Backend::url('tps/birzha/mycontroller'),
                'icon'        => 'icon-leaf',
                'permissions' => ['tps.birzha.*'],
                'order'       => 500,
            ],
        ];
    }
}
