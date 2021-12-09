<?php namespace AhmadFatoni\ApiGenerator\Controllers\API;

use Illuminate\Http\Request;
use Cms\Classes\Controller;
use Illuminate\Support\Facades\Validator;

// use RainLab\Notify\Models\Notification;

class NotificationsApiController extends Controller
{
    // protected $Notification;

    // public function __construct(Notification $Notification)
    // {
    //     parent::__construct();
    //     // $this->Notification    = $Notification;
    // }

    public function index(Request $request) {
        if (!$user = \JWTAuth::parseToken()->authenticate()) {
            return response()->json('Unauthorized', 401);
        }

        $validator = Validator::make($request->all(), [
            'records_per_page' => 'numeric',
        ]);
        if($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $notifications = $user->notifications()
            ->applyUnread()
            ->paginate($request->records_per_page ? $request->records_per_page : 5)
            ->makeHidden(['event_type', 'notifiable_type', 'notifiable_id', 'icon', 'type', 'body', 'data', 'parsed_body'])
            ->transform(function ($notification) {
                $notification->description_for_api = "";
                $notification->redirect_to_screen_for_api = "";
                return $notification;
            });


        return response()->json(['data' => $notifications], 200);
    }
}