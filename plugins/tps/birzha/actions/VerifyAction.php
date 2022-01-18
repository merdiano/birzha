<?php

namespace TPS\Birzha\Actions;

use Illuminate\Support\Facades\Log;
use Mail;
use October\Rain\Exception\ApplicationException;
use RainLab\Notify\Classes\ActionBase;
use System\Models\MailTemplate;

class VerifyAction extends ActionBase
{
    public $recipientModes = [
        'email'    => 'Send verification email',
        'sms'   => 'Send verification sms',
        'email&sms'  => 'Send verification email and sms',
        'email_sms'  => 'Send verification sms to local numbers (+993), emails to foreign numbers',
    ];

    /**
     * Returns information about this event, including name and description.
     */
    public function actionDetails()
    {
        return [
            'name'        => 'Verify user',
            'description' => 'Verify user email/phone',
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
            'mail_template' => 'required_unless:send_to_mode,sms',
        ];
    }
    public function getMailTemplateOptions()
    {
        $codes = array_keys(MailTemplate::listAllTemplates());
        $result = array_combine($codes, $codes);
        return $result;
    }
    public function getTitle()
    {
        return 'Send vefification code tu user';
    }

    public function getActionIcon()
    {
        return 'icon-envelope-square';
    }

    public function triggerAction($params)
    {
//        Log::info(json_encode($params));
        $code = $params['user']['id'];
        $phone = $params['username'];
        $dial_code = $params['user']['dial_code'];

        switch ($this->host->send_to_mode) {
            case 'sms':
                $this->sendSMS($phone,$code);
                break;
            case 'email':
                $this->sendEmail($params['email'],$code);
                break;
            case 'email&sms':
                $this->sendSMS($phone,$code);
                $this->sendEmail($params['email'],$code);
                break;
            case 'email_sms':
                $dial_code === '+993' ? $this->sendSMS($phone,$code) : $this->sendEmail($params['email'],$code);
                break;
        }

    }

    private function sendMail($email,$code){
        $template = $this->host->mail_template;
        if (!$email || !$template) {
            throw new ApplicationException('Missing valid recipient or mail template');
        }

        Mail::sendTo($email, $template, ['code'=>$code], function($message){

        });
    }

    private function sendSMS($phone,$code){

    }

    public function getSendToModeOptions()
    {
        $modes = $this->recipientModes;

        return $modes;
    }
}
