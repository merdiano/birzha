<?php namespace Tps\Birzha\Components;

use Cms\Classes\ComponentBase;
use TPS\Birzha\Models\Contactmessage;

class ContactForm extends ComponentBase
{
    public function componentDetails() {
        return [
            'name' => 'Contact form',
            'description' => 'Contact form'
        ];
    }

    public function onSend() {
        $data = post();

        $rules = [
            'name' => 'required|max:100',
            'surname' => 'required|max:100',
            'mobile' => 'required|max:12',
            'email' => 'required|email|max:100',
            'content' => 'required'
        ];

        $validator = \Validator::make($data, $rules);

        if($validator->fails()) {
            throw new \ValidationException($validator);
        } else {
            
            // todo save message

            $contactMessage = new Contactmessage();
            $contactMessage->fill($data);
            $contactMessage->save();

            $vars = [
                'firstname' => \Input::get('name'),
                'lastname' => \Input::get('surname'),
                'mobile' => \Input::get('mobile'),
                'email' => \Input::get('email'),
                'content' => \Input::get('message')
            ];

            \Mail::send('tps.birzha::mail.message', $vars, function($message) {
                $message->to(env('TPS_EMAIL_GETTER'), 'Birzha Admin');
                $message->subject('Birzha web site contact form');
            });

            return [
                '#form-steps' => $this->renderPartial('@message_sent')
            ];
        }
    }
}