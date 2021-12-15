<?php namespace AhmadFatoni\ApiGenerator\Controllers\API;

use Illuminate\Http\Request;
use Cms\Classes\Controller;
use Illuminate\Support\Facades\Validator;
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
            'type' => 'required',
            'amount' => 'numeric|gt:0',
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $payment = new Payment([
            'status' => 'new',
            'user_id' => $this->user->id
        ]);

        if($request->get('type') == 'online'){

            if(! $amount = $request->get('type')) {
                return response()->json(['message' => 'bad_request'], 400);
            }

            $payment->amount = $amount;

            if($payment->save()){
                $url = url('bank_result', ['payment_id' => $payment->id]);

                try {
                    $response = PaymentAPI::registerOrder($payment,$url);

                    $result = json_decode($response->body,true);

                    if($result['errorCode'] == 0) {
                        $payment->order_id = $result['orderId'];

                        $payment->save();

                        return response()->json(['formUrl' => $result['formUrl']], 200);

                    }

                }catch(\Exception $ex){
                    return response()->json(['message' => $ex->getMessage()], 500);
                }

            }

        }
        elseif($request->get('type') == 'bank'){

            //todo

        }

    }

}
