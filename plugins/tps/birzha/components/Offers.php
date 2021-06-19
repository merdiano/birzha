<?php namespace TPS\Birzha\Components;

use Cms\Classes\ComponentBase;
use TPS\Birzha\Models\Offer;
use TPS\Birzha\Models\Category;
use Session;

class Offers extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Offers List',
            'description' => 'List of offers'
        ];
    }

    public function defineProperties()
    {
        return [
            'categorySlug' => [
                'title' => 'Select by category :slug',
                'description' => 'Select by category',
                'type' => 'string',
                'default' => ''
            ],
            'perPage' => [
                'title' => 'Number of offers',
                'description' => 'How many offers do you want to display',
                'default' => 0,
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'Only numbers allowed'
            ],
            'sortOrder' => [
                'title' => 'Sort offers',
                'description' => 'How to sort offers',
                'type' => 'dropdown',
                'default' => 'desc'
            ],
        ];
    }

    public function getSortOrderOptions() {
        return [
            'asc' => 'Created date (ascending)',
            'desc' => 'Created date (descending)'
        ];
    }

    public function onRun() {
        $this->offers = $this->loadOffers();
    }

    protected function loadOffers() {
        $sortOrder = $this->property('sortOrder');
        $cSlug = $this->property('categorySlug');
        $perPage = $this->property('perPage');

        $query = Offer::where('status', 'approved')->orderBy('created_at', $sortOrder)->paginate($perPage);

        if($cSlug != '') {
            $category = Category::transWhere('slug', $cSlug, Session::get('rainlab.translate.locale'))->first();
            if($category) {
                $offersIds = array();

                $productsOfOneCategory = $category->products; //categories have many products

                foreach($productsOfOneCategory as $p) {
                    foreach($p->offers as $of) { //but only one product can have many offers and one offer can have just one product
                        $offersIds[] = $of->id;
                    }
                }
                $query = Offer::whereIn('id',$offersIds)->where('status','approved')->orderBy('created_at', $sortOrder)->paginate($perPage);
            } else {
                $query = null;
            }
        }

        return $query;
    }

    public $offers;
}
