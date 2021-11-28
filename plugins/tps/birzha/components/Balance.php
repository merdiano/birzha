<?php namespace TPS\Birzha\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use Illuminate\Validation\Rule;
use October\Rain\Exception\AjaxException;
use October\Rain\Support\Facades\Event;
use TPS\Birzha\Models\Payment;
use October\Rain\Network\Http;
use TPS\Birzha\Models\Settings;
use TPS\Birzha\Classes\Payment as CardApi;

class Balance extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Balance',
            'description' => 'Balance'
        ];
    }

    /**
     * On online payment
     */
    public function onSend() {
        $data = post();

        $rules = [
            // 'payment_type' => 'required',
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
                    $this->page['err_message'] = trans('validation.balance.bank_service_unavailable');
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

        $url = $this->controller->pageUrl('bank_result.htm', ['payment_id' => $payment->id]);

        $response = CardApi::registerOrder($payment, $url);

        $result = json_decode($response->body,true);

        if($result['errorCode'] == 0) {
            $payment->order_id = $result['orderId'];

            $payment->save();

            return $result['formUrl'];
        } else {
            return false;
        }
    }

    /**
     * On bank transfer payment
     */
    public function onPayByBankTransfer() {
        $data = input();

        $rules = [
            'bank_file' => 'required|mimes:pdf,jpg,png',
        ];

        $this->validateForm($data, $rules);

        $newPayment = new Payment;
        $newPayment->user_id = \Auth::user()->id;
        $newPayment->amount = 0;
        $newPayment->payment_type = "bank";
//        $newPayment->status = "new";
//        $newPayment->save();

        // attach file to payment
        $newPayment->bank_file = \Input::file('bank_file');
        if($newPayment->save()){
            Event::fire('tps.payment.received',$newPayment);
            return [
                '#form-steps' => $this->renderPartial('@payment_finish')
            ];
        }else{
            throw new AjaxException('Toleg kabul edilmedi.');
        }

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
//        $newPayment->status = "new";
        $newPayment->save();

        // attach file to payment
        if($bank_file) {
            $newPayment->bank_file = $bank_file;
            $newPayment->save();
        }

        return $newPayment;
    }
}
