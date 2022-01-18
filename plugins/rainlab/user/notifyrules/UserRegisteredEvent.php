<?php namespace RainLab\User\NotifyRules;

use RainLab\User\Classes\UserEventBase;

class UserRegisteredEvent extends UserEventBase
{
    /**
     * Returns information about this event, including name and description.
     */
    public function eventDetails()
    {
        return [
            'name'        => 'Registered',
            'description' => 'A user has registered',
            'group'       => 'user'
        ];
    }
    public static function makeParamsFromEvent(array $args, $eventName = null)
    {
        $user = array_get($args, 0);

        $params = $user->getNotificationVars();
        $params['activation_code'] = $user->getActivationCode();
        $params['user'] = $user;

        return $params;
    }
}
