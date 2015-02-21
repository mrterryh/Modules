<?php

namespace Mrterryh\Modules;

use Illuminate\Foundation\Application;
use Illuminate\Filesystem\Filesystem;

class Repository
{
	/**
	 * @var Application
	 */
	protected $app;

	/**
	 * @var Filesystem
	 */
	protected $filesystem;

	/**
	 * @var array|null
	 */
	protected $modules;

	/**
	 * Class constructor,
	 * @param Application $app
	 * @param Filesystem  $filesystem
	 */
	public function __construct(Application $app, Filesystem $filesystem)
	{
		$this->app = $app;
		$this->filesystem = $filesystem;
	}

	/**
	 * Boots the modules individually.
	 */
	public function register()
	{
		$modules = $this->getEnabledModules();

		foreach ($modules as $module)
			$module->registerServiceProvider();
	}

	/**
	 * Returns an array containing all enabled modules.
	 * @return array
	 */
	public function getEnabledModules()
	{
		$modules = $this->getModules();
		$enabledModules = [];

		foreach ($modules as $module) {
			if ($module->isEnabled()) $enabledModules[] = $module;
		}

		return $enabledModules;
	}

	/**
	 * Returns all modules in an array, either by returning the cached
	 * $modules property, or scanning the modules directory if it
	 * hasn't been populated yet.
	 * @return array
	 */
	public function getModules()
	{
		if (!$this->modules)
			$this->modules = $this->scanModulesDirectory();

		return $this->modules;
	}

	/**
	 * Returns a module by its name, or false if it doesn't exist.
	 * @param  string      $name
	 * @return Module|bool
	 */
	public function getByName($name)
	{
		foreach ($this->getModules() as $module) {
			if ($module->getName() == $name) return $module;
		}

		return false;
	}

	/**
	 * Scans the modules directory for modules and returns an array
	 * of Module objects.
	 * @return array
	 */
	protected function scanModulesDirectory()
	{
		$modules = [];

		$path = base_path('Modules');
		$files = $this->filesystem->glob($path . '/*/module.json');

		foreach ($files as $file) {
			$moduleName = dirname($file);
			$moduleData = $this->getJsonContents($file);

			$modules[] = $this->createModuleObject($moduleData->name);
		}

		return $modules;
	}

	/**
	 * Grabs the contents of the given JSON file
	 * and decodes it into an array.
	 * @param  string $filePath
	 * @return array
	 */
	protected function getJsonContents($filePath)
	{
		return json_decode($this->filesystem->get($filePath));
	}

	/**
	 * Creates a Module object.
	 * @param  string $identifier
	 * @return Module
	 */
	protected function createModuleObject($identifier)
	{
		return new Module($this->app, $identifier);
	}
}