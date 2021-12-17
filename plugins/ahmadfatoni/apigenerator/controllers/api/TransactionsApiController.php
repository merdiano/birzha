<?php namespace AhmadFatoni\ApiGenerator\Controllers\API;

use Illuminate\Http\Request;
use Cms\Classes\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use October\Rain\Support\Facades\Event;
use TPS\Birzha\Models\Payment;
use TPS\Birzha\Classes\Payment as PaymentAPI;

class TransactionsApiController extends KabinetAPIController
{

    public function index(Request $request) {

        $validator = Validator::make($request->all(), [
            'transactions_per_page' => 'numeric',
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $transactions = $this->user->transactions()->paginate($request->transactions_per_page ? $request->transactions_per_page : 5);

        return response()->json($transactions, 200);
    }

    /**
     * Get user's balance
     */
    public function myBalance() {

        return response()->json($this->user->getBalance() . ' TMT', 200);
    }

    public function updateBalance(Request $request){

        $validator = Validator::make($request->all(), [
            'type' => 'required|in:bank,online',
            'amount' => 'required_if:type,online|numeric|gt:0',
            'bank_file' => 'required_if:type,bank|mimes:pdf,jpg,png',
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $payment = $this->createNewPayment(false, $request->all());

        if($payment->payment_type == 'online'){

            $payment->amount = $request->get('amount');

            $url = url('bank_result', ['payment_id' => $payment->id]);

            try {
                $response = PaymentAPI::registerOrder($payment, $url);

                $result = json_decode($response->body, true);

                if($result['errorCode'] == 0) {
                    $payment->order_id = $result['orderId'];

                    $payment->save();

                    return response()->json(['formUrl' => $result['formUrl']], 200);
                } else {
                    return response()->json('bank services are unavailable', 200);
                }

            } catch(\Throwable $th){
                $payment->status = 'failed';
                $payment->save();

                return response()->json(['message' => $th->getMessage()], 500);
            }
        }
        elseif($payment->payment_type == 'bank'){
            $payment->amount = 0;
            $payment->bank_file = \Input::file('bank_file');

            if($payment->save()){
                Event::fire('tps.payment.received',$payment);
                return response()->json('The payment saved. Admin will consider it and approve later', 200);
            } else {
                return response()->json('The payment saving failed.', 500);
            }
        }

        return response()->json('The payment saving failed.', 500);

    }


    protected function createNewPayment($bank_file, $formData) {
        $newPayment = new Payment;
        $newPayment->user_id = $this->user->id;
        $newPayment->amount = $formData['amount'] ?? 0;
        $newPayment->payment_type = $formData['type'];
        $newPayment->save();

        // attach file to payment
        if($bank_file) {
            $newPayment->bank_file = $bank_file;
            $newPayment->save();
        }

        return $newPayment;
    }

}
