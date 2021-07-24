<?php
use Illuminate\Support\Facades\Route;
Route::namespace('TPS\Birzha\Controllers')->group(function () {

    Route::prefix('api')->group(function (){
        // api version
        Route::get('version', 'Settings@version')->name('version');
    });
});


// Route::get('payment-result', ['as'=>'paymentReturn','uses'=>'...@checkPayment'] );
