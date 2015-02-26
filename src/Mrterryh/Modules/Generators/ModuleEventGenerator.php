<?php

namespace Mrterryh\Modules\Generators;

use Illuminate\Foundation\Application;
use Illuminate\Filesystem\Filesystem;
use Mrterryh\Modules\Module;

class ModuleEventGenerator extends Generator
{	
	/**
	 * @var string
	 */
	protected $eventName;

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
	protected $stub = 'moduleEvent.stub';

	/**
	 * Class constructor.
	 * @param string      $eventName
	 * @param Application $app
	 * @param Module      $module
	 */
	public function __construct($eventName, Application $app, Module $module)
	{
		$this->eventName = $eventName;
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
		$fileName = studly_case($this->eventName) . '.php';

		return $this->module->getPath() . 'Events/' . $fileName;
	}

	/**
	 * @param  string $contents
	 * @return string
	 */
	public function replaceStubContents($contents)
	{
		return str_replace('{className}', studly_case($this->eventName), $contents);
	}
}