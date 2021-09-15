<?php namespace AhmadFatoni\ApiGenerator\Controllers\API;

use Cms\Classes\Controller;
use BackendMenu;

use Illuminate\Http\Request;
use AhmadFatoni\ApiGenerator\Helpers\Helpers;
use Illuminate\Support\Facades\Validator;
use RainLab\User\Models\User;
use TPS\Birzha\Models\Message;
class MessagesapiController extends Controller
{
	protected $Message;

    protected $helpers;

    public function __construct(Message $Message, Helpers $helpers)
    {
        parent::__construct();
        $this->Message    = $Message;
        $this->helpers          = $helpers;
    }

    public function index(){

        // $data = $this->Message->all()->toArray();

        $currentUser = \JWTAuth::parseToken()->authenticate();
        
        // \DB::enableQueryLog();

        $chatrooms = $currentUser->chatrooms()
            ->with([
                'users' => function ($query) use ($currentUser) {
                    $query->select(['users.id', 'name', 'email'])
                    ->where('users.id', '!=', $currentUser->id);
                },
                'messages' => function ($query) {
                    $query->latest('send_at');
                }
            ])
            ->get()
            ->map(function($chatroom) use ($currentUser) {
                
                $chatroom->last_message = $chatroom->messages->first();
                $chatroom->count_unread_messages = $chatroom->messages->where('read_at',null)->where('reciver_id', $currentUser->id)->count();
                
                return $chatroom;
            });

            // dump(\DB::getQueryLog());
            // dd($this->helpers->apiArrayResponseBuilder(200, 'success', ['chatrooms' => $chatrooms]));

        return $this->helpers->apiArrayResponseBuilder(200, 'success', ['chatrooms' => $chatrooms]);
    }

    public function show($id){

        $data = $this->Message::find($id);

        if ($data){
            return $this->helpers->apiArrayResponseBuilder(200, 'success', [$data]);
        } else {
            $this->helpers->apiArrayResponseBuilder(404, 'not found', ['error' => 'Resource id=' . $id . ' could not be found']);
        }

    }

    public function store(Request $request){

    	$arr = $request->all();

        while ( $data = current($arr)) {
            $this->Message->{key($arr)} = $data;
            next($arr);
        }

        $validation = Validator::make($request->all(), $this->Message->rules);
        
        if( $validation->passes() ){
            $this->Message->save();
            return $this->helpers->apiArrayResponseBuilder(201, 'created', ['id' => $this->Message->id]);
        }else{
            return $this->helpers->apiArrayResponseBuilder(400, 'fail', $validation->errors() );
        }

    }

    public function update($id, Request $request){

        $status = $this->Message->where('id',$id)->update($data);
    
        if( $status ){
            
            return $this->helpers->apiArrayResponseBuilder(200, 'success', 'Data has been updated successfully.');

        }else{

            return $this->helpers->apiArrayResponseBuilder(400, 'bad request', 'Error, data failed to update.');

        }
    }

    public function delete($id){

        $this->Message->where('id',$id)->delete();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', 'Data has been deleted successfully.');
    }

    public function destroy($id){

        $this->Message->where('id',$id)->delete();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', 'Data has been deleted successfully.');
    }


    public static function getAfterFilters() {return [];}
    public static function getBeforeFilters() {return [];}
    public static function getMiddleware() {return [];}
    public function callAction($method, $parameters=false) {
        return call_user_func_array(array($this, $method), $parameters);
    }
    
}