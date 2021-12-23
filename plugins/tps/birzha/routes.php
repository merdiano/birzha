<?php
use Illuminate\Support\Facades\Route;
use TPS\Birzha\Classes\SmppReceiver;
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
Route::get('tm/check-sms', function() {
    // Construct transport and client
	$transport = new SocketTransport(array('217.174.228.218'),5019);
	$transport->setRecvTimeout(10000);
	$smpp = new SmppClient($transport);
	
	// Open the connection
	$transport->open();
	$smpp->bindTransmitter("birja","Birj@1");
	
	// Prepare message
	$message = 'Hello World €$£';
	$encodedMessage = GsmEncoder::utf8_to_gsm0338($message);
	$from = new SmppAddress('MelroseLabs',SMPP::TON_ALPHANUMERIC);
	$to = new SmppAddress(99365611968,SMPP::TON_INTERNATIONAL,SMPP::NPI_E164);
	
	// Send
	$messageID = $smpp->sendSMS($from,$to,$encodedMessage,null);
	
	// Close connection
	$smpp->close();
    dump('sms');
    // $transmitter = new SmppTransmitter();
    // $transmitter->sendSms('Hello from transmitter :)', '0773', '+99365611968');
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
