<?php namespace AhmadFatoni\ApiGenerator\Controllers\API;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Cms\Classes\Controller;
use Illuminate\Support\Facades\Validator;


class NotificationsApiController extends Controller
{
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

    /**
     * Read the notification
     */
    public function markAsRead($id)
    {
        if (!$user = \JWTAuth::parseToken()->authenticate()) {
            return response()->json('Unauthorized', 401);
        }

        $notification = $user->notifications()
            ->applyUnread()
            ->find($id);
        if(!$notification) {
            return response()->json("Not found resource with id=$id", 404);
        }

        try {
            $notification->update(['read_at' => Carbon::now()]);
        } catch (\Throwable $th) {
            return response()->json('Something went wrong', 500);
        }

        return response()->json([
            'message' => 'Notification is successfully read',
            'notification' => [
                'id' => $notification->id,
                'read_at' => $notification->read_at,
                'redirect_to_screen' => $notification->redirect_to_screen_for_api
            ],
        ], 300);
    }
}