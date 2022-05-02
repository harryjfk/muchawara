<?php

/*registering admin routes*/

$namespace = "App\\Plugins\\AdminPhotoVerifyPlugin\\Controllers";

Route::get('admin-photo-verify-plugin/get-code', "{$namespace}\\AdminPhotoVerifyController@getCode");
Route::post('admin-photo-verify-plugin/send-verify-request', "{$namespace}\\AdminPhotoVerifyController@saveVerifyRequest");
