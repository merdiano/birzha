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

    const REGISTRATION_URI = 'register.do';

    const STATUS_URI = 'getOrderStatus.do';

    const API_URL = 'https://mpi.gov.tm/payment/rest/';

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
        $product->status = 'draft';
        $product->mark = Input::get('mark');
        $product->manufacturer = Input::get('manufacturer');
        $product->country_id = Input::get('country_id');

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

    public function onOfferFill() {
        $data = input();

        $rules = [
            'quantity' => 'required|numeric',
            'price' => 'required|numeric',
            'place' => 'required',
            'description_tm' => 'required',
            'description_en' => 'required',
            'description_ru' => 'required',
            'ends_at' => 'required|date',
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

        foreach($data['new_img'] as $key => $img) {
            // add images to completely new product
            $attachedProduct->images = $img;
            $attachedProduct->save();
        }

        $draft_offers = \Auth::user()->products()
            ->where('status','draft')
            ->orderBy('created_at', 'desc')->get();

        $this->page['draft_offers'] = $draft_offers;
        $this->page['draft_offers_count'] = count($draft_offers);
        $this->page['fee'] = Settings::getValue('fee');


        return [
            '#form-steps' => $this->renderPartial('@basket')
        ];
    }

    public function onDeleteOfferFromBasket() {
        // delete offer from basket, from db
        $offer = Product::find(Input::get('offer_id'));
        $offer->images()->delete();
        $offer->translations()->delete();
        $offer->delete();

        // then display the rest of offers
        $draft_offers = \Auth::user()->products()
            ->where('status','draft')
            ->orderBy('created_at', 'desc')->get();

        $this->page['draft_offers'] = $draft_offers;
        $this->page['draft_offers_count'] = count($draft_offers);
        $this->page['fee'] = Settings::getValue('fee');


        return [
            '#form-steps' => $this->renderPartial('@basket')
        ];
    }

    public function onStartToPay() {
        $data = input();

        $rules = [
            'pay_type' => 'required',
        ];

        $this->validateForm($data, $rules);

        $pay_type = $data['pay_type'];
        if($pay_type == "bank") {
            return [
                '#form-steps' => $this->renderPartial('@bank_transfer_pay')
            ];
        } else {
            $url = $this->payOnline();
            if(!$url) {
                $this->page['err_message'] = 'Не удается подключиться к сервисам банка. Попробуйте позже';
                return [
                    '#form-steps' => $this->renderPartial('@message')
                ];
            }

            $this->page['url'] = $url;
            return [
                '#form-steps' => $this->renderPartial('@redirect')
            ];
        }
    }

    protected function payOnline() {
        $payment = $this->createNewPayment(false, 'online');
        
        $response = $this->registerOrder($payment);

        $result = json_decode($response->body,true);

        if($result['errorCode'] == 0){
            $payment->order_id = $result['orderId'];

            $payment->save();

            return $result['formUrl'];
        }
        else{
            return false;
            // dd($result['formUrl']);
            // throw new AjaxException(
            //     $result
            // );
        }
    }

    public function onPayByBankTransfer() {
        $data = input();

        $rules = [
            'bank_file' => 'required|mimes:pdf,jpg,png',
        ];

        $this->validateForm($data, $rules);

        $this->createNewPayment(Input::file('bank_file'), 'bank');

        return [
            '#form-steps' => $this->renderPartial('@message')
        ];
    }

    protected function createNewPayment($bankFile, $payMethod) {
        $newPayment = new Payment;
        $newPayment->user_id = \Auth::user()->id;
        $newPayment->created_at = Carbon::now('Asia/Ashgabat');
        
        $draft_offers = \Auth::user()->products()
            ->where('status','draft')
            ->get();
        $newPayment->amount = count($draft_offers) * Settings::getValue('fee');
        
        $newPayment->payment_type = $payMethod;
        $newPayment->status = "new";
        $newPayment->save();
        $newPaymentId = $newPayment->id;

        DB::transaction(function() use ($draft_offers, $newPaymentId) {
            foreach($draft_offers as $df) {
                $df->payment_id = $newPaymentId;
                $df->status = 'new';
                $df->save();
            }
        });

        // attach file to payment
        if($bankFile) {
            $newPayment->bank_file = $bankFile;
            $newPayment->save();
        }

        return $newPayment;
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

    protected function fillProduct($data,$attachedProduct) {
        // $newOffer = new Offer;
        // $newOffer->product_id = $attachedProduct->id;
        
        $attachedProduct->vendor_id = \Auth::user()->id;
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
        // $attachedProduct->name = $attachedProduct->name;
        $attachedProduct->currency_id = $data['currency_id'];
        $attachedProduct->created_at = Carbon::now('Asia/Ashgabat');
        $attachedProduct->ends_at = $data['ends_at'];
        $attachedProduct->save();

        return $attachedProduct;
    }

    public function onRun() {
        $this->countries = Country::all();
        $this->categories = Category::select('id','name','status')->where('status',1)->get();
    }

    // payment
    protected function registerOrder($payment) {
        $client = self::getClient(self::REGISTRATION_URI);

        $url = $this->controller->pageUrl('bank_result.htm', ['payment_id' => $payment->id]);
        
        $client->data([
            'amount'      => $payment->amount * 100,//multiply by 100 to obtain tenge
            'currency' => 934,
            'description' => 'Kart üçin döwlet pajy.',
            'orderNumber'     => strtoupper(str_random(5)) . date('jn'),

            'failUrl'     => $url . '?status=success',
            'returnUrl' => $url . '?status=failed',

        ]);

        $client->setOption(CURLOPT_POSTFIELDS,$client->getRequestData());
    //    dd($client);
        return $client->send();
    }

    private static function getClient($url){
        return Http::make(self::API_URL.$url, Http::METHOD_POST)->data([
                'userName' => Settings::getValue('api_login'),
                'password' => Settings::getValue('api_password'),
        ])->timeout(120);
    }
} 