<?php
use Illuminate\Support\Facades\Route;
use October\Rain\Network\Http;
use October\Rain\Support\Facades\Http as FacadesHttp;

Route::namespace('TPS\Birzha\Controllers')->group(function () {

    Route::prefix('api')->group(function (){
        // api version
        Route::get('version', 'Settings@version')->name('version');
    });
});


// Route::get('bank_result/{payment_id}', ['as'=>'paymentReturn','uses'=>'...@checkPayment'] );

Route::get('check-sms', function() {
    // $response = Http::withHeaders([
    //     'Content-Type' => 'application/json'
        
    // ])->post('http://217.174.228.218/auth/jwt/create', [
    //                                         'username' => 'birja',
    //                                         'password' => 'Birj@1',
    //                                     ])->throw()->json();

    // $accessToken = $response['access'];

    // dd($accessToken);

    $client = Http::make('https://217.174.228.218:5019/auth/jwt/create', Http::METHOD_POST)->data([
        'username' => 'birja',
        'password' => 'Birj@1',
    ])->timeout(3600);

    $client->setOption(CURLOPT_POSTFIELDS,$client->getRequestData());
    dd($client->send());
});
