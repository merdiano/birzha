<?php namespace Tps\Birzha\Components;

use Cms\Classes\ComponentBase;
use Input;
use Validator;
use Redirect;
use Tps\Birzha\Models\Offer;
use Tps\Birzha\Models\Measure;
use Tps\Birzha\Models\Term;
use Tps\Birzha\Models\Currency;
use Tps\Birzha\Models\Product;
use Flash;
use Session;

class OfferForm extends ComponentBase 
{
    /**
     * @var string A collection of measures in dropdown
     */
    public $measures;
    
    /**
     * @var string A collection of payment terms in dropdown
     */
    public $paymentTerms;
    
    /**
     * @var string A collection of delivery terms in dropdown
     */
    public $deliveryTerms;
    
    /**
     * @var string A collection of currencies in dropdown
     */
    public $currencies;

    public function componentDetails() {
        return [
            'name' => 'Offer Form',
            'description' => 'Add offer'
        ];
    }

    public function onInput() {
        $query = Input::get('query');
        $locale = Session::get('rainlab.translate.locale');

        if($query != '') {
            if($locale == 'tm') {
                // user enters product name
                $products = Product
                    ::where('name', 'like', "%${query}%")
                    ->where('status','approved')
                    ->get()->toArray();
            } else {
                $queryString = $query;
                
                // user enters product name on ru, en
                $products =  Product::whereHas('translations', function ($query) use ($locale,$queryString) {
                    $query->where('locale', $locale)->where('attribute_data', 'like', "%${queryString}%");
                })->where('status','approved')->get()->toArray();
            }
        } else {
            $products = [];
        }
        
        return [
            'products' => $products
        ];
    }

    public function onSave() {
        $newOffer = new Offer;
        // $newOffer->name = Input::get('name');
        // $newActor->lastname = Input::get('lastname');
        // $newActor->save();
        // Flash::success('Actor added');
        return Redirect::back();
    }

    public function onRun() {
        $this->measures = Measure::all();
        $this->paymentTerms = Term::where('type','payment')->get();
        $this->deliveryTerms = Term::where('type','delivery')->get();
        $this->currencies = Currency::all();
        // dd(auth()->user());
    }
} 