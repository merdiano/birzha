<?php

namespace TPS\Birzha\Actions;

use RainLab\Notify\Classes\ActionBase;

class SendSMSAction extends ActionBase
{
    /**
     * Returns information about this event, including name and description.
     */
    public function actionDetails()
    {
        return [
            'name'        => 'Send a sms message',
            'description' => 'Send a sms notification to a customers',
            'icon'        => 'icon-envelope'
        ];
    }

    /**
     * Field configuration for the action.
     */
//    public function defineFormFields()
//    {
//        return 'fields.yaml';
//    }

    public function getTitle()
    {
        return 'Send sms to customers';
    }

    public function getActionIcon()
    {
        return 'icon-envelope-square';
    }

    /**
     * Triggers this action.
     * @param array $params
     * @return void
     */
    public function triggerAction($params)
    {

    }
}
