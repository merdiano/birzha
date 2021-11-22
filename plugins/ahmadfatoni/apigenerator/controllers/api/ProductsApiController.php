<?php namespace AhmadFatoni\ApiGenerator\Controllers\API;

use Cms\Classes\Controller;
use BackendMenu;

use Illuminate\Http\Request;
use AhmadFatoni\ApiGenerator\Helpers\Helpers;
use Illuminate\Support\Facades\Validator;
use PHPUnit\TextUI\Help;
use TPS\Birzha\Models\Product;
use TPS\Birzha\Models\Category;
use TPS\Birzha\Models\Term;
use TPS\Birzha\Models\Measure;
use TPS\Birzha\Models\Settings;
class ProductsAPIController extends Controller
{
	protected $Product;

    protected $helpers;

    public function __construct(Product $Product, Helpers $helpers)
    {
        parent::__construct();
        $this->Product    = $Product;
        $this->helpers          = $helpers;
    }

    public function index(){
        $sortOrderParam = strtolower(input('sort_order'));

        // protect from sql injection
        if($sortOrderParam != 'asc' && $sortOrderParam != 'desc') {
            $sortOrder = 'desc'; // by default
        } else {
            $sortOrder = $sortOrderParam;
        }

        $categoryId = intval(input('category_id')); // intval protects from injection
        $perPage = intval(input('custom_per_page')); // intval protects from injection
        $productId = intval(input('product_id')); // intval protects from injection
        $queryString = input('q');
        $locale = input('locale');

        // $query = $this->Product::with('categories:id,name')
        $query = $this->Product::with([
                'translations:locale,model_id,attribute_data',
                'images:attachment_id,attachment_type,disk_name,file_name',
                'measure.translations:locale,model_id,attribute_data',
                'currency.translations:locale,model_id,attribute_data'
            ])
            ->approvedAndFreshEndDate()
            ->orderBy('updated_at', $sortOrder);

        if($categoryId) { // fetch offers by the category of the product
            $category = Category::find($categoryId);
            if($category) {
                $query = $category->products()
                // ->with('categories:id,name')
                ->with([
                    'translations:locale,model_id,attribute_data',
                    'images:attachment_id,attachment_type,disk_name,file_name',
                    'measure.translations:locale,model_id,attribute_data',
                    'currency.translations:locale,model_id,attribute_data'
                ])
                ->approvedAndFreshEndDate()
                ->orderBy('updated_at', $sortOrder);
            } else {
                $query = null;
            }
        } 

        if($productId) { // fetch offers with products in the same category
            
            $product = $this->Product::find($productId);
            
            if($product) {
                $category = $product->categories->first();

                $query = $category->products()
                    ->where('id','!=',$productId)
                    // ->with('categories:id,name')
                    ->with([
                        'translations:locale,model_id,attribute_data',
                        'images:attachment_id,attachment_type,disk_name,file_name',
                        'measure.translations:locale,model_id,attribute_data',
                        'currency.translations:locale,model_id,attribute_data'
                    ])
                    ->approvedAndFreshEndDate()
                    ->orderBy('updated_at', $sortOrder);
            } else {
                $query = null;
            }
        }

        if($queryString && $locale) {
            $query = $this->Product::searched($locale, $queryString)
            ->with([
                'translations:locale,model_id,attribute_data',
                'images:attachment_id,attachment_type,disk_name,file_name',
                'measure.translations:locale,model_id,attribute_data',
                'currency.translations:locale,model_id,attribute_data'
            ])
            ->orderBy('updated_at', $sortOrder);
        }

        $data = $query ? $query->paginate($perPage) : null;

        // if($data) {
        //     $data->each(function ($item, $key) {
        //         $item->img_url_start = $this->pageUrl('index') . \Config::get('cms.storage.media.path') . $item->icon;
        //     });
        // }

        return response()->json($data, 200);
    }

    public function show($id){

        $data = $this->Product::with([
            'translations:locale,model_id,attribute_data',
            'images:attachment_id,attachment_type,disk_name,file_name',
            'measure.translations:locale,model_id,attribute_data',
            'currency.translations:locale,model_id,attribute_data',
            'payment_term.translations:locale,model_id,attribute_data',
            'delivery_term.translations:locale,model_id,attribute_data',
            'vendor:id,name,surname,email,username'
        ])->find($id);

        if ($data && $data->status == 'approved' && $data->ends_at >= \Carbon\Carbon::now()){
            return response()->json($data, 200);
        } else {
            return $this->helpers->apiArrayResponseBuilder(404, 'not found', ['error' => 'Resource id=' . $id . ' could not be found']);
        }

    }

