<?php

Route::post('fatoni/generate/api', array('as' => 'fatoni.generate.api', 'uses' => 'AhmadFatoni\ApiGenerator\Controllers\ApiGeneratorController@generateApi'));
Route::post('fatoni/update/api/{id}', array('as' => 'fatoni.update.api', 'uses' => 'AhmadFatoni\ApiGenerator\Controllers\ApiGeneratorController@updateApi'));
Route::get('fatoni/delete/api/{id}', array('as' => 'fatoni.delete.api', 'uses' => 'AhmadFatoni\ApiGenerator\Controllers\ApiGeneratorController@deleteApi'));

Route::resource('api/v1/categories', 'AhmadFatoni\ApiGenerator\Controllers\API\CategoriesAPIController', ['except' => ['destroy', 'create', 'edit']]);
// Route::get('api/v1/categories/{id}/delete', ['as' => 'api/v1/categories.delete', 'uses' => 'AhmadFatoni\ApiGenerator\Controllers\API\CategoriesAPIController@destroy']);

Route::get('api/v1/products', ['as' => 'products.index', 'uses' => 'AhmadFatoni\ApiGenerator\Controllers\API\ProductsApiController@index']);
Route::get('api/v1/products/{id}', ['as' => 'products.show', 'uses' => 'AhmadFatoni\ApiGenerator\Controllers\API\ProductsApiController@show']);
Route::post('api/v1/products', ['as' => 'products.store', 'uses' => 'AhmadFatoni\ApiGenerator\Controllers\API\ProductsApiController@store'])->middleware('\Tymon\JWTAuth\Middleware\GetUserFromToken');
// Route::get('api/v1/products/{id}/delete', ['as' => 'api/v1/products.delete', 'uses' => 'AhmadFatoni\ApiGenerator\Controllers\API\ProductsApiController@destroy']);