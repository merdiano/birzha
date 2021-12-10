<?php namespace AhmadFatoni\ApiGenerator\Controllers\API;

use Illuminate\Http\Request;
use Cms\Classes\Controller;
use Illuminate\Support\Facades\Validator;

class TransactionsApiController extends Controller
{
    /**
     * Get user's transactions
     */
    public function index(Request $request) {
        if (!$user = \JWTAuth::parseToken()->authenticate()) {
            return response()->json('Unauthorized', 401);
        }

        $validator = Validator::make($request->all(), [
            'transactions_per_page' => 'numeric',
        ]);
        if($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $transactions = $user->transactions()->paginate($request->transactions_per_page ? $request->transactions_per_page : 5);
        
        return response()->json($transactions, 200);
    }

    /**
     * Get user's balance
     */
    public function myBalance() {
        if (!$user = \JWTAuth::parseToken()->authenticate()) {
            return response()->json('Unauthorized', 401);
        }

        return response()->json($user->getBalance() . ' TMT', 200);
    }
}