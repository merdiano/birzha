<?php
use Illuminate\Support\Facades\Route;
use TPS\Birzha\Classes\SmppTransmitter;

// use October\Rain\Network\Http;
// use October\Rain\Support\Facades\Http as FacadesHttp;
// use Http;

Route::namespace('TPS\Birzha\Controllers')->group(function () {

    Route::prefix('api')->group(function (){
        // api version
        Route::get('version', 'Settings@version')->name('version');
    });
});


// Route::get('bank_result/{payment_id}', ['as'=>'paymentReturn','uses'=>'...@checkPayment'] );

Route::get('check-sms', function() {
    $transmitter = new SmppTransmitter();
    $transmitter->sendSms('Hello from transmitter :)', '0773', '+99365611968');
    // $response = \Http::withHeaders([
    //     'Content-Type' => 'application/json'
        
    // ])->post('http://217.174.228.218/auth/jwt/create', [
    //                                         'username' => 'birja',
    //                                         'password' => 'Birj@1',
    //                                     ])->throw()->json();

    // $accessToken = $response['access'];

    // dd($accessToken);




    // $client = Http::make('http://217.174.228.218:5019/auth/jwt/create', Http::METHOD_POST)->data([
    //     'username' => 'birja',
    //     'password' => 'Birj@1',
    // ])->timeout(3600);

    // // $client->setOption(CURLOPT_POSTFIELDS,$client->getRequestData());
    // dd($client->send());




    // $response = \Http::post('http://217.174.228.218:5019/auth/jwt/create', function($http){

        // Sets a HTTP header
        // $http->header('Content-Type', 'application/json');
     
        // Use basic authentication
        // $http->auth('birja', 'Birj@1');
     
        // Sends data with the request
        // $http->data('foo', 'bar');
        // $http->data(['key' => 'value', ...]);
     
        // Sets the timeout duration
        // $http->timeout(3600);
     
        // Sets a cURL option manually
        // $http->setOption(CURLOPT_SSL_VERIFYHOST, false);
     
    // })->throw()->json();

    // $accessToken = $response['access'];

    // dd($accessToken);
});
