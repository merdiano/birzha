<?php namespace AhmadFatoni\ApiGenerator\Controllers\API;

use Cms\Classes\Controller;
use BackendMenu;

use Illuminate\Http\Request;
use AhmadFatoni\ApiGenerator\Helpers\Helpers;
use Illuminate\Support\Facades\Validator;
use TPS\Birzha\Models\Product;
use TPS\Birzha\Models\Category;
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
                'images:attachment_id,attachment_type,disk_name,file_name'
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
                    'images:attachment_id,attachment_type,disk_name,file_name'
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
                        'images:attachment_id,attachment_type,disk_name,file_name'
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
                'images:attachment_id,attachment_type,disk_name,file_name'
            ])
            ->orderBy('updated_at', $sortOrder);
        }

        $data = $query ? $query->paginate($perPage) : null;

        // if($data) {
        //     $data->each(function ($item, $key) {
        //         $item->img_url_start = $this->pageUrl('index') . \Config::get('cms.storage.media.path') . $item->icon;
        //     });
        // }

        return $this->helpers->apiArrayResponseBuilder(200, 'success', [$data]);
    }

    public function show($id){

        $data = $this->Product::with([
            'translations:locale,model_id,attribute_data',
            'images:attachment_id,attachment_type,disk_name,file_name'
        ])->find($id);

        if ($data && $data->status == 'approved' && $data->ends_at >= \Carbon\Carbon::now()){
            return $this->helpers->apiArrayResponseBuilder(200, 'success', [$data]);
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
            'country_id' => 'required|exists:tps_birzha_countries,id'
        ];

        $validator = $this->validateForm($data, $rules);
        if($validator->fails()) {
            return $this->helpers->apiArrayResponseBuilder(400, 'fail', $validator->errors() );
        }

        $category = Category::find($data['category_id']);

        if(isset($data['productForEditing'])) { // if product is being edited - not new
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
        $product->country_id = $data['country_id'];

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

        $status = $this->Product->where('id',$id)->update($data);
    
        if( $status ){
            
            return $this->helpers->apiArrayResponseBuilder(200, 'success', 'Data has been updated successfully.');

        }else{

            return $this->helpers->apiArrayResponseBuilder(400, 'bad request', 'Error, data failed to update.');

        }
    }

    public function delete($id){

        $this->Product->where('id',$id)->delete();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', 'Data has been deleted successfully.');
    }

    public function destroy($id){

        $this->Product->where('id',$id)->delete();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', 'Data has been deleted successfully.');
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
    
}