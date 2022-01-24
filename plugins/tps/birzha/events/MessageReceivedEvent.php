<?php

namespace TPS\Birzha\Events;

use Illuminate\Support\Facades\Log;

class MessageReceivedEvent extends \RainLab\Notify\Classes\EventBase
{
    /**
     * Returns information about this event, including name and description.
     */
    public function eventDetails()
    {
        return [
            'name'        => 'New message',
            'description' => 'New message received',
            'group'       => 'message'
        ];
    }

    /**
     * Defines the usable parameters provided by this class.
     */
//    public function defineParams()
//    {
//        return [
//            'name' => [
//                'title' => 'Name',
//                'label' => 'Name of the user',
//            ],
//            // ...
//        ];
//    }

    public static function makeParamsFromEvent(array $args, $eventName = null)
    {

        return [
            'user' => array_get($args, 0),
            'message' => array_get($args, 1),
        ];
    }
}
