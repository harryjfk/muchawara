<?php

/*registering admin routes*/

$namespace = "App\\Plugins\\AdminPhotoVerifyPlugin\\Controllers";

Route::get('admin/plugins/admin-photo-verify-plugin/request/pending', "{$namespace}\\AdminPhotoVerifyController@showPendingRequests");
Route::get('admin/plugins/admin-photo-verify-plugin/request/verified', "{$namespace}\\AdminPhotoVerifyController@showVerifiedRequests");
Route::post('admin/plugins/admin-photo-verify-plugin/request/doaction', "{$namespace}\\AdminPhotoVerifyController@doAction");
Route::post('admin/plugins/admin-photo-verify-plugin/upload-icon', "{$namespace}\\AdminPhotoVerifyController@saveIcon");