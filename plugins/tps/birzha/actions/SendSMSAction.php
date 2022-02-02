<?php

namespace TPS\Birzha\Actions;

use Backend\Models\UserGroup as AdminGroupModel;
use Illuminate\Support\Facades\Log;
use RainLab\Notify\Classes\ActionBase;
use TPS\Birzha\Classes\SMS;

class SendSMSAction extends ActionBase
{
    public $recipientModes = [
        'user'    => 'User phone number (if applicable)',
//        'admin'   => 'Back-end administrators phones',
        'custom'  => 'Specific phone number',
    ];

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
     * Defines validation rules for the custom fields.
     * @return array
     */
    public function defineValidationRules()
    {
        return [
            'send_to_mode' => 'required',
            'send_to_custom' => 'required_if:send_to_mode,custom',
//            'send_to_admin' => 'required_if:send_to_mode,admin',
            'message' => 'required|string|max:160'
        ];
    }
    /**
     * Field configuration for the action.
     */
    public function defineFormFields()
    {
        return 'fields.yaml';
    }

    public function getSendToModeOptions()
    {
        $modes = $this->recipientModes;

        return $modes;
    }

//    public function getSendToAdminOptions()
//    {
//        $options = ['' => '- All administrators -'];
//
//        $groups = AdminGroupModel::lists('name', 'id');
//
//        return $options + $groups;
//    }

    public function getTitle()
    {
//        if ($this->isAdminMode()) {
//            return 'Send sms to administrators';
//        }
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

        if($this->host->send_to_mode == 'user' && $params['user']){
            if(!$params['user']['phone_verified'])
                return;
            $to = $params['user']['username'];

        }else{
            $to = $this->host->send_to_custom;
        }

        SMS::send($to,$this->host->message);

//        Log::info(json_encode($params));
    }
//    protected function isAdminMode()
//    {
//        return $this->host->send_to_mode == 'admin';
//    }
}
