<?php namespace TPS\Card\Classes;
use October\Rain\Network\Http;
use TPS\Card\Models\Application as CardApp;
use TPS\Card\Models\Settings;

class Payment
{

    const REGISTRATION_URI = 'register.do';

    const STATUS_URI = 'getOrderStatus.do';

    const API_URL = 'https://mpi.gov.tm/payment/rest/';

    private static function getClient($url){
        return Http::make(self::API_URL.$url, Http::METHOD_POST)->data([
                'userName' => Settings::get('bank_api_user'),
                'password' => Settings::get('bank_api_password'),
        ])->timeout(3600);
    }

    public static function registerOrder($order_id){

        $client = self::getClient(self::REGISTRATION_URI);

        $client->data([
            'amount'      => Settings::get('application_fee')*100,//multiply by 100 to obtain tenge
            'currency' => 934,
            'description' => 'Kart üçin döwlet pajy.',
            'orderNumber'     => strtoupper(str_random(5)) . date('jn'),

            'failUrl'     => route('paymentReturn', [
                'app_id'             => $order_id,
                'is_payment_cancelled' => 1
            ]),
            'returnUrl' => route('paymentReturn', [
                'app_id'              => $order_id,
                'is_payment_successful' => 1
            ]),

        ]);

        $client->setOption(CURLOPT_POSTFIELDS,$client->getRequestData());
//        dd($client);
        return $client->send();

    }

    public static function getStatus($order_id){
        $client = self::getClient(self::STATUS_URI);

        $client->data([
            'orderId' => $order_id
        ]);

        return $client->send();
    }
}
