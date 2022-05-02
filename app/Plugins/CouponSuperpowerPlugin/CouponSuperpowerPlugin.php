<?php

use App\Components\PluginAbstract;
use App\Components\Theme;
use App\Components\Plugin;

class CouponSuperpowerPlugin extends PluginAbstract
{
	public function productID()
	{
		return "31";
	}

	public function author()
	{
		return 'DatingFramework';
	}

	public function description()
	{
		return 'Enable users to activate superpower by entering coupon code';
	}

	public function version()
	{
		return '1.0.0';
	}
	public function website()
	{
		return 'datingframework.com';
	}


	public function __construct()
	{
		$this->cn = "App\\Plugins\\CouponSuperpowerPlugin\\Controllers";
		$this->rn = "App\\Plugins\\CouponSuperpowerPlugin\\Repositories";
		$this->couponSuperpowerRepo = app($this->rn.'\\CouponSuperpowerRepository');
	}


	public function hooks()
	{
		Theme::hook('admin_plugin_menu', [$this->couponSuperpowerRepo, 'registerAdminMenuHooks']);
		Theme::hook('javascript_plugin_hooks', [$this->couponSuperpowerRepo, 'registerJavascriptPluginHooks']);
		Theme::hook('payment_body', [$this->couponSuperpowerRepo, 'registerPaymentBodyHook']);
		Plugin::add_hook('coupon_superpower_activated', [$this->couponSuperpowerRepo, 'registerCoupoActivationNotif']);
		Plugin::add_hook('users_deleted', [$this->couponSuperpowerRepo, 'registerUserDelete']);
	}	

	public function autoload()
	{
		return [];
	}

	public function routes()
	{
		Route::group(["middleware" => "admin"], function(){
			Route::get('admin/plugins/coupon-superpower/settings', $this->cn."\\CouponSuperpowerController@showCouponSettings");
			Route::post('admin/plugins/coupon-superpower/coupon/create', $this->cn."\\CouponSuperpowerController@createCoupon");
			Route::get('admin/plugins/coupon-superpower/coupon/lists', $this->cn."\\CouponSuperpowerController@couponLists");
			Route::post('admin/plugins/coupon-superpower/coupon/update', $this->cn."\\CouponSuperpowerController@updateCoupon");
			Route::post('admin/plugins/coupon-superpower/coupon/delete', $this->cn."\\CouponSuperpowerController@deleteCoupon");
			Route::post('admin/plugins/coupon-superpower/coupon/activate', $this->cn."\\CouponSuperpowerController@activateCoupon");
			Route::post('admin/plugins/coupon-superpower/coupon/deactivate', $this->cn."\\CouponSuperpowerController@deActivateCoupon");			
		});



		Route::group(["middleware" => "auth"], function(){
			Route::post('coupon/superpower/activate', $this->cn."\\CouponSuperpowerController@activateSuperpower");
		});


	}
}