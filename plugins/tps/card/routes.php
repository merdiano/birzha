<?php
Route::group(['prefix' => 'card-application'], function() {

    Route::get('payment-result', ['as'=>'paymentReturn','uses'=>'TPS\Card\Controllers\Applications@checkPayment'] );

});
