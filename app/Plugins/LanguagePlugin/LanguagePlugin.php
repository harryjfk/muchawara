<?php
use App\Components\PluginAbstract;
use App\Events\Event;
use App\Components\Plugin;
use App\Components\Theme;
use App\Repositories\Admin\UtilityRepository;
use App\Repositories\LanguageRepository;
use  Illuminate\Support\Facades\Auth;

class LanguagePlugin extends PluginAbstract
{
	public function website()
	{
		return 'datingframework.com';
	}

	public function author()
	{
		return 'DatingFramework';
	}

	public function description()
	{
		return 'This is the Multi-language Plugin.';
	}

	public function version()
	{
		return '1.0.0';
	}


	public function isCore()
	{
		return true;
	}



	public function setCarbonLocale($lang = "")
	{
		$path = base_path("vendor/nesbot/carbon/src/Carbon/Lang/{$lang}.php");
		
		if(file_exists($path)) {
			\Carbon\Carbon::setLocale($lang);
		}
	}



	protected function getRouteMiddleware($route)
	{
		$action = $route->getAction();
		return isset($action['middleware'])? $action['middleware'] : '';
	}


	public function hooks()
	{

		$defaultLang = UtilityRepository::get_setting('default_language');


		Plugin::add_hook('router.matched', function($route, $request) use($defaultLang) {

			$middleware = $this->getRouteMiddleware($route);

			if($middleware == "admin") {
				App::setLocale($defaultLang);
				$this->setCarbonLocale($defaultLang);
			} else {

				$authUser = Auth::user();

				if ($authUser && $authUser->language != "") {

					App::setLocale($authUser->language);
					$this->setCarbonLocale($authUser->language);

				} else if ( ($cookieLang = request()->cookie('language')) != "") {

					App::setLocale($cookieLang);
					$this->setCarbonLocale($cookieLang);
				} else {
					App::setLocale($defaultLang);
					$this->setCarbonLocale($defaultLang);
				}


			}

		});

		
		Theme::hook ('admin_content_links', function () {

			$url = url('/admin/languageSettings');

			$language_link = <<<LANG_LINK
				<li><a href="$url"><i class="fa fa-circle-o"></i> Language Content</a></li>							
LANG_LINK;

			return $language_link;

		});
 




		Theme::hook('top-header', function() use ($defaultLang) {

			$language = request()->cookie('language');
			$selected_language = ($language) ? $language : $defaultLang;
			$languages = (new LanguageRepository)->getSupportedLanguages();

			return Plugin::view('LanguagePlugin/language_button',[
				'langs' => $languages, 'selected_language' => $selected_language
			]);

		});



		Plugin::add_hook('account_create', function($user) use ($defaultLang){

			$language = request()->cookie('language');
			$user->language = ($language) ? $language : $defaultLang;
			$user->save();
		});


	}	


	public function autoload()
	{
		return array(
			Plugin::path('LanguagePlugin/controllers'),
			Plugin::path('LanguagePlugin/repositories'),
		);
	}

	public function routes()
	{
		
		Route::group(['middleware' => 'admin'], function(){

			//google admin settings view route
			Route::get('/admin/languageSettings', 'App\Http\Controllers\LanguageController@showSettings');
			Route::get('/admin/languageSettings/edit', 'App\Http\Controllers\LanguageController@get_edit_language');
			Route::post('/admin/languageSettings/edit/save', 'App\Http\Controllers\LanguageController@post_edit_language');
			Route::post('/admin/languageSettings/default/save', 'App\Http\Controllers\LanguageController@saveDefaultSettngs');
			//Route::post('/admin/languageSettings/selected/save', 'App\Http\Controllers\LanguageController@saveSelectedSettngs');

		});

		Route::post('set/language/cookie', 'App\Http\Controllers\LanguageController@setLanguageCookie');
	}

}
