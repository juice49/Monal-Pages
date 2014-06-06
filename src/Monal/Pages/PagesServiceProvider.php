<?php
namespace Monal\Pages;

use Illuminate\Support\ServiceProvider;
use Monal\MonalPackageServiceProvider;
use Illuminate\Database\Schema\Blueprint;

class PagesServiceProvider extends ServiceProvider implements MonalPackageServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var		Boolean
	 */
	protected $defer = false;

	/**
	 * Return the package's namespace.
	 *
	 * @return	String
	 */
	public function packageNamespace()
	{
		return 'monal/pages';
	}

	/**
	 * Return the package's details.
	 *
	 * @return	Array
	 */
	public function packageDetails()
	{
		return array(
			'name' => 'Pages',
			'author' => 'Arran Jacques',
			'version' => '0.9.0',
		);
	}

	/**
	 * Install the package.
	 *
	 * @return	Boolean
	 */
	public function install()
	{
		mkdir(public_path() . '/packages/pages');
		\Resource::cloneDirecotry(__DIR__ . '/../../../public', public_path() . '/packages/pages');
		\Schema::create(
			'pages',
			function(Blueprint $table) {
				$table->increments('id');
				$table->integer('page_type')->nullable();
				$table->string('name', 255)->nullable();
				$table->integer('parent')->nullable();
				$table->string('slug', 255)->nullable();
				$table->text('url')->nullable();
				$table->string('title', 255)->nullable();
				$table->text('description')->nullable();
				$table->text('keywords')->nullable();
				$table->tinyInteger('is_home')->nullable();
				$table->integer('order')->nullable();
				$table->timestamps();
			}
		);
		\Schema::create(
			'page_types',
			function(Blueprint $table) {
				$table->increments('id');
				$table->string('name', 255)->nullable();
				$table->string('table_prefix', 255)->nullable();
				$table->string('template', 255)->nullable();
				$table->text('data_set_templates')->nullable();
				$table->timestamps();
			}
		);
		return true;
	}

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
		$this->app->singleton(
			'Monal\Pages\Repositories\PagesRepository',
			function () {
				return new \Monal\Pages\Repositories\MonalPagesRepository;
			}
		);
		$this->app->singleton(
			'Monal\Pages\Repositories\PageTypesRepository',
			function () {
				return new \Monal\Pages\Repositories\MonalPageTypesRepository;
			}
		);

		// Register Facades
		$this->app['pageshelper'] = $this->app->share(function ($app) {
				return new \Monal\Pages\Libraries\PagesHelper;
		});
		$this->app->booting(function () {
			$loader = \Illuminate\Foundation\AliasLoader::getInstance();
			$loader->alias('PagesHelper', 'Monal\Pages\Facades\PagesHelper');
		});

		$this->app['pagesrepository'] = $this->app->share(function ($app) {
				return \App::make('Monal\Pages\Repositories\PagesRepository');
		});
		$this->app->booting(function () {
			$loader = \Illuminate\Foundation\AliasLoader::getInstance();
			$loader->alias('PagesRepository', 'Monal\Pages\Facades\PagesRepository');
		});

		$this->app['pagetypesrepository'] = $this->app->share(function ($app) {
				return \App::make('Monal\Pages\Repositories\PageTypesRepository');
		});
		$this->app->booting(function () {
			$loader = \Illuminate\Foundation\AliasLoader::getInstance();
			$loader->alias('PageTypesRepository', 'Monal\Pages\Facades\PageTypesRepository');
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
