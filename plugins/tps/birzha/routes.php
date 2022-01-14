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

