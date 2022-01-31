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
        
        $vars = array_merge($request->all(), [
            'phone' => $this->user->username,
            'status' => $exRequest->status,
            'withdraw_from_balance' => $exRequest->transaction->amount
        ]);

        $admin_email = Settings::getValue('admin_email');
        
        if($admin_email) {
            \Mail::send('tps.birzha::mail.requests', $vars, function($message) use ($admin_email){
                $message->to($admin_email, 'Birzha Admin');
                $message->subject('Биржа - Запрос пользователя (раздел Импортные цены)');
            });
        }

        return response()->json([
            'status' => 201,
            'response' => $exRequest,
            'message' => 'Successfully created response'
        ], 201);
    }
}