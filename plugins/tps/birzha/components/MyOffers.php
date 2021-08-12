<?php namespace TPS\Birzha\Components;

use Cms\Classes\ComponentBase;
use TPS\Birzha\Models\Product;
use Input;

class MyOffers extends ComponentBase
{
    /**
     * @var Collection A collection of user's posts
     */
    public $offers;
    
    /**
     * today's date
     */
    public $today;
    
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

    public function onDeleteOffer() {
        $product = Product::find(Input::get('deleting_product_id'));
        $product->images()->delete();
        $product->translations()->delete();
        $product->delete();

        return \Redirect::back();
    }

    public function onRun() {
        $this->offers = $this->loadOffers();
        $this->today = \Carbon\Carbon::now();
    }

    protected function loadOffers() {
        $perPage = $this->property('perPage');

        return \Auth::user()->products()
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    
}
