<?php namespace TPS\Birzha;

use Backend;
use System\Classes\PluginBase;
use TPS\Birzha\Models\Category;
use TPS\Birzha\Models\Product;
use TPS\Birzha\Models\Offer;
use Event;
use Session;
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

        \Event::listen('offline.sitesearch.query', function ($query) {

            // The controller is used to generate page URLs.
            $controller = \Cms\Classes\Controller::getController() ?? new \Cms\Classes\Controller();

            // Search your plugin's contents
            
            $locale = Session::get('rainlab.translate.locale');
            
            if($locale == 'tm') {
                // user enters product name
                $items = Models\Product
                    ::where('name', 'like', "%${query}%")
                    ->where('status','approved')
                    ->where('ends_at','>=',\DB::raw('curdate()'))->orderBy('created_at', 'desc')
                    ->get();
            } else {
                $queryString = $query;
                
                // user enters product name
                $items =  Models\Product::whereHas('translations', function ($query) use ($locale,$queryString) {
                    $query->where('locale', $locale)->where('attribute_data', 'like', "%${queryString}%");
                })
                    ->where('status','approved')
                    ->where('ends_at','>=',\DB::raw('curdate()'))->orderBy('created_at', 'desc')
                    ->get();
            }
            
            
            // show all offers that have that product
            // $items = collect(new Offer);
            // foreach($products as $p) {
            //     foreach($p->offers()->where('status','approved')->get() as $of) {
            //         $items->add($of);
            //     }
            // }

            // Now build a results array
            $results = $items->map(function ($item) use ($query, $controller) {

                // If the query is found in the title, set a relevance of 2
                $relevance = mb_stripos($item->title, $query) !== false ? 2 : 1;

                // Optional: Add an age penalty to older results. This makes sure that
                // newer results are listed first.
                // if ($relevance > 1 && $item->created_at) {
                //    $ageInDays = $item->created_at->diffInDays(\Illuminate\Support\Carbon::now());
                //    $relevance -= \OFFLINE\SiteSearch\Classes\Providers\ResultsProvider::agePenaltyForDays($ageInDays);
                // }

                return [
                    'title'     => $item->name,
                    'url'       => $controller->pageUrl('offer', ['slug' => $item->slug, 'id' => $item->id]),
                    // 'thumb'     => optional($item->product->images)->first(), // Instance of System\Models\File
                    'relevance' => $relevance, // higher relevance results in a higher
                                            // position in the results listing
                    // 'meta' => 'data',       // optional, any other information you want
                                            // to associate with this result
                    'model' => $item,       // optional, pass along the original model
                ];
            });

            return [
                'provider' => 'Offers', // The badge to display for this result
                'results'  => $results,
            ];
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
            'TPS\Birzha\Components\Sliders' => 'sliders',
            'TPS\Birzha\Components\Offers' => 'offers',
            'TPS\Birzha\Components\Singleoffer' => 'singleoffer',
            'TPS\Birzha\Components\OfferForm' => 'offerform',
            'TPS\Birzha\Components\Messages' => 'messages',
            'TPS\Birzha\Components\PaymentApi' => 'paymentapi'
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
