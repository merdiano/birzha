<?php namespace TPS\Birzha\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use Illuminate\Validation\Rule;
use TPS\Birzha\Models\Payment;

class Balance extends ComponentBase
{
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
                // ...
                break;
        }
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
}