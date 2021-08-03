<?php namespace Tps\Birzha\Components;

use Cms\Classes\ComponentBase;
use TPS\Birzha\Models\Payment;
use October\Rain\Network\Http;
use TPS\Birzha\Models\Settings;

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

    public function onRun() {
        $payment_id = $this->property('payment_id');
        $payment = Payment::find($payment_id);

        if($payment && \Input::get('status') == 'success') {
            $responce = json_decode($this->getStatus($payment->order_id), true);

            if( $responce['ErrorCode'] == 0 && $responce['OrderStatus'] == 2) {
                Payment::where('id', $payment_id)->update(['status' => 'payed']);
                
                // show successful message
                
            } else {
                // show error message
            }
        } else {
            // show error message
        }
    }

    protected function getStatus($order_id) {
        $client = self::getClient('getOrderStatus.do');

        $client->data([
            'orderId' => $order_id
        ]);

        return $client->send();
    }

    private static function getClient($url) {
        return Http::make('https://mpi.gov.tm/payment/rest/'.$url, Http::METHOD_POST)->data([
            'userName' => Settings::get('api_login'),
            'password' => Settings::get('api_password'),
        ])->timeout(3600);
    }
}