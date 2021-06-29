<?php namespace Tps\Birzha\Components;

use Cms\Classes\ComponentBase;
use RainLab\User\Models\User;
use Tps\Birzha\Models\Chatroom;
use Tps\Birzha\Models\Message;
use Auth;
use Input;
use Carbon\Carbon;

class Messages extends ComponentBase
{
    public function componentDetails() {
        return [
            'name' => 'Messages',
            'description' => 'Messages'
        ];
    }

    protected function loadMessages() {
        $this->chatrooms = Auth::user()->chatrooms;
        foreach($this->chatrooms as $room) {
            $room->last_message = $room->messages()->latest('send_at')->first();
            $room->message_partner = $room->users()->where('users.id','!=',Auth::user()->id)->first();
            $room->count_unread_messages = $room->messages()->where('read_at',null)->where('reciver_id',Auth::user()->id)->count();
        }
    }

    public $chatrooms;

    public function onRun() {
        $this->loadMessages();
    }

    public function onChatroom() {
        $chatRoomId = Input::get('chatroom_id');

        // Read unread messages
        Chatroom::find($chatRoomId)->messages()->where('reciver_id',Auth::user()->id)->where('read_at',null)->update(['read_at'=>Carbon::now()]);

        
        
        return [
            'messages' => Chatroom::find($chatRoomId)->messages,
            'currentUserId' => Auth::user()->id,
            'chatRoomId' => $chatRoomId,
            'chatRoomPartnerId' => Chatroom::find($chatRoomId)->users()->where('users.id','!=',Auth::user()->id)->first()->id
        ];
    }

    public function onMessageSend() {
        $newMsg = new Message;
        $newMsg->sender_id = Auth::user()->id;
        $newMsg->reciver_id = Input::get('reciver_id');
        $newMsg->send_at = Carbon::now();
        $newMsg->message = Input::get('msg');
        $newMsg->chatroom_id = Input::get('chatroom_id');
        $newMsg->save();

        return $this->onChatroom();
    }
}