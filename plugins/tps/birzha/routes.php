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
    
    $S = new \TPS\Birzha\Classes\SMSC_SMPP();
    $S->send_sms("99365611968", "test message");
    if ($S->send_sms("99365611968", "Тестовое сообщение", "sender"))
        echo "Сообщение отправлено";
    else
        echo "Произошла ошибка";
});
