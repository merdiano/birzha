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

        $exRequest = $this->user->exchangerequests()->create([
            'content' => 'Exchange creating a request',
            'payed_for_request' => $request->get('fee'),
            'status' => 'failed', // before transaction is saved
            'currency' => $request->get('currency'),
            'total_price' => $request->get('total_price'),
            'converted_to_tmt' => $request->get('fee')
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