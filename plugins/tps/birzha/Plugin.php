<?php namespace TPS\Birzha;

use Backend;
use RainLab\Notify\NotifyRules\SaveDatabaseAction;

use System\Classes\PluginBase;
use TPS\Birzha\Actions\MailToAdminsAction;
use TPS\Birzha\Actions\SendSMSAction;
use TPS\Birzha\Models\Category;
use TPS\Birzha\Models\Message;
use TPS\Birzha\Models\Product;
use Tps\Birzha\Console\DatabaseBackUp;
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
        $this->registerConsoleCommand('birzha:databasebackup', DatabaseBackUp::class);
    }
    public function registerListColumnTypes()
    {
        return [
            // A local method, i.e $this->evalUppercaseListColumn()
            'status' => [$this, 'statusListColumn'],
            'vendor' => [$this, 'vendorLinkListColumn'],
            'user' => [$this, 'userLinkListColumn'],
            'money' => [$this, 'moneyColumn'],

        ];
    }

    public function moneyColumn($value, $column, $record){
        return '<span style="color: '.($value > 0 ? 'green':'red').'">'.number_format($value,2).' tmt</span>';
    }

    public function userLinkListColumn($value, $column, $record){
        return '<a href="'.Backend::url('rainlab/user/users/preview',$record->user_id).'" class="btn btn-link">'.$value.'</a>';
    }

    public function vendorLinkListColumn($value, $column, $record){
        return '<a href="'.Backend::url('rainlab/user/users/preview',$record->vendor_id).'" class="btn btn-link">'.$value.'</a>';
    }

    public function statusListColumn($value, $column, $record)
    {
        switch ($value){
            case 'draft' : return '<span class="btn btn-default btn-xs">'.$value.'</span>';
            case 'bank' : return '<span class="btn btn-outline-warning btn-xs">'.$value.'</span>';
            case 'online' : return '<span class="btn btn-outline-primary btn-xs">'.$value.'</span>';
            case 'gift' : return '<span class="btn btn-outline-success btn-xs">'.$value.'</span>';
            case 'payed':
            case 'approved' : return '<span class="btn btn-primary btn-xs">'.$value.'</span>';
            case 'new' : return '<span class="btn btn-secondary btn-xs">'.$value.'</span>';
            case 'denied' : return '<span class="btn btn-danger btn-xs bg-s">'.$value.'</span>';
            case 'disabled' : return '<span class="btn btn-outline-danger btn-xs bg-s">'.$value.'</span>';
            default : return $value;
        }

    }

    public function registerSchedule($schedule)
    {
        $schedule->command('birzha:databasebackup')
            ->timezone('America/New_York')
            ->everyMinute();
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
                    ->where('ends_at','>=',\DB::raw('curdate()'))->orderBy('updated_at', 'desc')
                    ->get();
            } else {
                $queryString = $query;

                // user enters product name
                $items =  Models\Product::whereHas('translations', function ($query) use ($locale,$queryString) {
                    $query->where('locale', $locale)->where('attribute_data', 'like', "%${queryString}%");
                })
                    ->where('status','approved')
                    ->where('ends_at','>=',\DB::raw('curdate()'))->orderBy('updated_at', 'desc')
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

        /*
         * Notifications
         */
        \RainLab\Notify\Classes\Notifier::bindEvents([
            'tps.payment.received' => \TPS\Birzha\Events\PaymentRecievedEvent::class,
            'tps.payment.reviewed' => \TPS\Birzha\Events\PaymentReviewedEvent::class,
            'tps.product.received' => \TPS\Birzha\Events\ProductRecievedEvent::class,
            'tps.product.reviewed' => \TPS\Birzha\Events\ProductReviewedEvent::class,
            'tps.message.received' => \TPS\Birzha\Events\MessageReceivedEvent::class,
        ]);
//
//        \RainLab\Notify\Classes\Notifier::instance()->registerCallback(function($manager) {
//            $manager->registerGlobalParams([
//                'user' => Auth::getUser()
//            ]);
//        });
    }


    public function registerNotificationRules()
    {
        return [
            'events' => [
                \TPS\Birzha\Events\PaymentRecievedEvent::class,
                \TPS\Birzha\Events\PaymentReviewedEvent::class,
                \TPS\Birzha\Events\ProductRecievedEvent::class,
                \TPS\Birzha\Events\ProductReviewedEvent::class,
                \TPS\Birzha\Events\MessageReceivedEvent::class,
            ],
            'actions' => [
                SendSMSAction::class,
                MailToAdminsAction::class
            ],
            'conditions' => [
            ],
            'groups' => [
                'payment' => [
                    'label' => 'Payment',
                    'icon' => 'icon-money'
                ],
                'product' => [
                    'label' => 'Product',
                    'icon' => 'icon-cube'
                ],
                'message' => [
                    'label' => 'Message',
                    'icon' => 'icon-envelope'
                ],
            ],
//            'presets' => '$/rainlab/user/config/notify_presets.yaml',
        ];
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
            'TPS\Birzha\Components\PaymentApi' => 'paymentapi',
            'TPS\Birzha\Components\MyOffers' => 'myOffers',
            'TPS\Birzha\Components\Balance' => 'balance',
            'TPS\Birzha\Components\ContactForm' => 'contactForm',
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
