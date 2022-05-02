<?php

	use App\Components\Plugin;


	/*admin routes*/

	Route::group(['middleware' => 'admin'], function(){

		Route::get('admin/plugins/payu/settings', 'App\Plugin\PayUPlugin\Controllers\PayUPluginController@showAdminSettings');
		Route::post('admin/plugins/payu/save-settings', 'App\Plugin\PayUPlugin\Controllers\PayUPluginController@saveAdminSettings');
		Route::post('admin/plugins/payu/country-accountid/remove', 'App\Plugin\PayUPlugin\Controllers\PayUPluginController@removeCountryAccountID');
		Route::post('admin/plugins/payu/country-accountid/add', 'App\Plugin\PayUPlugin\Controllers\PayUPluginController@addCountryAccountID');

	});


	/*auth routes*/
	
	Route::group(['middleware' => 'auth'], function(){

		Route::post('plugins/payu/get-reference-and-signature', 'App\Plugin\PayUPlugin\Controllers\PayUPluginController@getReferenceCodeAndSignature');

		Route::get('plugins/payu/response', 'App\Plugin\PayUPlugin\Controllers\PayUPluginController@payuResponse');

	});





	/*payu confirm notification webhook route*/
	Plugin::removeCSRFToken('plugins/payu/confirmation');
	Route::post('plugins/payu/confirmation', 'App\Plugin\PayUPlugin\Controllers\PayUPluginController@payuConfirmation');
