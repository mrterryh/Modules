<?php

namespace Mrterryh\Modules;

use Illuminate\Support\ServiceProvider;

class ModulesServiceProvider extends ServiceProvider
{
	/**
	 * Indicates if loading of the provider is deferred.
	 */
	protected $defer = false;

	/**
	 * @var array
	 */
	protected $commands = [
		'Mrterryh\Modules\Console\MakeModuleCommand',
		'Mrterryh\Modules\Console\EnableModuleCommand',
		'Mrterryh\Modules\Console\DisableModuleCommand',
		'Mrterryh\Modules\Console\MakeModuleMigrationCommand',
		'Mrterryh\Modules\Console\ModuleMigrateCommand',
	];

	/**
	 * Register the service provider.
	 */
	public function register()
	{
		$this->app->bindShared('modules', function($app) {
			return new Repository($app, $app['files']);
		});

		$this->commands($this->commands);
	}

	/**
	 * Boot the service provider.
	 */
	public function boot()
	{
		$this->app['modules']->register();
	}

	/**
	 * Get the services provided by the provider.
	 * @return array
	 */
	public function provides()
	{
		return ['modules'];
	}
}