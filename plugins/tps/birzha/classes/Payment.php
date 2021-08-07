<?php namespace TPS\Birzha\Classes;
use October\Rain\Network\Http;
use TPS\Birzha\Models\Settings;

class Payment
{

    const REGISTRATION_URI = 'register.do';

    const STATUS_URI = 'getOrderStatus.do';

    const API_URL = 'https://mpi.gov.tm/payment/rest/';

    private static function getClient($url){
        return Http::make(self::API_URL.$url, Http::METHOD_POST)->data([
            'userName' => Settings::getValue('api_login'),
            'password' => Settings::getValue('api_password'),
        ])->timeout(3600);
    }

    public static function registerOrder($payment, $url) {
        $client = self::getClient(self::REGISTRATION_URI);

        $client->data([
            'amount'      => $payment->amount * 100,//multiply by 100 to obtain tenge
            'currency' => 934,
            'description' => 'Balansa pul goşmak - Birža şahsy otag',
            'orderNumber'     => strtoupper(str_random(5)) . date('jn'),
            'failUrl'     => $url . '?status=failed',
            'returnUrl' => $url . '?status=success',
        ]);
        $client->setOption(CURLOPT_POSTFIELDS,$client->getRequestData());
        return $client->send();
    }

    public static function getStatus($order_id) {
        $client = self::getClient(self::STATUS_URI);

        $client->data([
            'orderId' => $order_id
        ]);

        return $client->send();
    }
}
