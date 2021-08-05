<?php namespace TPS\Birzha\Components;

use Cms\Classes\ComponentBase;
// use TPS\Birzha\Models\Offer;
use TPS\Birzha\Models\Category;
use TPS\Birzha\Models\Product;
use Session;
use DB;

class Offers extends ComponentBase
{
    /*
    * sort order parametr in a url string
    */
    public $sortParam = '';

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
            'productSlug' => [
                'title' => 'Product Slug',
                'description' => 'Similar offers (the same product)',
                'type' => 'string',
                'default' => ''
            ],
            'offerId' => [
                'title' => 'Offer id',
                'description' => 'Offer id',
                'type' => 'string',
                'default' => ''
            ]
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
        $sortOrderParam = strtolower(\Input::get('sort_order'));
        
        // protect from sql injection
        if($sortOrderParam != 'asc' && $sortOrderParam != 'desc') {
            $sortOrder = $this->property('sortOrder');
        } else {
            $sortOrder = $sortOrderParam;
            $this->sortParam = $sortOrderParam;
        }
        
        $cSlug = $this->property('categorySlug');
        $perPage = $this->property('perPage');
        $productSlug = $this->property('productSlug');
        $offerId = $this->property('offerId');

        $query = Product::where('status', 'approved')->where('ends_at','>=',DB::raw('curdate()'))->orderBy('created_at', $sortOrder)->paginate($perPage);

        if($cSlug != '') { //fetch offers by the category of the product
            $category = Category::transWhere('slug', $cSlug, Session::get('rainlab.translate.locale'))->first();
            if($category) {
                $offersIds = array();

                $query = $category->products()->where('status','approved')->where('ends_at','>=',DB::raw('curdate()'))->orderBy('created_at', $sortOrder)->paginate($perPage); //categories have many products

                // foreach($productsOfOneCategory as $p) {
                //     foreach($p->offers as $of) { //but only one product can have many offers and one offer can have just one product
                //         $offersIds[] = $of->id;
                //     }
                // }
                // $query = Offer::whereIn('id',$offersIds)->where('status','approved')->where('ends_at','>=',DB::raw('curdate()'))->orderBy('created_at', $sortOrder)->paginate($perPage);
            } else {
                $query = null;
            }
        }

        if($productSlug != '' && $offerId != '') { // fetch offers with similar products
            $product = Product::transWhere('slug', $productSlug, Session::get('rainlab.translate.locale'))->first();
            if($product) {
                $query = Product::where('id','!=',$offerId)->where('status','approved')->where('ends_at','>=',DB::raw('curdate()'))->orderBy('created_at', $sortOrder)->paginate($perPage);
            } else {
                $query = null;
            }
        }

        return $query;
    }

    public $offers;
}
