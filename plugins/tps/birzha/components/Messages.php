<?php namespace Tps\Birzha\Components;

use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Log;
use October\Rain\Exception\AjaxException;
use RainLab\User\Models\User;
use Tps\Birzha\Models\Chatroom;
use Tps\Birzha\Models\Message;
use Auth;
use Input;
use Carbon\Carbon;
use Flash;
use Event;

class Messages extends ComponentBase
{
    public function componentDetails() {
        return [
            'name' => 'Messages',
            'description' => 'Messages'
        ];
    }

    protected function loadMessages($sellerId) {
        $newChatRoomNeeded = true;

        $this->chatrooms = Auth::user()->chatrooms;


        foreach($this->chatrooms as $room) {
            $room->last_message = $room->messages()->latest('send_at')->first();
            $room->message_partner = $room->users()->where('users.id','!=',Auth::user()->id)->first();
            $room->count_unread_messages = $room->messages()->where('read_at',null)->where('reciver_id',Auth::user()->id)->count();

            if($room->message_partner->id == $sellerId) {
                $newChatRoomNeeded = false;
            }
        }
        // dump($this->chatrooms);

        if($sellerId) {
            if(!$newChatRoomNeeded) {
                // dump('open an existing chat');

            } else {
                // dump('create new chat');
                $seller = User::findOrFail($sellerId);

                $chatroom = new Chatroom;
                $chatroom->save();

                $chatroom->users()->attach($seller->id);
                $chatroom->users()->attach(Auth::user()->id);

                // add this newly added chatroom to the collection "chatrooms"
                $chatroom->message_partner = $seller;
                $this->chatrooms->push($chatroom);
                // dd($this->chatrooms);
            }
        } else {
            // dump("don't do anything");
        }
    }

    public $chatrooms;

    public function onRun() {
        $sellerId = null;

        if(Input::get('seller_id') && is_numeric(Input::get('seller_id')) && Input::get('seller_id') != Auth::user()->id) {
            $sellerId = Input::get('seller_id');
        }

        $this->loadMessages($sellerId);
    }

    public function onChatroom() {
        $chatRoomId = Input::get('chatroom_id');

        // Read unread messages
        Chatroom::find($chatRoomId)->messages()->where('reciver_id',Auth::user()->id)->where('read_at',null)->update(['read_at'=>Carbon::now()]);

        $this->page['result'] = Chatroom::find($chatRoomId)->messages()->latest('send_at')->limit(5)->get()->reverse();
        $this->page['currentUserId'] = Auth::user()->id;
        $this->page['chat_room_id'] = $chatRoomId;
        $this->page['chatRoomPartnerId'] = Chatroom::find($chatRoomId)->users()->where('users.id','!=',Auth::user()->id)->first()->id;

        return [
            'chat_area' => $this->renderPartial('@chatroom')
        ];
    }

    public function onLoadMore() {
        $skipParam = Input::get('skip');
        $chatRoomId = Input::get('chatroom_id');

        return [
            'more_messages' => Chatroom::find($chatRoomId)->messages()->latest('send_at')->skip($skipParam)->limit(5)->get()->reverse(),
            'currentUserId' => Auth::user()->id,
            'skipparam' => $skipParam
        ];
    }

    public function onMessageSend() {
        $newMsg = new Message;
        $newMsg->sender_id = Auth::user()->id;
        $newMsg->reciver_id = Input::get('reciver_id');
        $newMsg->send_at = Carbon::now();
        $newMsg->message = Input::get('msg');
        $newMsg->chatroom_id = Input::get('chatroom_id');
        if($newMsg->save()){

            Event::fire('tps.message.received', [$newMsg->reciver_id, $newMsg]);

            $this->page['latestMessage'] = $newMsg->message;
            $this->page['latestMessageTime'] = $newMsg->send_at;

        }else
            throw new AjaxException('Hat gitmedi. Nasazlyk yuze chykdy');

        return [
            'latest_message_area' => $this->renderPartial('@latest_message')
        ];
    }

    public function onDeleteChat()
    {
        $chatRoomId = Input::get('chatroom_id');

        $chatroom = Chatroom::with(['messages', 'users'])->find($chatRoomId);
        if($chatroom) {
            \DB::beginTransaction();

            try {
                $chatroom->messages()->delete();
                $chatroom->users()->detach();
                $chatroom->delete();
            } catch (\Throwable $th) {
                return \Redirect::to('/error');
            }

            \DB::commit();
        }

        return \Redirect::back();
    }
}
