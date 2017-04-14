<?php namespace Dorantes\LaravelPug;

// Dependencies
use Illuminate\View\Engines\CompilerEngine;
use Pug\Pug;

class ServiceProvider extends \Illuminate\Support\ServiceProvider {

	/**
	 * Get the major Laravel version number
	 *
	 * @return integer
	 */
	public function version() {
		$app = $this->app;
		return intval($app::VERSION);
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {

		// Determine the cache dir
		$cache_dir = storage_path('/views');

		// Bind the package-configued Pug instance
		$this->app->singleton('laravel-pug.pug', function($app) {
			$config = $this->getConfig();
			return new Pug($config);
		});

		// Bind the Pug compiler
		$this->app->singleton('Dorantes\LaravelPug\PugCompiler', function($app) use ($cache_dir) {
			return new PugCompiler($app['laravel-pug.pug'], $app['files'], $cache_dir);
		});

		// Bind the Pug Blade compiler
		$this->app->singleton('Dorantes\LaravelPug\PugBladeCompiler', function($app) use ($cache_dir) {
			return new PugBladeCompiler($app['laravel-pug.pug'], $app['files'], $cache_dir);
		});

	}

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot() {

		if ($this->version()==4) {
			$this->package('dorantes/laravel-pug');
		}else{
			throw new Exception('Unsupported Laravel version');	
		}

		// Register compilers
		$this->registerPugCompiler();
		$this->registerPugBladeCompiler();
	}

	/**
	 * Register the regular Pug compiler
	 *
	 * @return void
	 */
	public function registerPugCompiler() {

		// Add resolver
		$this->app['view.engine.resolver']->register('pug', function() {
			return new CompilerEngine($this->app['Dorantes\LaravelPug\PugCompiler']);
		});

		// Add extensions
		$this->app['view']->addExtension('pug', 'pug');
		$this->app['view']->addExtension('pug.php', 'pug');
		$this->app['view']->addExtension('jade', 'pug');
		$this->app['view']->addExtension('jade.php', 'pug');
	}

	/**
	 * Register the blade compiler compiler
	 *
	 * @return void
	 */
	public function registerPugBladeCompiler() {

		// Add resolver
		$this->app['view.engine.resolver']->register('pug.blade', function() {
			return new CompilerEngine($this->app['Dorantes\LaravelPug\PugBladeCompiler']);
		});

		// Add extensions
		$this->app['view']->addExtension('pug.blade', 'pug.blade');
		$this->app['view']->addExtension('pug.blade.php', 'pug.blade');
		$this->app['view']->addExtension('jade.blade', 'jade.blade');
		$this->app['view']->addExtension('jade.blade.php', 'jade.blade');
	}

	/**
	 * Get the configuration, which is keyed differently in L5 vs l4
	 *
	 * @return array
	 */
	public function getConfig() {
		$loader = $this->app['config']->getLoader();

		 // Get environment name
		$env = $this->app['config']->getEnvironment();

		// Add package namespace with path set, override package if app config exists in the main app directory
		if (file_exists(app_path() . '/config/packages/dorantes/laravel-pug')) {
			$loader->addNamespace('namespace', app_path() . '/config/packages/dorantes/laravel-pug');
		} else {
			$loader->addNamespace('namespace', __DIR__ . '/../../config');
		}

		$config = $loader->load($env, 'config', 'namespace');

		// $this->app['config']->set('namespace::config', $config);

		return $config;
		
	}



	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides() {
		return array(
			'Dorantes\LaravelPug\PugCompiler',
			'Dorantes\LaravelPug\PugBladeCompiler',
			'laravel-pug.pug',
		);
	}

}
