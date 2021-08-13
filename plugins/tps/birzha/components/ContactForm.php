<?php namespace Tps\Birzha\Components;

use Cms\Classes\ComponentBase;

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
            'firstname' => 'required|max:100',
            'lastname' => 'required|max:100',
            'mobile' => 'required|max:12',
            'email' => 'required|email|max:100',
            'message' => 'required'
        ];

        $validator = \Validator::make($data, $rules);

        if($validator->fails()) {
            throw new \ValidationException($validator);
        } else {
            $vars = [
                'firstname' => \Input::get('firstname'),
                'lastname' => \Input::get('lastname'),
                'mobile' => \Input::get('mobile'),
                'email' => \Input::get('email'),
                'content' => \Input::get('message')
            ];

            \Mail::send('tps.birzha::mail.message', $vars, function($message) {
                $message->to('XXXXXXXXXXXXXX', 'Birzha Admin');
                $message->subject('Birzha web site contact form');
            });

            return [
                '#form-steps' => $this->renderPartial('@message_sent')
            ];
        }
    }
}