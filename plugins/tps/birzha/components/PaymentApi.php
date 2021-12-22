<?php namespace Tps\Birzha\Components;

use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Redirect;
use October\Rain\Support\Facades\Event;
use TPS\Birzha\Models\Payment;
use October\Rain\Network\Http;
use TPS\Birzha\Models\Settings;
use TPS\Birzha\Classes\Payment as CardApi;

class PaymentApi extends ComponentBase
{
    public $balance_message;
    public $orderStatusCode;

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

        if($payment && \Input::get('status') === 'success' && \Input::get('orderId') === $payment->order_id) {
            $responce = json_decode(CardApi::getStatus($payment->order_id), true);

            // if( $responce['ErrorCode'] == 0 && $responce['OrderStatus'] == 2) {
            if( $responce['ErrorCode'] == 0) {

                // if page bank_result page is refreshed
                if($payment->status === 'approved') {
                    return Redirect::to('/');
                }

                $this->orderStatusCode = $responce['OrderStatus'];

                switch ($this->orderStatusCode) {
                    case 2: // OrderStatus 2 OK - the best scenario
                        $payment->status = 'approved';

                        if($payment->save()){
                            Event::fire('tps.payment.received',[$payment]);
                            $this->balance_message = trans('validation.balance.fill_up_succes');                            
                        }
                        break;
                    case 0:
                        $this->balance_message = trans('validation.balance.fill_up_succes_but_delayed');
                        break;
            
                    default:
                        $payment->status = 'failed';

                        if($payment->save()) {
                            $this->balance_message = trans('validation.balance.fill_up_fail');
                        }
                        break;
                }

            } else {
                // if page bank_result page is refreshed
                if($payment->status === 'approved') {
                    return Redirect::to('/');
                }
                
                $payment->status = 'failed';

                if($payment->save()) {
                    $this->balance_message = trans('validation.balance.fill_up_fail');
                }
                
            }
        } else {

            // if page bank_result page is refreshed
            if($payment->status === 'approved') {
                return Redirect::to('/');
            }
            
            $payment->status = 'failed';

            if($payment->save()) {
                $this->balance_message = trans('validation.balance.fill_up_fail');
            }
        }
    }
}
