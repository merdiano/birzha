<?php

Route::post('fatoni/generate/api', array('as' => 'fatoni.generate.api', 'uses' => 'AhmadFatoni\ApiGenerator\Controllers\ApiGeneratorController@generateApi'));
Route::post('fatoni/update/api/{id}', array('as' => 'fatoni.update.api', 'uses' => 'AhmadFatoni\ApiGenerator\Controllers\ApiGeneratorController@updateApi'));
Route::get('fatoni/delete/api/{id}', array('as' => 'fatoni.delete.api', 'uses' => 'AhmadFatoni\ApiGenerator\Controllers\ApiGeneratorController@deleteApi'));

Route::group(['prefix' =>'api/v1','namespace' =>'AhmadFatoni\ApiGenerator\Controllers\API'],function ($route){
    Route::resource('categories', 'CategoriesAPIController', ['except' => ['destroy', 'create', 'edit']]);
// Route::get('categories/{id}/delete', ['as' => 'categories.delete', 'uses' => 'CategoriesAPIController@destroy']);

    Route::get('products', ['as' => 'products.index', 'uses' => 'ProductsApiController@index']);
    Route::get('products/{id}', ['as' => 'products.show', 'uses' => 'ProductsApiController@show']);
    Route::get('test',['as' => 'test', 'uses' => 'SmsController@index']);

// Route::get('products/{id}/delete', ['as' => 'products.delete', 'uses' => 'ProductsApiController@destroy']);

    Route::resource('countries', 'CountriesapiController', ['except' => ['destroy', 'create', 'edit']]);
    Route::resource('currencies', 'CurrenciesapiController', ['except' => ['destroy', 'create', 'edit']]);
    Route::resource('measures', 'MeasuresapiController', ['except' => ['destroy', 'create', 'edit']]);
// Route::get('measures/{id}/delete', ['as' => 'measures.delete', 'uses' => 'MeasuresapiController@destroy']);

    Route::resource('terms', 'TermsapiController', ['except' => ['destroy', 'create', 'edit']]);
// Route::get('terms/{id}/delete', ['as' => 'terms.delete', 'uses' => 'TermsapiController@destroy']);

    Route::post('send-contact-form', 'ContactFormApiController@sendContactForm');

    Route::middleware(['\Tymon\JWTAuth\Middleware\GetUserFromToken'])->group(function () {

        Route::post('products', 'ProductsApiController@store');
        Route::post('products/{id}', 'ProductsApiController@update')

            ->where('id', '[0-9]+');
        Route::delete('products/{id}/image-delete/{image_id}', 'ProductsApiController@imageDelete')
            ->where(['id' => '[0-9]+', 'image_id' => '[0-9]+']);
        Route::post('products/{id}/publish', 'ProductsApiController@publish')
            ->where('id', '[0-9]+');
        Route::get('my-products/','ProductsApiController@myProducts');
        Route::delete('my-products/{id}', 'ProductsApiController@delete')
            ->where('id', '[0-9]+');
        Route::resource('messages', 'MessagesapiController', ['except' => ['destroy', 'create', 'edit']]);
        Route::get('messages/chatroom/{id}', 'MessagesapiController@enterChatroom')
            ->where('id', '[0-9]+');
        Route::get('messages/chatroom/{id}/load-more', 'MessagesapiController@loadMore')
            ->where('id', '[0-9]+');
        Route::post('messages/{chatroom_id}', 'MessagesapiController@sendMessage')
            ->where('chatroom_id', '[0-9]+');
        Route::post('messages/initialize-chatting/{seller_id}', 'MessagesapiController@initializeChatting')
            ->where('seller_id', '[0-9]+');

        //Balance
        Route::post('balance_update','TransactionsApiController@updateBalance');
//        Route::post('balance_bank_transfer','BalanceController@createBankTransfer');

        Route::get('notifications', 'NotificationsApiController@index');
        Route::post('notifications/{id}/read', 'NotificationsApiController@markAsRead')
            ->where('id', '^(?=.*[a-z])(?=.*[\-])(?=.*\d)[a-z\d\-]{36,}$');

        Route::get('transactions', 'TransactionsApiController@index');
        Route::get('my-balance', 'TransactionsApiController@myBalance');

        Route::post('withdraw-from-balance', 'ExchangeRequestsController@withdrawFromBalance');

        Route::post('send-sms-code', 'SmsController@sendSmsCode');
        Route::post('check-sms-code', 'SmsController@checkSmsCode');

        Route::post('send-email-verification-link', 'EmailVerificationController@sendEmailVerificationLink');

    });
});

