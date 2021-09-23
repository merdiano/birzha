<?php namespace AhmadFatoni\ApiGenerator\Controllers\API;

use Cms\Classes\Controller;
use BackendMenu;

use Illuminate\Http\Request;
use AhmadFatoni\ApiGenerator\Helpers\Helpers;
use Illuminate\Support\Facades\Validator;
use RainLab\User\Models\User;
use TPS\Birzha\Models\Message;
use Tps\Birzha\Models\Chatroom;
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
                    /**
                     * this is the message_partner with whom
                     * $currentUser is chatting ('users.id', '!=', $currentUser->id)
                     */
                    $query->select(['users.id', 'username', 'name', 'surname', 'email'])
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
                $chatroom->messages = 'see the [last_message] field'; // we don't need all the messages, just the latest
                
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

    public function enterChatroom($id)
    {
        $currentUser = \JWTAuth::parseToken()->authenticate();
        
        $chatroom = $currentUser->chatrooms()
            ->find($id);

        if(!$chatroom) {
            return $this->helpers->apiArrayResponseBuilder(404, 'not found', ['error' => 'Resource id=' . $id . ' could not be found']);
        }

        $messages = $this->getMessagesFromChatroom($chatroom, $currentUser);

        return $this->helpers->apiArrayResponseBuilder(200, 'success', ['messages' => $messages]);
    }

    public function loadMore($id, Request $request)
    {
        $inputData = $request->all();

        $rules = [
            'skip' => 'required|integer'
        ];

        $validator = Validator::make($inputData, $rules);
        if($validator->fails()) {
            return $this->helpers->apiArrayResponseBuilder(400, 'fail', $validator->errors() );
        }

        $skipParam = $inputData['skip'];

        $currentUser = \JWTAuth::parseToken()->authenticate();
        
        $chatroom = $currentUser->chatrooms()
            ->find($id);

        if(!$chatroom) {
            return $this->helpers->apiArrayResponseBuilder(404, 'not found', ['error' => 'Resource id=' . $id . ' could not be found']);
        }

        $messages = $chatroom::find($id)->messages()->latest('send_at')->skip($skipParam)->limit(5)->get()->reverse();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', ['messages' => $messages]);
    }

    public function sendMessage($chatroom_id, Request $request)
    {
        $currentUser = \JWTAuth::parseToken()->authenticate();

        $chatroom = $currentUser->chatrooms()
            ->with([

                // this is a message reciever
                'users' => function ($query) use ($currentUser) {
                    $query->select(['users.id', 'name', 'email'])
                    ->where('users.id', '!=', $currentUser->id);
                }
            ])
            ->find($chatroom_id);

        if(!$chatroom) {
            return $this->helpers->apiArrayResponseBuilder(404, 'not found', ['error' => 'Resource id=' . $chatroom_id . ' could not be found']);
        }

        $inputData = $request->all();

        $rules = [
            'msg' => 'required'
        ];

        $validator = Validator::make($inputData, $rules);
        if($validator->fails()) {
            return $this->helpers->apiArrayResponseBuilder(400, 'fail', $validator->errors() );
        }

        $newMsg = new $this->Message;
        $newMsg->sender_id = $currentUser->id;
        $newMsg->reciver_id = $chatroom->users->first()->id;
        $newMsg->send_at = \Carbon\Carbon::now();
        $newMsg->message = $inputData['msg'];
        $newMsg->chatroom_id = $chatroom_id;
        $newMsg->save();

        return $this->helpers->apiArrayResponseBuilder(200, 'ok', ['message' => 'message sent successfully']);
    }

    /**
     * this api works when on a post page authenticated user wants
     * to write a message to a seller
     */
    public function initializeChatting($seller_id)
    {
        $currentUser = \JWTAuth::parseToken()->authenticate();

        // \DB::enableQueryLog();

        $seller = User::with(['chatrooms' => function($query) { // seller has chatrooms
                $query->with(['users' => function($queryUsers) { // every chatroom has users (2)
                    $queryUsers->select(['users.id', 'name', 'email']);
                }]);
            }])
            ->find($seller_id);

        if (!$seller){
            return $this->helpers->apiArrayResponseBuilder(404, 'not found', ['error' => 'Resource id=' . $seller_id . ' could not be found']);
        }
        
        /** 
         * this if statement means that
         * authenticated user & seller must not be the same person
         */
        if($seller->id == $currentUser->id) {
            return $this->helpers->apiArrayResponseBuilder(400, 'bad request', ['error' => 'Id is invalid']);
        }

        /**
         * from all seller's chatrooms choose only one with $currentUser
         * if such chatroom exists
         * else create new chatroom with $seller & $currentUser
         */
        $chatroomNeeded = null;
        foreach ($seller->chatrooms as $chatroom) {
            
            foreach ($chatroom->users as $user) {
                if($user->id == $currentUser->id) {
                    $chatroomNeeded = $chatroom;
                    break;
                }
            }

            // after chatroom iteration, check if we found $chatroomNeeded
            if($chatroomNeeded) break;
        }

        // dd(\DB::getQueryLog());

        /**
         * chatroom with given seller not found => $chatroomNeeded is null
         * in this case create new chatroom
         */
        if (!$chatroomNeeded) {
            $chatroomNeeded = new Chatroom;
            $chatroomNeeded->save();

            $chatroomNeeded->users()->attach($seller->id);
            $chatroomNeeded->users()->attach($currentUser->id);
        }

        // return response()->json(['chatrooms' => $seller->chatrooms]);
        // return response()->json(['users' => $seller->chatrooms->users]);
        // return response()->json(['chatroomNeeded' => $chatroomNeeded]);
        return $this->helpers->apiArrayResponseBuilder(200, 'success', ['messages' => $this->getMessagesFromChatroom($chatroomNeeded, $currentUser)]);

    }

    protected function getMessagesFromChatroom($chatroom, $currentUser)
    {
        $chatroom->messages()
            ->where('reciver_id', $currentUser->id)
            ->readAtNull()
            ->update(['read_at'=>\Carbon\Carbon::now()]);

        $messages = $chatroom->messages()->latest('send_at')->limit(5)->get()->reverse();

        return $messages;
    }

    public static function getAfterFilters() {return [];}
    public static function getBeforeFilters() {return [];}
    public static function getMiddleware() {return [];}
    public function callAction($method, $parameters=false) {
        return call_user_func_array(array($this, $method), $parameters);
    }
    
}