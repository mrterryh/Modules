<?php

namespace Mrterryh\Modules\Generators;

use Illuminate\Foundation\Application;
use Illuminate\Filesystem\Filesystem;
use Mrterryh\Modules\Module;

class ModuleCommandGenerator extends Generator
{	
	/**
	 * @var string
	 */
	protected $commandName;

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
	protected $stub = 'moduleCommand.stub';

	/**
	 * Class constructor.
	 * @param string      $commandName
	 * @param Application $app
	 * @param Module      $module
	 */
	public function __construct($commandName, Application $app, Module $module)
	{
		$this->commandName = $commandName;
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
	 * Returns the path to the destination file.
	 * @return string
	 */
	public function getFilePath()
	{
		$fileName = studly_case($this->commandName) . '.php';

		return $this->module->getPath() . 'Commands/' . $fileName;
	}

	/**
	 * @param  string $contents
	 * @return string
	 */
	public function replaceStubContents($contents)
	{
		return str_replace('{className}', studly_case($this->commandName), $contents);
	}
}