    // step1
    public function store(Request $request){
        $data = $request->all();

        $rules = [
            'name_ru' => 'required',
            'name_en' => 'required',
            'name_tm' => 'required',
            'category_id' => [
                'required',
                'exists:tps_birzha_categories,id',
                // validation for category status - it should be active
                function ($attribute, $value, $fail) {
                    $c = Category::find($value);
                    if($c) {
                        if($c->status != 1) $fail(":attribute is non-active!");
                    }
                }
            ],
            'mark' => 'required',
            'manufacturer' => 'required',
            'country' => 'required',
            
            // if product is being edited - not added 
            'productForEditing' => [
                // validation for product deleted_at field - it should
                // be null, means it should not be deleted (exists rule not working)
                function ($attribute, $value, $fail) {
                    $p = Product::find($value);
                    if(!$p) $fail(":attribute is invalid");
                }
            ]
        ];

        $validator = $this->validateForm($data, $rules);
        if($validator->fails()) {
            return $this->helpers->apiArrayResponseBuilder(400, 'fail', $validator->errors() );
        }

        $category = Category::find($data['category_id']);

        if(isset($data['productForEditing'])) { // if product is being edited - not added
            $product = $this->Product::find($data['productForEditing']);
        } else {
            $product = new $this->Product;
        }

        $product->translateContext('tm');

        $product->name = $data['name_tm'];
        // Sets a single translated attribute for a language
        $product->setAttributeTranslated('name', $data['name_ru'], 'ru');
        $product->setAttributeTranslated('name', $data['name_en'], 'en');
        // Sets a single translated attribute for a language
        $product->setAttributeTranslated('slug', \Str::slug($data['name_ru'],'-'), 'ru');
        $product->setAttributeTranslated('slug', \Str::slug($data['name_en'],'-'), 'en');

        $product->slug = \Str::slug($data['name_tm'],'-');
        $product->status = 'draft';
        $product->mark = $data['mark'];
        $product->manufacturer = $data['manufacturer'];
        $product->country = $data['country'];

        $product->vendor_id = \JWTAuth::parseToken()->authenticate()->id;

        $product->ends_at = null; // if approved but date was expired


        if(!isset($data['productForEditing'])) {
            // $product->created_at = Carbon::now('Asia/Ashgabat');
            $category->products()->save($product);
        } else {
            // detach from all other categories
            $product->categories()->detach();
            // attach to a new category
            $category->products()->save($product);
        }
        
        return $this->helpers->apiArrayResponseBuilder(200, 'ok', ['product' => $product]);
        
        //     $this->Product->save();
        //     return $this->helpers->apiArrayResponseBuilder(201, 'created', ['id' => $this->Product->id]);
    }

    public function update($id, Request $request){

        $attachedProduct = $this->Product::find($id);
        if(!$attachedProduct) {
            return $this->helpers->apiArrayResponseBuilder(404, 'not found', ['error' => 'Resource id=' . $id . ' could not be found']);
        }

        $data = $request->all();

        $rules = [
            'quantity' => 'required|numeric|integer',
            'price' => 'required|numeric',
            'place' => 'required',
            'description_tm' => 'required',
            'description_en' => 'required',
            'description_ru' => 'required',
            'payment_term_id' => [
                'required',
                'exists:tps_birzha_terms,id',
                // in terms table it must have type = payment
                function ($attribute, $value, $fail) {
                    $t = Term::find($value);
                    if($t) {
                        if($t->type != 'payment') $fail(":attribute is invalid");
                    }
                }
            ],
            'packaging' => 'required|in:yes,no',
            'delivery_term_id' => [
                'required',
                'exists:tps_birzha_terms,id',
                // in terms table it must have type = delivery
                function ($attribute, $value, $fail) {
                    $t = Term::find($value);
                    if($t) {
                        if($t->type != 'delivery') $fail(":attribute is invalid");
                    }
                }
            ],
            'currency_id' => 'required|exists:tps_birzha_currencies,id',
            'measure_id' => [
                'required',
                // validation for measure deleted_at field - it should
                // be null, means it should not be deleted (exists rule not working)
                function ($attribute, $value, $fail) {
                    $m = Measure::find($value);
                    if(!$m) $fail(":attribute is invalid");
                }
            ],
            'old_img' => 'array',
            'new_img' => 'array',
            // allowed only jpg & png files with size <= 1024
            'new_img.*' => 'mimes:jpg,png|max:1024',
        ];

        $validator = $this->validateForm($data, $rules);
        if($validator->fails()) {
            return $this->helpers->apiArrayResponseBuilder(400, 'fail', $validator->errors() );
        }

        // validate if no old images and no new images
        if((!$request->has('new_img') && !$request->has('old_img'))) {
            return $this->helpers->apiArrayResponseBuilder(400, 'fail', ['no_images' => trans('validation.atleast_1_image')]);
        }

        // if new_img field is passed but empty
        if($request->has('new_img')) {
            $rules = [
                'new_img' => 'required'
            ];
            $validator = $this->validateForm($data, $rules);
            if($validator->fails()) {
                return $this->helpers->apiArrayResponseBuilder(400, 'fail', $validator->errors() );
            }
        }
        
        // if old_img field is passed but empty
        if($request->has('old_img')) {
            $rules = [
                'old_img' => 'required'
            ];
            $validator = $this->validateForm($data, $rules);
            if($validator->fails()) {
                return $this->helpers->apiArrayResponseBuilder(400, 'fail', $validator->errors() );
            }
        }

        // product found, validation passed -> can fill product &save
        try {
            $attachedProduct = $this->fillProduct($data,$attachedProduct);

            foreach($data['new_img'] ?? [] as $key => $img) {
                $attachedProduct->images = $img;
                $attachedProduct->save();
            }
        } catch(\Throwable $e) {
            return $this->helpers->apiArrayResponseBuilder(500, 'server error', ['message' => 'something went wrong']);
        }

        return $this->helpers->apiArrayResponseBuilder(200, 'ok', ['message' => 'updated successfully']);
    }

