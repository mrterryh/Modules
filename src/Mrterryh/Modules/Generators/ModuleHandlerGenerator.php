<?php

namespace Mrterryh\Modules\Generators;

use Illuminate\Foundation\Application;
use Illuminate\Filesystem\Filesystem;
use Mrterryh\Modules\Module;

class ModuleHandlerGenerator extends Generator
{	
	/**
	 * @var string
	 */
	protected $handlerName;

	/**
	 * @var string
	 */
	protected $handlingName;

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
	protected $stub = 'moduleHandler.stub';

	/**
	 * Class constructor.
	 * @param string      $handlerName
	 * @param string      $handlerType
	 * @param Application $app
	 * @param Module      $module
	 */
	public function __construct($handlerName, $handlingName, $handlerType, Application $app, Module $module)
	{
		$this->handlerName = $handlerName;
		$this->handlerType = $handlerType;
		$this->handlingName = $handlingName;
		$this->app = $app;
		$this->module = $module;

		$this->stub = $handlerType == 'event' ? 'moduleEventHandler.stub' : 'moduleCommandHandler.stub';
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
		$fileName = studly_case($this->handlerName) . '.php';

		return $this->module->getPath() . 'Handlers/' . ucfirst($this->handlerType) . 's/' . $fileName;
	}

	/**
	 * @param  string $contents
	 * @return string
	 */
	public function replaceStubContents($contents)
	{
		$contents = str_replace('{className}', studly_case($this->handlerName), $contents);
		$contents = str_replace('{handlerType}', studly_case($this->handlerType), $contents);
		$contents = str_replace('{handlingClass}', studly_case($this->handlingName), $contents);

		return $contents;
	}
}