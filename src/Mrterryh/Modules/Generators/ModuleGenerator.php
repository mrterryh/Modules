<?php

namespace Mrterryh\Modules\Generators;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Console\Command as Console;

class ModuleGenerator extends Generator
{
	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var Filesystem
	 */
	protected $filesystem;

	/**
	 * @var Console
	 */
	protected $console;

	/**
	 * @var array
	 */
	protected $foldersToGenerate = [
		'Commands',
		'Console',
		'Events',
		'Handlers',
		'Handlers/Commands',
		'Handlers/Events',
		'Http',
		'Http/Middleware',
		'Http/Controllers',
		'Http/Requests',
		'Providers',
		'Database',
		'Database/Seeders',
		'Database/Migrations',
		'Resources',
		'Resources/Views',
		'Resources/Language'
	];

	/**
	 * @var array
	 */
	protected $filesToCreate = [
		'Http/routes.php'							=>	'moduleRoutes.stub',
		'Providers/{name}ServiceProvider.php'		=>	'moduleProvider.stub',
		'Providers/RouteServiceProvider.php'		=>	'moduleRouteProvider.stub',
		'Providers/BusServiceProvider.php'			=>	'moduleBusProvider.stub',
		'module.json'								=>	'moduleJson.stub',
		'Database/Seeders/{name}DatabaseSeeder.php'	=>	'moduleDbSeeder.stub'
	];

	/**
	 * Class constructor.
	 * @param string     $name
	 * @param Filesystem $filesystem
	 * @param Console    $console
	 */
	public function __construct($name, Filesystem $filesystem, Console $console)
	{
		$this->name = $name;
		$this->filesystem = $filesystem;
		$this->console = $console;
	}

	/**
	 * Runs the generator.
	 */
	public function generate()
	{
		$this->createModulesDir();
		$this->createFolders();
		$this->createFiles();

		$this->console->info("Your module [$this->name] has been generated.");
	}

	/**
	 * Returns the name of the module.
	 * @return string
	 */
	public function getModuleName()
	{
		return $this->name;
	}

	/**
	 * Returns the path to the destination file.
	 * @return string
	 */
	public function getFilePath()
	{
		// Not needed for this class.
	}

	/**
	 * Creates the modules directory if it doesn't already exist.
	 */
	protected function createModulesDir()
	{
		$path = base_path('Modules');

		if (!$this->filesystem->isDirectory($path))
			$this->filesystem->makeDirectory($path);
	}

	/**
	 * Creates the module directory and other folders.
	 */
	protected function createFolders()
	{
		$path = base_path('Modules/' . $this->name);

		$this->filesystem->makeDirectory($path);

		foreach ($this->foldersToGenerate as $folder)
			$this->filesystem->makeDirectory($path . '/' . $folder);
	}

	/**
	 * Creates the module files.
	 */
	protected function createFiles()
	{
		foreach ($this->filesToCreate as $file => $stub) {
			$file = str_replace('{name}', $this->name, $file);
			$path = base_path('Modules/' . $this->name . '/' . $file);
			$stub = $this->getStubContents($stub);

			$this->filesystem->put($path, $stub);
		}
	}
}