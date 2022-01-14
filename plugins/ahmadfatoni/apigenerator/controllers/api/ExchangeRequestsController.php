<?php namespace AhmadFatoni\ApiGenerator\Controllers\API;

use Cms\Classes\Controller;
use Illuminate\Http\Request;

class ExchangeRequestsController extends KabinetAPIController
{
    protected $helpers;

    public function __construct()
    {
        parent::__construct();
    }

    public function withdrawFromBalance(Request $request) {

        $balance = $this->user->getBalance();

        if($balance - 2.50 < 0) {
            return response()->json([
                'status' => 300,
                'response' => null,
                'message' => 'Fill up your balance',
            ], 300);

        }

        $exRequest = $this->user->exchangerequests()->create([
            'content' => 'Exchange creating a request',
            'payed_for_request' => 2.50,
            'status' => 'failed'
        ]);

        return response()->json([
            'status' => 201,
            'response' => $exRequest,
            'message' => 'Successfully created response'
        ], 201);
    }
}