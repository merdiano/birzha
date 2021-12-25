<?php
use Illuminate\Support\Facades\Route;
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
    
    (new SmsBuilder('217.174.228.218', '5019', 'birja', 'Birj@1', 10000))
    ->setRecipient('99365611968', \smpp\SMPP::TON_INTERNATIONAL) //msisdn of recipient
    ->sendMessage('Тестовое сообщение на русском and @noth3r$Ymb0ls');
});
