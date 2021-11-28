<?php

namespace TPS\Birzha\Events;

class ProductReviewedEvent extends \RainLab\Notify\Classes\EventBase
{
    /**
     * Returns information about this event, including name and description.
     */
    public function eventDetails()
    {
        return [
            'name'        => 'Product reviewed',
            'description' => 'Product reviewed by admin',
            'group'       => 'product'
        ];
    }

    public static function makeParamsFromEvent(array $args, $eventName = null)
    {
        return [
            'product' => array_get($args, 0),
            'user' => array_get($args, 1),
        ];
    }
}
