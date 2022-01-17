<?php namespace AhmadFatoni\ApiGenerator\Controllers\API;

use Illuminate\Http\Request;
use TPS\Birzha\Models\Settings;

class ExchangeRequestsController extends KabinetAPIController
{
    protected $helpers;

    public function __construct()
    {
        parent::__construct();
    }

    public function withdrawFromBalance(Request $request) {

        $balance = $this->user->getBalance();

        $fee = $request->get('total_price');

        if($request->get('currency') == 'USD') {
            $fee = $request->get('total_price') * Settings::getValue('dollar');
        }

        if($balance - $fee < 0) {
            return response()->json([
                'status' => 300,
                'response' => null,
                'message' => 'Fill up your balance',
            ], 300);

        }

        $exRequest = $this->user->exchangerequests()->create([
            'content' => 'Exchange creating a request',
            'payed_for_request' => $fee,
            'status' => 'failed', // before transaction is saved
            'currency' => $request->get('currency'),
            'total_price' => $request->get('total_price'),
            'converted_to_tmt' => $fee
        ]);

        if(!is_null($exRequest->transaction)) {
            $exRequest->update(['status' => 'success']);
        }

        return response()->json([
            'status' => 201,
            'response' => $exRequest,
            'message' => 'Successfully created response'
        ], 201);
    }
}