<?php namespace Tps\Birzha\Components;

use Cms\Classes\ComponentBase;

class PaymentApi extends ComponentBase
{
    public function componentDetails() {
        return [
            'name' => 'Payment API',
            'description' => 'Payment API'
        ];
    }

    public function defineProperties()
    {
        return [
            'payment_id' => [
                'title'       => 'Payment ID',
                'description' => 'Payment ID',
                'default'     => '{{ :payment_id }}',
                'type'        => 'string',
            ]
        ];
    }
}