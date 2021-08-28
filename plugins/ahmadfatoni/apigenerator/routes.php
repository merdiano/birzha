<?php

Route::post('fatoni/generate/api', array('as' => 'fatoni.generate.api', 'uses' => 'AhmadFatoni\ApiGenerator\Controllers\ApiGeneratorController@generateApi'));
Route::post('fatoni/update/api/{id}', array('as' => 'fatoni.update.api', 'uses' => 'AhmadFatoni\ApiGenerator\Controllers\ApiGeneratorController@updateApi'));
Route::get('fatoni/delete/api/{id}', array('as' => 'fatoni.delete.api', 'uses' => 'AhmadFatoni\ApiGenerator\Controllers\ApiGeneratorController@deleteApi'));

Route::resource('api/v1/categories', 'AhmadFatoni\ApiGenerator\Controllers\API\CategoriesAPIController', ['except' => ['destroy', 'create', 'edit']]);
Route::get('api/v1/categories/{id}/delete', ['as' => 'api/v1/categories.delete', 'uses' => 'AhmadFatoni\ApiGenerator\Controllers\API\CategoriesAPIController@destroy']);
Route::resource('api/v1/products', 'AhmadFatoni\ApiGenerator\Controllers\API\ProductsAPIController', ['except' => ['destroy', 'create', 'edit']]);
Route::get('api/v1/products/{id}/delete', ['as' => 'api/v1/products.delete', 'uses' => 'AhmadFatoni\ApiGenerator\Controllers\API\ProductsAPIController@destroy']);