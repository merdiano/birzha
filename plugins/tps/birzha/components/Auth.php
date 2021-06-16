<?php namespace TPS\Birzha\Components;

use Cms\Classes\ComponentBase;

class Auth extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Auth Form component',
            'description' => 'Signup Signin forms'
        ];
    }

    public function defineProperties()
    {
        return [
            'form_type' => [
                'title'       => 'Form type',
                'description' => 'Chose category page',
                'type'        => 'dropdown',
                'options'     => [
                    'signin' => 'Sign in',
                    'signup' => 'Sign up'
                ],
                'required' => true,
            ],
        ];
    }

    public function onSignup(){
        return [];
    }

    public function onSignin(){
        return [];
    }
}
