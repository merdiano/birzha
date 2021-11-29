<?php
namespace TPS\Birzha\Events;

class PaymentRecievedEvent extends \RainLab\Notify\Classes\EventBase
{
    /**
     * Returns information about this event, including name and description.
     */
    public function eventDetails()
    {
        return [
            'name'        => 'Payment received',
            'description' => 'New payment received',
            'group'       => 'payment'
        ];
    }

    public static function makeParamsFromEvent(array $args, $eventName = null)
    {
        return [
            'payment' => array_get($args, 0)
        ];
    }
}
