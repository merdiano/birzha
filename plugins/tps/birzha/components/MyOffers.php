<?php namespace TPS\Birzha\Components;

use Cms\Classes\ComponentBase;
use TPS\Birzha\Models\Product;

class MyOffers extends ComponentBase
{
    /**
     * @var Collection A collection of user's posts
     */
    public $offers;
    
    public function componentDetails()
    {
        return [
            'name'        => 'MyOffers List',
            'description' => 'List of my offers'
        ];
    }

    public function defineProperties()
    {
        return [
            'perPage' => [
                'title' => 'Number of offers',
                'description' => 'How many offers do you want to display',
                'default' => 0,
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'Only numbers allowed'
            ],
            // 'productSlug' => [
            //     'title' => 'Product Slug',
            //     'description' => 'Similar offers (the same product)',
            //     'type' => 'string',
            //     'default' => ''
            // ],
            // 'offerId' => [
            //     'title' => 'Offer id',
            //     'description' => 'Offer id',
            //     'type' => 'string',
            //     'default' => ''
            // ]
        ];
    }

    public function onRun() {
        $this->offers = $this->loadOffers();
    }

    protected function loadOffers() {
        $perPage = $this->property('perPage');
        // $productSlug = $this->property('productSlug');
        // $offerId = $this->property('offerId');

        // $user = \Auth::user();
        // $query = $user->products()->query();
// orderBy('created_at', $sortOrder)->paginate($perPage);
        // $query = query()->

        // if($productSlug != '' && $offerId != '') { // fetch offers with similar products
        //     $product = Product::transWhere('slug', $productSlug, Session::get('rainlab.translate.locale'))->first();
        //     if($product) {
        //         $query = Product::where('id','!=',$offerId)->where('status','approved')->where('ends_at','>=',DB::raw('curdate()'))->orderBy('created_at', $sortOrder)->paginate($perPage);
        //     }
        // }

        return \Auth::user()->products()
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    
}
