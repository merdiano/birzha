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
use Flash;
use Session;
use DB;
use Str;
use ValidationException;

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

        $this->page['products'] = $products;
        
        return [
            '#suggestions' => $this->renderPartial('@suggestions')
        ];
    }

    public function onChooseSuggestion() {
        $product = Product::with(['categories:id', 'country:id'])->find(Input::get('product_id'));

        $product_en = DB::table('rainlab_translate_attributes')
            ->where('model_id',$product->id)
            ->where('locale','en')->first();
            
        $product_ru = DB::table('rainlab_translate_attributes')
            ->where('model_id',$product->id)
            ->where('locale','ru')->first();


        return [
            'product' => $product,
            'product_en_name' => json_decode($product_en->attribute_data)->name,
            'product_ru_name' => json_decode($product_ru->attribute_data)->name
        ];
    }

    public function onSave() {
        $product = null;
        $flag_existed_product = null;
        if(!Input::get('product_id')) {
            $flag_existed_product = false;
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

            $validator = Validator::make($data, $rules);

            if($validator->fails()) {
                throw new ValidationException($validator);
            }
            
            $category = Category::find(Input::get('category_id'));
                
            $product = new Product;
            $product->name = Input::get('name_tm');
            // Sets a single translated attribute for a language
            $product->setAttributeTranslated('name', Input::get('name_ru'), 'ru');
            $product->setAttributeTranslated('name', Input::get('name_en'), 'en');
            // Sets a single translated attribute for a language
            $product->setAttributeTranslated('slug', Str::slug(Input::get('name_ru'),'-'), 'ru');
            $product->setAttributeTranslated('slug', Str::slug(Input::get('name_en'),'-'), 'en');

            $product->slug = Str::slug(Input::get('name_tm'),'-');
            $product->status = 'new';
            $product->mark = Input::get('mark');
            $product->manufacturer = Input::get('manufacturer');
            $product->country_id = Input::get('country_id');

            $category->products()->save($product);
        } else {
            $product = Product::find(Input::get('product_id'));
            
            $flag_existed_product = true;
        }

        // go to next step - next form
        $this->page['measures'] = Measure::all();
        $this->page['paymentTerms'] = Term::where('type','payment')->get();
        $this->page['deliveryTerms'] = Term::where('type','delivery')->get();
        $this->page['currencies'] = Currency::all();
        $this->page['product'] = $product;
        $this->page['product_exists'] = $flag_existed_product;

        return [
            '#form-steps' => $this->renderPartial('@second_step_form')
        ];

    }

    public function onOfferFill() {
        $data = input();

        $existedProductButNewPhotosAdded = $data['product_exists'] && isset($data['new_img']);
        $existedProductButNoPhotosAdded = $data['product_exists'] && !isset($data['new_img']);

        $rules = [
            'quantity' => 'required|numeric',
            'price' => 'required|numeric',
            'place' => 'required',
            'description_tm' => 'required',
            'description_en' => 'required',
            'description_ru' => 'required',
            'ends_at' => 'required|date'
        ];

        $this->validateForm($data, $rules);

        // seaparate validation for file type
        $rules = [
            'new_img.*' => 'mimes:jpg,png'
        ];
        $this->validateFileType($data, $rules);
        
        // seaparate validation for image size
        $rules = [
            'new_img.*' => 'max:1024'
        ];
        $this->validateImageSize($data, $rules);

        $attachedProduct = Product::find($data['product_id']);

        $newOffer = $this->createNewOffer($data,$attachedProduct);

        if($existedProductButNoPhotosAdded){
            // bind product's images to new offer
            DB::table('system_files')->insert($this->makeInsertionArray($attachedProduct,$newOffer));
        } 
        elseif($existedProductButNewPhotosAdded) {
            $replacedImagesIds = array();

            // images: add new images to new offer
            foreach($data['new_img'] as $key => $img) {
                $replacedImagesIds[] = $key;

                $newOffer->images = $img;

                $newOffer->save();
            }
            
            $insertionArray = array();
            foreach($attachedProduct->images as $img) {
                
                if(!in_array($img->id, $replacedImagesIds)) {
                    // make insertion array
                    $insertionArray[] = [
                        'disk_name' => $img->disk_name,
                        'file_name' => $img->file_name,
                        'file_size' => $img->file_size,
                        'content_type' => $img->content_type,
                        'field' => $img->field,
                        'attachment_id' => $newOffer->id,
                        'attachment_type' => 'TPS\Birzha\Models\Offer',
                        'is_public' => $img->is_public
                    ];           
                }
            }

            // db table add not replaced img refs as above
            DB::table('system_files')->insert($insertionArray);
        } 
        else { //completely new product 
            // seaprate validation for image requirement
            $rules = [
                'new_img' => 'required'
            ];
            $this->validateForm($data, $rules);

            foreach($data['new_img'] as $key => $img) {
                $newOffer->images = $img;

                $newOffer->save();
            }
        }

        return [
            '#form-steps' => $this->renderPartial('@basket')
        ];
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

    protected function makeInsertionArray($attachedProduct,$newOffer) { //for binding images
        $insertionArray = array();

        $i = 0;

        foreach($attachedProduct->images as $img) {
            $insertionArray[] = [
                'disk_name' => $img->disk_name,
                'file_name' => $img->file_name,
                'file_size' => $img->file_size,
                'content_type' => $img->content_type,
                'field' => $img->field,
                'attachment_id' => $newOffer->id,
                'attachment_type' => 'TPS\Birzha\Models\Offer',
                'is_public' => $img->is_public
            ];
            
            $i++;
            
            if($i == 3) break; //only three first photos
        }

        return $insertionArray;
    }

    protected function createNewOffer($data,$attachedProduct) {
        $newOffer = new Offer;
        $newOffer->product_id = $attachedProduct->id;
        $newOffer->vendor_id = \Auth::user()->id;
        $newOffer->description = $data['description_tm'];
        // Sets a single translated attribute for a language
        $newOffer->setAttributeTranslated('description', $data['description_ru'], 'ru');
        $newOffer->setAttributeTranslated('description', $data['description_en'], 'en');
        $newOffer->quantity = $data['quantity'];
        $newOffer->price = $data['price'];
        $newOffer->measure_id = $data['measure_id'];
        $newOffer->payment_term_id = $data['payment_term_id'];
        $newOffer->delivery_term_id = $data['delivery_term_id'];
        $newOffer->packaging = $data['packaging'];
        $newOffer->place = $data['place'];
        $newOffer->name = $attachedProduct->name;
        $newOffer->currency_id = $data['currency_id'];
        $newOffer->ends_at = $data['ends_at'];
        $newOffer->save();

        return $newOffer;
    }

    public function onRun() {
        $this->countries = Country::all();
        $this->categories = Category::select('id','name')->get();
    }
} 