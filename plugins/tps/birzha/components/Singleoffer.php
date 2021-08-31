<?php namespace TPS\Birzha\Components;

use Cms\Classes\ComponentBase;
use TPS\Birzha\Models\Product;

class Singleoffer extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Single offer',
            'description' => 'Selected offer'
        ];
    }

    public function defineProperties()
    {
        return [
            'productSlug' => [
                'title' => 'Product slug',
                'description' => 'Product slug',
                'type' => 'string',
                'default' => '{{ :slug }}'
            ],
            'offerId' => [
                'title' => 'Offer id',
                'description' => 'Offer id',
                'type' => 'string',
                'default' => '{{ :id }}'
            ]
        ];
    }

    public function onRun() {
        $this->offer = $this->loadOffer();
    }

    protected function loadOffer() {

        $product = Product::find($this->property('offerId'));

        if($product && $product->status == 'approved' && $product->ends_at >= \Carbon\Carbon::now()) {
            return $product;
        }
        return null;
    }

    public $offer;
}