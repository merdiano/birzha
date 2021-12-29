<?php
use Illuminate\Support\Facades\Route;
use TPS\Birzha\Classes\SMPP;
use TPS\Birzha\Classes\SmsBuilder;

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
    
    $tx=new SMPP('217.174.228.218', 5019); // make sure the port is integer
    $tx->debug=false;
    $tx->bindTransmitter("birja","Birj@1");
    $result = $tx->sendSMS("0773","99365611968","Hello world");
    echo $tx->getStatusMessage($result);
    $tx->close();
    unset($tx);
});
