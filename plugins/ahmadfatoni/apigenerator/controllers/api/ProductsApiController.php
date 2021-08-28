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

        // $query = $this->Product::with('categories:id,name')
        $query = $this->Product::with([
                'translations:locale,model_id,attribute_data',
                'images:attachment_id,attachment_type,disk_name,file_name'
            ])
            ->approvedAndFreshEndDate()
            ->orderBy('created_at', $sortOrder);

        if($categoryId) { // fetch offers by the category of the product
            $category = Category::find($categoryId);
            if($category) {
                $query = $category->products()
                // ->with('categories:id,name')
                ->with('translations:locale,model_id,attribute_data')
                ->approvedAndFreshEndDate()
                ->orderBy('created_at', $sortOrder);
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
                    ->with('translations:locale,model_id,attribute_data')
                    ->approvedAndFreshEndDate()
                    ->orderBy('created_at', $sortOrder);
            } else {
                $query = null;
            }
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

        $data = $this->Product::find($id);

        if ($data){
            return $this->helpers->apiArrayResponseBuilder(200, 'success', [$data]);
        } else {
            $this->helpers->apiArrayResponseBuilder(404, 'not found', ['error' => 'Resource id=' . $id . ' could not be found']);
        }

    }

    public function store(Request $request){

    	$arr = $request->all();

        while ( $data = current($arr)) {
            $this->Product->{key($arr)} = $data;
            next($arr);
        }

        $validation = Validator::make($request->all(), $this->Product->rules);
        
        if( $validation->passes() ){
            $this->Product->save();
            return $this->helpers->apiArrayResponseBuilder(201, 'created', ['id' => $this->Product->id]);
        }else{
            return $this->helpers->apiArrayResponseBuilder(400, 'fail', $validation->errors() );
        }

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
    
}