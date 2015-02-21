<?php

namespace Mrterryh\Modules\Generators;

use Illuminate\Foundation\Application;
use Illuminate\Filesystem\Filesystem;
use Mrterryh\Modules\Module;

class ModuleMigrationGenerator extends Generator
{	
	/**
	 * @var string
	 */
	protected $migrationName;

	/**
	 * @var Application
	 */
	protected $app;

	/**
	 * @var Module
	 */
	protected $module;

	/**
	 * @var string
	 */
	protected $stub = 'moduleDbMigration.stub';

	/**
	 * Class constructor.
	 * @param string      $migrationName
	 * @param Application $app
	 * @param Module      $module
	 */
	public function __construct($migrationName, Application $app, Module $module)
	{
		$this->migrationName = $migrationName;
		$this->app = $app;
		$this->module = $module;
	}

	/**
	 * Returns the name of the module.
	 * @return string
	 */
	public function getModuleName()
	{
		return $this->module->getName();
	}

	/**
	 * Generates a migration filename.
	 * @return string
	 */
	public function getMigrationName()
	{
		return date('Y_m_d_His') . '_' . studly_case($this->migrationName) . '.php';
	}

	/**
	 * Returns the path to the destination file.
	 * @return string
	 */
	public function getFilePath()
	{
		$migrationName = $this->getMigrationName();

		return $this->module->getPath() . 'Database/Migrations/' . $migrationName;
	}

	/**
	 * @param  string $contents
	 * @return string
	 */
	public function replaceStubContents($contents)
	{
		return str_replace('{className}', studly_case($this->migrationName), $contents);
	}
}