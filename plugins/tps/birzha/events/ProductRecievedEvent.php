<?php

namespace TPS\Birzha\Events;

use RainLab\Notify\Classes\EventBase;

class ProductRecievedEvent extends EventBase
{
    /**
     * Returns information about this event, including name and description.
     */
    public function eventDetails()
    {
        return [
            'name'        => 'New product',
            'description' => 'New product submitted',
            'group'       => 'product'
        ];
    }

    public static function makeParamsFromEvent(array $args, $eventName = null)
    {
        return [
            'product' => array_get($args, 0)
        ];
    }
}
