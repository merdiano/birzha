<?php

Route::post('fatoni/generate/api', array('as' => 'fatoni.generate.api', 'uses' => 'AhmadFatoni\ApiGenerator\Controllers\ApiGeneratorController@generateApi'));
Route::post('fatoni/update/api/{id}', array('as' => 'fatoni.update.api', 'uses' => 'AhmadFatoni\ApiGenerator\Controllers\ApiGeneratorController@updateApi'));
Route::get('fatoni/delete/api/{id}', array('as' => 'fatoni.delete.api', 'uses' => 'AhmadFatoni\ApiGenerator\Controllers\ApiGeneratorController@deleteApi'));

Route::resource('api/v1/categories', 'AhmadFatoni\ApiGenerator\Controllers\API\CategoriesAPIController', ['except' => ['destroy', 'create', 'edit']]);
// Route::get('api/v1/categories/{id}/delete', ['as' => 'api/v1/categories.delete', 'uses' => 'AhmadFatoni\ApiGenerator\Controllers\API\CategoriesAPIController@destroy']);

Route::get('api/v1/products', ['as' => 'products.index', 'uses' => 'AhmadFatoni\ApiGenerator\Controllers\API\ProductsApiController@index']);
Route::get('api/v1/products/{id}', ['as' => 'products.show', 'uses' => 'AhmadFatoni\ApiGenerator\Controllers\API\ProductsApiController@show']);
Route::post('api/v1/products', ['as' => 'products.store', 'uses' => 'AhmadFatoni\ApiGenerator\Controllers\API\ProductsApiController@store'])->middleware('\Tymon\JWTAuth\Middleware\GetUserFromToken');
Route::post('api/v1/products/{id}', ['as' => 'products.complete.step2', 'uses' => 'AhmadFatoni\ApiGenerator\Controllers\API\ProductsApiController@update'])->where('id', '[0-9]+')->middleware('\Tymon\JWTAuth\Middleware\GetUserFromToken');
Route::delete('api/v1/products/{id}/image-delete/{image_id}', ['as' => 'products.images.delete', 'uses' => 'AhmadFatoni\ApiGenerator\Controllers\API\ProductsApiController@imageDelete'])->where(['id' => '[0-9]+', 'image_id' => '[0-9]+'])->middleware('\Tymon\JWTAuth\Middleware\GetUserFromToken');
Route::post('api/v1/products/{id}/publish', ['as' => 'products.publish', 'uses' => 'AhmadFatoni\ApiGenerator\Controllers\API\ProductsApiController@publish'])->where('id', '[0-9]+')->middleware('\Tymon\JWTAuth\Middleware\GetUserFromToken');
Route::get('api/v1/my-products/', ['as' => 'products.my_products', 'uses' => 'AhmadFatoni\ApiGenerator\Controllers\API\ProductsApiController@myProducts'])->middleware('\Tymon\JWTAuth\Middleware\GetUserFromToken');
Route::delete('api/v1/my-products/{id}', ['as' => 'products.my_products.delete', 'uses' => 'AhmadFatoni\ApiGenerator\Controllers\API\ProductsApiController@delete'])->where('id', '[0-9]+')->middleware('\Tymon\JWTAuth\Middleware\GetUserFromToken');
// Route::get('api/v1/products/{id}/delete', ['as' => 'api/v1/products.delete', 'uses' => 'AhmadFatoni\ApiGenerator\Controllers\API\ProductsApiController@destroy']);

Route::resource('api/v1/countries', 'AhmadFatoni\ApiGenerator\Controllers\API\CountriesapiController', ['except' => ['destroy', 'create', 'edit']]);
// Route::get('api/v1/countries/{id}/delete', ['as' => 'api/v1/countries.delete', 'uses' => 'AhmadFatoni\ApiGenerator\Controllers\API\CountriesapiController@destroy']);

Route::resource('api/v1/currencies', 'AhmadFatoni\ApiGenerator\Controllers\API\CurrenciesapiController', ['except' => ['destroy', 'create', 'edit']]);
// Route::get('api/v1/currencies/{id}/delete', ['as' => 'api/v1/currencies.delete', 'uses' => 'AhmadFatoni\ApiGenerator\Controllers\API\CurrenciesapiController@destroy']);

Route::resource('api/v1/measures', 'AhmadFatoni\ApiGenerator\Controllers\API\MeasuresapiController', ['except' => ['destroy', 'create', 'edit']]);
// Route::get('api/v1/measures/{id}/delete', ['as' => 'api/v1/measures.delete', 'uses' => 'AhmadFatoni\ApiGenerator\Controllers\API\MeasuresapiController@destroy']);

Route::resource('api/v1/terms', 'AhmadFatoni\ApiGenerator\Controllers\API\TermsapiController', ['except' => ['destroy', 'create', 'edit']]);
// Route::get('api/v1/terms/{id}/delete', ['as' => 'api/v1/terms.delete', 'uses' => 'AhmadFatoni\ApiGenerator\Controllers\API\TermsapiController@destroy']);

Route::resource('api/v1/messages', 'AhmadFatoni\ApiGenerator\Controllers\API\MessagesapiController', ['except' => ['destroy', 'create', 'edit']])->middleware('\Tymon\JWTAuth\Middleware\GetUserFromToken');
Route::get('api/v1/messages/chatroom/{id}', 'AhmadFatoni\ApiGenerator\Controllers\API\MessagesapiController@enterChatroom')->where('id', '[0-9]+')->middleware('\Tymon\JWTAuth\Middleware\GetUserFromToken');
Route::get('api/v1/messages/chatroom/{id}/load-more', 'AhmadFatoni\ApiGenerator\Controllers\API\MessagesapiController@loadMore')->where('id', '[0-9]+')->middleware('\Tymon\JWTAuth\Middleware\GetUserFromToken');
Route::post('api/v1/messages/{chatroom_id}', 'AhmadFatoni\ApiGenerator\Controllers\API\MessagesapiController@sendMessage')->where('chatroom_id', '[0-9]+')->middleware('\Tymon\JWTAuth\Middleware\GetUserFromToken');
Route::post('api/v1/messages/initialize-chatting/{seller_id}', 'AhmadFatoni\ApiGenerator\Controllers\API\MessagesapiController@initializeChatting')->where('seller_id', '[0-9]+')->middleware('\Tymon\JWTAuth\Middleware\GetUserFromToken');
// Route::get('api/v1/messages/{id}/delete', ['as' => 'api/v1/messages.delete', 'uses' => 'AhmadFatoni\ApiGenerator\Controllers\API\MessagesapiController@destroy']);

Route::get('api/v1/notifications', 'AhmadFatoni\ApiGenerator\Controllers\API\NotificationsApiController@index')->middleware('\Tymon\JWTAuth\Middleware\GetUserFromToken');
Route::post('api/v1/notifications/{id}/read', 'AhmadFatoni\ApiGenerator\Controllers\API\NotificationsApiController@markAsRead')->where('id', '^(?=.*[a-z])(?=.*[\-])(?=.*\d)[a-z\d\-]{36,}$')->middleware('\Tymon\JWTAuth\Middleware\GetUserFromToken');
