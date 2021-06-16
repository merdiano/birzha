<?php namespace TPS\Birzha;

use Backend;
use System\Classes\PluginBase;
use TPS\Birzha\Models\Category;
use TPS\Birzha\Models\Product;
use Event;
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
        /*
         * Register menu items for the RainLab.Pages plugin
         */
        Event::listen('pages.menuitem.listTypes', function() {
            return [
                'category'          => 'Category',
                'all-categories'    => 'All Categories',
                'all-products'      => 'All products',
                'category-products' => 'Category Products',
            ];
        });

        Event::listen('pages.menuitem.getTypeInfo', function($type) {
            if ($type == 'category' || $type == 'all-categories') {
                return Category::getMenuTypeInfo($type);
            }
            elseif ($type == 'product' || $type == 'all-products' || $type == 'category-products') {
                return Product::getMenuTypeInfo($type);
            }
        });

        Event::listen('pages.menuitem.resolveItem', function($type, $item, $url, $theme) {
            if ($type == 'category' || $type == 'all-categories') {
                return Category::resolveMenuItem($item, $url, $theme);
            }
            elseif ($type == 'product' || $type == 'all-products' || $type == 'category-products') {
                return Product::resolveMenuItem($item, $url, $theme);
            }
        });
    }

    /**
     * @return array
     */
    public function registerSettings()
    {
        return [
            'config' => [
                'label'       => 'Application Settings',
                'icon'        => 'icon-cogs',
                'description' => 'Site general settings',
                'class'       => 'TPS\Birzha\Models\Settings',
                'order'       => 300,
                'permissions' => [
                    'toolbox-menu-settings',
                ],
            ],
        ];
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return [
            'TPS\Birzha\Components\Categories' => 'categories',
            'TPS\Birzha\Components\Auth' => 'auth',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
//    public function registerPermissions()
//    {
//        return []; // Remove this line to activate
//
//        return [
//            'tps.birzha.some_permission' => [
//                'tab' => 'Birzha',
//                'label' => 'Some permission'
//            ],
//        ];
//    }
//
//    /**
//     * Registers back-end navigation items for this plugin.
//     *
//     * @return array
//     */
//    public function registerNavigation()
//    {
//        return []; // Remove this line to activate
//
//        return [
//            'birzha' => [
//                'label'       => 'Birzha',
//                'url'         => Backend::url('tps/birzha/mycontroller'),
//                'icon'        => 'icon-leaf',
//                'permissions' => ['tps.birzha.*'],
//                'order'       => 500,
//            ],
//        ];
//    }
}
