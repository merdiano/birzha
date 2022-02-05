<?php namespace Tps\Birzha\Components;

use Cms\Classes\ComponentBase;

class EmailVerify extends ComponentBase
{
    public function componentDetails() {
        return [
            'name' => 'Email Verification',
            'description' => 'Email Verification'
        ];
    }

    public function defineProperties()
    {
        return [
            'code' => [
                'title'       => 'Verificaiton code',
                'description' => 'Verificaiton code',
                'default'     => '{{ :code }}',
                'type'        => 'string',
            ]
        ];
    }

    public function onRun() {
        $user = \Auth::user();

        if(!$user->email_verified) {
            if($user->email_activation_code == $this->property('code')) {
                $user->email_verified = true;
                $user->email_activation_code = null;
                $user->save();

                $this->page['message'] = 'Your email address has been succesfully verified';
                
            } else {

                $this->page['message'] = 'Invalid verification link';
            }
        } else {

            $this->page['message'] = 'You have already verified your email address';
        }
        
    }
}