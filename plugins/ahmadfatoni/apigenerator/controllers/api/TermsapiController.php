<?php namespace AhmadFatoni\ApiGenerator\Controllers\API;

use Cms\Classes\Controller;
use BackendMenu;

use Illuminate\Http\Request;
use AhmadFatoni\ApiGenerator\Helpers\Helpers;
use Illuminate\Support\Facades\Validator;
use TPS\Birzha\Models\Term;
class TermsapiController extends Controller
{
	protected $Term;

    protected $helpers;

    public function __construct(Term $Term, Helpers $helpers)
    {
        parent::__construct();
        $this->Term    = $Term;
        $this->helpers          = $helpers;
    }

    public function index(Request $request){

        $inputData = $request->all();

        $rules = [
            'type' => 'required|in:payment,delivery'
        ];

        $validator = Validator::make($inputData, $rules);
        if($validator->fails()) {
            return $this->helpers->apiArrayResponseBuilder(400, 'fail', $validator->errors() );
        }

        switch ($inputData['type']) {
            case 'payment':
                $data = $this->Term::payment()->with('translations:locale,model_id,attribute_data')->get()->toArray();
                break;
            
            case 'delivery':
                $data = $this->Term::delivery()->with('translations:locale,model_id,attribute_data')->get()->toArray();
                break;
            
            default:
                # code...
                break;
        }

        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
    }

    public function show($id){

        $data = $this->Term::find($id);

        if ($data){
            return $this->helpers->apiArrayResponseBuilder(200, 'success', [$data]);
        } else {
            $this->helpers->apiArrayResponseBuilder(404, 'not found', ['error' => 'Resource id=' . $id . ' could not be found']);
        }

    }

    public function store(Request $request){

    	$arr = $request->all();

        while ( $data = current($arr)) {
            $this->Term->{key($arr)} = $data;
            next($arr);
        }

        $validation = Validator::make($request->all(), $this->Term->rules);
        
        if( $validation->passes() ){
            $this->Term->save();
            return $this->helpers->apiArrayResponseBuilder(201, 'created', ['id' => $this->Term->id]);
        }else{
            return $this->helpers->apiArrayResponseBuilder(400, 'fail', $validation->errors() );
        }

    }

    public function update($id, Request $request){

        $status = $this->Term->where('id',$id)->update($data);
    
        if( $status ){
            
            return $this->helpers->apiArrayResponseBuilder(200, 'success', 'Data has been updated successfully.');

        }else{

            return $this->helpers->apiArrayResponseBuilder(400, 'bad request', 'Error, data failed to update.');

        }
    }

    public function delete($id){

        $this->Term->where('id',$id)->delete();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', 'Data has been deleted successfully.');
    }

    public function destroy($id){

        $this->Term->where('id',$id)->delete();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', 'Data has been deleted successfully.');
    }


    public static function getAfterFilters() {return [];}
    public static function getBeforeFilters() {return [];}
    public static function getMiddleware() {return [];}
    public function callAction($method, $parameters=false) {
        return call_user_func_array(array($this, $method), $parameters);
    }
    
}