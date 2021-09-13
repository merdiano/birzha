<?php namespace AhmadFatoni\ApiGenerator\Controllers\API;

use Cms\Classes\Controller;
use BackendMenu;

use Illuminate\Http\Request;
use AhmadFatoni\ApiGenerator\Helpers\Helpers;
use Illuminate\Support\Facades\Validator;
use TPS\Birzha\Models\Currency;
class CurrenciesapiController extends Controller
{
	protected $Currency;

    protected $helpers;

    public function __construct(Currency $Currency, Helpers $helpers)
    {
        parent::__construct();
        $this->Currency    = $Currency;
        $this->helpers          = $helpers;
    }

    public function index(){

        $data = $this->Currency::with('translations:locale,model_id,attribute_data')->get()->toArray();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
    }

    public function show($id){

        $data = $this->Currency::find($id);

        if ($data){
            return $this->helpers->apiArrayResponseBuilder(200, 'success', [$data]);
        } else {
            $this->helpers->apiArrayResponseBuilder(404, 'not found', ['error' => 'Resource id=' . $id . ' could not be found']);
        }

    }

    public function store(Request $request){

    	$arr = $request->all();

        while ( $data = current($arr)) {
            $this->Currency->{key($arr)} = $data;
            next($arr);
        }

        $validation = Validator::make($request->all(), $this->Currency->rules);
        
        if( $validation->passes() ){
            $this->Currency->save();
            return $this->helpers->apiArrayResponseBuilder(201, 'created', ['id' => $this->Currency->id]);
        }else{
            return $this->helpers->apiArrayResponseBuilder(400, 'fail', $validation->errors() );
        }

    }

    public function update($id, Request $request){

        $status = $this->Currency->where('id',$id)->update($data);
    
        if( $status ){
            
            return $this->helpers->apiArrayResponseBuilder(200, 'success', 'Data has been updated successfully.');

        }else{

            return $this->helpers->apiArrayResponseBuilder(400, 'bad request', 'Error, data failed to update.');

        }
    }

    public function delete($id){

        $this->Currency->where('id',$id)->delete();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', 'Data has been deleted successfully.');
    }

    public function destroy($id){

        $this->Currency->where('id',$id)->delete();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', 'Data has been deleted successfully.');
    }


    public static function getAfterFilters() {return [];}
    public static function getBeforeFilters() {return [];}
    public static function getMiddleware() {return [];}
    public function callAction($method, $parameters=false) {
        return call_user_func_array(array($this, $method), $parameters);
    }
    
}