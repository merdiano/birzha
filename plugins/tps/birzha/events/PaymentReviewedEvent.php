<?php

namespace TPS\Birzha\Events;

use RainLab\Notify\Classes\EventBase;

class PaymentReviewedEvent extends EventBase
{
    /**
     * Returns information about this event, including name and description.
     */
    public function eventDetails()
    {
        return [
            'name'        => 'Payment reviewed',
            'description' => 'Payment reviewed by admin',
            'group'       => 'payment'
        ];
    }

    public static function makeParamsFromEvent(array $args, $eventName = null)
    {
        return [
            'payment' => array_get($args, 0),
            'user' => array_get($args, 1),
            'message' => 'sizin balansynyz doldurldy'
        ];
    }

}
