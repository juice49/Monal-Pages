<?php
namespace Monal\Pages;

use Illuminate\Support\ServiceProvider;

class PagesServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var		Boolean
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return	Void
	 */
	public function boot()
	{
		$this->package('monal/pages');

		$routes = __DIR__.'/../../routes.php';
		if (file_exists($routes)){
			include $routes;
		}

		\Monal::registerMenuOption('Pages', 'Pages', 'pages', 'pages');
		\Monal::registerMenuOption('Pages', 'Page Types', 'page-types', 'page-types');

		\Monal::registerFrontendRouteLogic(function($url_segments) {
			$pages_repo = \App::make('Monal\Pages\Repositories\PagesRepository');
			if (empty($url_segments)) {
				if ($page = $pages_repo->retrieveHomePage()) {
					$controller = new \FrontendPagesController(\Monal::instance());
					return $controller->page($page);
				}
			} else {
				$url = '';
				foreach ($url_segments as $segment) {
					$url .= '/' . $segment;
				}
				if ($page = $pages_repo->retrieveByURL($url)) {
					$controller = new \FrontendPagesController(\Monal::instance());
					return $controller->page($page);
				}
			}
		});
	}

	/**
	 * Register the service provider.
	 *
	 * @return	Void
	 */
	public function register()
	{
		$this->app->bind(
			'Monal\Pages\Models\PageType',
			function () {
				return new \Monal\Pages\Models\MonalPageType;
			}
		);
		$this->app->bind(
			'Monal\Pages\Models\Page',
			function () {
				return new \Monal\Pages\Models\MonalPage;
			}
		);
		$this->app->bind(
			'Monal\Pages\Models\FrontendPage',
			function ($app, $parameters) {
				return new \Monal\Pages\Models\MonalFrontendPage($parameters);
			}
		);
		$this->app->bind(
			'Monal\Pages\Repositories\PagesRepository',
			function () {
				return new \Monal\Pages\Repositories\MonalPagesRepository;
			}
		);
		$this->app->bind(
			'Monal\Pages\Repositories\PageTypesRepository',
			function () {
				return new \Monal\Pages\Repositories\MonalPageTypesRepository;
			}
		);

		// Register Facades
		$this->app['pageshelper'] = $this->app->share(
			function ($app) {
				return new \Monal\Pages\Libraries\PagesHelper;
			}
		);
		$this->app->booting(
			function () {
			$loader = \Illuminate\Foundation\AliasLoader::getInstance();
			$loader->alias('PagesHelper', 'Monal\Pages\Facades\PagesHelper');
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return	Array
	 */
	public function provides()
	{
		return array();
	}
}
