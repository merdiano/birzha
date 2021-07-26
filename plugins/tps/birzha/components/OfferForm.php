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
use Tps\Birzha\Models\Category;
use Tps\Birzha\Models\Country;
use TPS\Birzha\Models\Settings;
use TPS\Birzha\Models\Payment;
use Flash;
use Session;
use DB;
use Str;
use ValidationException;
use October\Rain\Network\Http;
use October\Rain\Exception\AjaxException;
use Carbon\Carbon;

class OfferForm extends ComponentBase 
{
    /**
     * @var string A collection of categories in dropdown
     */
    public $categories;
    
    /**
     * @var string A collection of countries in dropdown
     */
    public $countries;

    public function componentDetails() {
        return [
            'name' => 'Offer Form',
            'description' => 'Add offer'
        ];
    }

    // step1
    public function onSave() {
        $data = post();

        $rules = [
            'name_ru' => 'required',
            'name_en' => 'required',
            'name_tm' => 'required',
            'category_id' => 'exists:tps_birzha_categories,id',
            'mark' => 'required',
            'manufacturer' => 'required',
            'country_id' => 'exists:tps_birzha_countries,id'
        ];

        $this->validateForm($data, $rules);
        
        $category = Category::find($data['category_id']);
            
        $product = new Product;
        $product->name = $data['name_tm'];
        // Sets a single translated attribute for a language
        $product->setAttributeTranslated('name', $data['name_ru'], 'ru');
        $product->setAttributeTranslated('name', $data['name_en'], 'en');
        // Sets a single translated attribute for a language
        $product->setAttributeTranslated('slug', Str::slug($data['name_ru'],'-'), 'ru');
        $product->setAttributeTranslated('slug', Str::slug($data['name_en'],'-'), 'en');

        $product->slug = Str::slug($data['name_tm'],'-');
        $product->status = 'draft';
        $product->mark = $data['mark'];
        $product->manufacturer = $data['manufacturer'];
        $product->country_id = $data['country_id'];
        $product->created_at = Carbon::now('Asia/Ashgabat');
        $product->vendor_id = \Auth::user()->id;

        $category->products()->save($product);

        // go to next step - next form
        $this->page['measures'] = Measure::all();
        $this->page['paymentTerms'] = Term::where('type','payment')->get();
        $this->page['deliveryTerms'] = Term::where('type','delivery')->get();
        $this->page['currencies'] = Currency::all();
        $this->page['product'] = $product;

        return [
            '#form-steps' => $this->renderPartial('@second_step_form')
        ];

    }

    // step 2
    public function onOfferFill() {
        $data = input();

        $rules = [
            'quantity' => 'required|numeric',
            'price' => 'required|numeric',
            'place' => 'required',
            'description_tm' => 'required',
            'description_en' => 'required',
            'description_ru' => 'required',
            // 'ends_at' => 'required|date',
            'payment_term_id' => 'required',
            'packaging' => 'required',
            'delivery_term_id' => 'required',
            'currency_id' => 'required',
            'measure_id' => 'required',
            'new_img' => 'required'
        ];
        $this->validateForm($data, $rules);

        // seaparate validation for file type
        $rules = [
            'new_img.*' => 'mimes:jpg,png'
        ];
        $this->validateFileType($data, $rules);
        
        // separate validation for image size
        $rules = [
            'new_img.*' => 'max:1024'
        ];
        $this->validateImageSize($data, $rules);

        $attachedProduct = Product::find($data['product_id']);

        $attachedProduct = $this->fillProduct($data,$attachedProduct);
    
        // add images to completely new product
        foreach($data['new_img'] as $key => $img) {
            $attachedProduct->images = $img;
            $attachedProduct->save();
        }

        $this->page['fee'] = Settings::getValue('fee');
        $this->page['product'] = $attachedProduct;

        return [
            '#form-steps' => $this->renderPartial('@third_step_form')
        ];
    }

    // step3
    public function onPublish() {
        $user = \Auth::user();
        if($user->balance - Settings::getValue('fee') < 0) {
            // ... message about not enough money
        } else {
            $user->balance = $user->balance - Settings::getValue('fee');
            $user->save();

            $product = Product::find(Input::get('product_id'));
            //save how much user payed because fee can be changed by admin tomorrow
            // if post is denied we get back payed fee, not admin's set fee
            $product->payed_fee_for_publ = Settings::getValue('fee');
            $product->status = 'new';
            $product->save();

            return [
                '#form-steps' => $this->renderPartial('@message')
            ];
        }
    }

    protected function validateFileType($data, $rules) {
        $validator = Validator::make($data, $rules);

        if($validator->fails()) {
            throw new ValidationException(['new_img_type_error' => 'You must upload jpg,png!']);
        }
    }

    protected function validateImageSize($data, $rules) {
        $validator = Validator::make($data, $rules);

        if($validator->fails()) {
            throw new ValidationException(['new_img_size_error' => 'Max file size 1 Mb!']);
        }
    }

    protected function validateForm($data, $rules) {
        $validator = Validator::make($data, $rules);

        if($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    protected function fillProduct($data,$attachedProduct) {
        $attachedProduct->description = $data['description_tm'];
        // Sets a single translated attribute for a language
        $attachedProduct->setAttributeTranslated('description', $data['description_ru'], 'ru');
        $attachedProduct->setAttributeTranslated('description', $data['description_en'], 'en');

        $attachedProduct->quantity = $data['quantity'];
        $attachedProduct->price = $data['price'];
        $attachedProduct->measure_id = $data['measure_id'];
        $attachedProduct->payment_term_id = $data['payment_term_id'];
        $attachedProduct->delivery_term_id = $data['delivery_term_id'];
        $attachedProduct->packaging = $data['packaging'];
        $attachedProduct->place = $data['place'];
        $attachedProduct->currency_id = $data['currency_id'];
        // $attachedProduct->ends_at = $data['ends_at'];
        $attachedProduct->save();

        return $attachedProduct;
    }

    public function onRun() {
        $this->countries = Country::all();
        $this->categories = Category::select('id','name','status')->where('status',1)->get();
    }
} 