    /**
     * Delete a my product
     */
    public function delete($id){

        $currentUser = \JWTAuth::parseToken()->authenticate();

        $product = $currentUser->products->find($id);

        if(!$product) {
            return $this->helpers->apiArrayResponseBuilder(404, 'not found', ['error' => 'Resource id=' . $id . ' could not be found']);
        }

        /**
         * user can not delete product with status 'new'
         */
        if($product->status == 'new') {
            return $this->helpers->apiArrayResponseBuilder(404, 'not found', ['error' => 'Resource id=' . $id . ' could not be found']);
        }

        $product->images()->delete();
        $product->translations()->delete();
        $product->delete();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', ['message' => 'Data has been deleted successfully']);
    }

    public function destroy($id){

        $this->Product->where('id',$id)->delete();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', 'Data has been deleted successfully.');
    }

    public function imageDelete($id, $image_id)
    {
        $product = $this->Product::find($id);

        if(!$product) {
            return $this->helpers->apiArrayResponseBuilder(404, 'not found', ['error' => 'Resource id=' . $id . ' could not be found']);
        }

        try {
            $image = $product->images()->find($image_id);

            if(!$image) {
                return $this->helpers->apiArrayResponseBuilder(404, 'not found', ['error' => 'Resource id=' . $image_id . ' could not be found']);
            }

            

            $image->delete();
            

        } catch(\Throwable $e) {
            return $this->helpers->apiArrayResponseBuilder(500, 'server error', ['message' => 'Something went wrong']);
        }

        return $this->helpers->apiArrayResponseBuilder(200, 'success', ['message' => 'Image deleted successfully']);
    }

    public function publish($id)
    {
        $product = $this->Product::find($id);

        if(!$product) {
            return $this->helpers->apiArrayResponseBuilder(404, 'not found', ['error' => 'Resource id=' . $id . ' could not be found']);
        }

        try {
            $user = \JWTAuth::parseToken()->authenticate();

            if($user->balance - Settings::getValue('fee') < 0) {
                // ... message about not enough money
                return $this->helpers->apiArrayResponseBuilder(300, 'redirect', ['message' => 'fill up your balance']);
            } else {
                $user->balance = $user->balance - Settings::getValue('fee');
                $user->save();

                //save how much user payed because fee can be changed by admin tomorrow
                // if post is denied we get back payed fee, not admin's set fee
                $product->payed_fee_for_publ = Settings::getValue('fee');
                $product->status = 'new';
                $product->save();
            }
        } catch (\Throwable $th) {
            return $this->helpers->apiArrayResponseBuilder(500, 'server error', ['message' => 'Something went wrong']);
        }

        return $this->helpers->apiArrayResponseBuilder(200, 'ok', ['message' => 'published successfully']);
    }

    public static function getAfterFilters() {return [];}
    public static function getBeforeFilters() {return [];}
    public static function getMiddleware() {return [];}
    public function callAction($method, $parameters=false) {
        return call_user_func_array(array($this, $method), $parameters);
    }

    protected function validateForm($data, $rules) {

        // dd($rules);
        return Validator::make($data, $rules);

        
        // dd('validator does not fail');
    }
    
    protected function fillProduct($data, $attachedProduct) {
        $attachedProduct->translateContext('tm');

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
        $attachedProduct->save();

        return $attachedProduct;
    }

    public function myProducts()
    {
        $perPage = intval(input('custom_per_page')); // intval protects from injection

        try {
            $products = \JWTAuth::parseToken()->authenticate()
                ->products()
                ->with('translations:locale,model_id,attribute_data',
                    'images:attachment_id,attachment_type,disk_name,file_name')
                ->orderBy('updated_at', 'desc')
                ->paginate($perPage);
        } catch (\Throwable $th) {
            return $this->helpers->apiArrayResponseBuilder(500, 'server error', ['message' => 'Something went wrong']);
        }
        
        
        return response()->json($products, 200);
    }
}