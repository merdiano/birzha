<?php namespace TPS\Birzha\Components;

use Cms\Classes\ComponentBase;

class Product extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Product Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }
}
