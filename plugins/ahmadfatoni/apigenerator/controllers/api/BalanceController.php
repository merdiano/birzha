<?php

namespace AhmadFatoni\ApiGenerator\Controllers\API;

use Cms\Classes\Controller;

class BalanceController extends Controller
{

    public function index(){

    }

    public function register(){

        if(! $amount = request('amount')){
            return response()->json(array('status' => 'error', 'message' =>'Amount must be given'));
        }

    }
}
