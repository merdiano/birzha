<?php namespace Tps\Birzha\Components;

use Cms\Classes\ComponentBase;
use TPS\Birzha\Models\Payment;
use October\Rain\Network\Http;
use TPS\Birzha\Models\Settings;
use TPS\Birzha\Classes\Payment as CardApi;

class PaymentApi extends ComponentBase
{
    public $balance_message;
    
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

    public function onRun() {
        $payment_id = $this->property('payment_id');
        $payment = Payment::find($payment_id);

        if($payment && \Input::get('status') == 'success') {
            $responce = json_decode(CardApi::getStatus($payment->order_id), true);

            if( $responce['ErrorCode'] == 0 && $responce['OrderStatus'] == 2) {
                Payment::where('id', $payment_id)->update(['status' => 'payed']);

                $user = $payment->user;
                $user->balance += $payment->amount;
                $user->save();
                
                
                $this->balance_message = 'Баланс пополнен успешно';
                
            } else {
                $this->balance_message = 'Баланс не пополнен. Попробуйте позже';
            }
        } else {
            
            $this->balance_message = 'Баланс не пополнен. Попробуйте позже';
        }
    }
}