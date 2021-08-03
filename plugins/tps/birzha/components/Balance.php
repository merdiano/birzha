<?php namespace TPS\Birzha\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use Illuminate\Validation\Rule;
use TPS\Birzha\Models\Payment;
use October\Rain\Network\Http;
use TPS\Birzha\Models\Settings;

class Balance extends ComponentBase
{
    // bank api configs and urls
    const REGISTRATION_URI = 'register.do';

    const STATUS_URI = 'getOrderStatus.do';

    const API_URL = 'https://mpi.gov.tm/payment/rest/';

    public function componentDetails()
    {
        return [
            'name'        => 'Balance',
            'description' => 'Balance'
        ];
    }

    public function onSend() {
        $data = post();

        $rules = [
            'payment_type' => 'required',
            'amount' => 'required|numeric'
        ];

        $this->validateForm($data, $rules);

        switch ($data['payment_type']) {
            case 'bank':
                $this->page['amount'] = $data['amount'];
                return [
                    '#form-steps' => $this->renderPartial('@bank_transfer_pay')
                ];
            case 'online':
                $url = $this->payOnline($data);
                if(!$url) {
                    $this->page['err_message'] = 'Не удается подключиться к сервисам банка. Попробуйте позже';
                    return [
                        '#form-steps' => $this->renderPartial('@message')
                    ];
                }

                $this->page['url'] = $url;
                return [
                    '#form-steps' => $this->renderPartial('@redirect')
                ];
        }
    }

    protected function payOnline($formData) {
        $payment = $this->createNewPayment(false, $formData);
        $response = $this->registerOrder($payment);

        $result = json_decode($response->body,true);

        if($result['errorCode'] == 0) {
            $payment->order_id = $result['orderId'];

            $payment->save();

            return $result['formUrl'];
        } else {
            return false;
        }
    }

    protected function registerOrder($payment) {
        $client = self::getClient(self::REGISTRATION_URI);

        $url = $this->controller->pageUrl('bank_result.htm', ['payment_id' => $payment->id]);

        $client->data([
            'amount'      => $payment->amount * 100,//multiply by 100 to obtain tenge
            'currency' => 934,
            'description' => 'Kart üçin döwlet pajy.',
            'orderNumber'     => strtoupper(str_random(5)) . date('jn'),
            'failUrl'     => $url . '?status=failed',
            'returnUrl' => $url . '?status=success',
        ]);
        $client->setOption(CURLOPT_POSTFIELDS,$client->getRequestData());
        return $client->send();
    }

    private static function getClient($url) {
        return Http::make(self::API_URL.$url, Http::METHOD_POST)->data([
            'userName' => Settings::getValue('api_login'),
            'password' => Settings::getValue('api_password'),
        ])->timeout(3600);
    }

    public function onPayByBankTransfer() {
        $data = input();

        $rules = [
            'bank_file' => 'required|mimes:pdf,jpg,png',
        ];

        $this->validateForm($data, $rules);
        
        $newPayment = new Payment;
        $newPayment->user_id = \Auth::user()->id;
        $newPayment->amount = $data['amount'];
        $newPayment->payment_type = "bank";
        $newPayment->status = "new";
        $newPayment->save();

        // attach file to payment
        $newPayment->bank_file = \Input::file('bank_file');
        $newPayment->save();

        \Flash::success('Администратор просмотрит ваш документ оплаты, и средства перейдут на ваш баланс. Спасибо');
        return \Redirect::back();
    }

    protected function validateForm($data, $rules) {
        $validator = \Validator::make($data, $rules);

        if($validator->fails()) {
            throw new \ValidationException($validator);
        }
    }

    protected function createNewPayment($bank_file, $formData) {
        $newPayment = new Payment;
        $newPayment->user_id = \Auth::user()->id;
        $newPayment->amount = $formData['amount'];
        $newPayment->payment_type = $formData['payment_type'];
        $newPayment->status = "new";
        $newPayment->save();

        // attach file to payment
        if($bank_file) {
            $newPayment->bank_file = $bank_file;
            $newPayment->save();
        }

        return $newPayment;
    }
}