<?php

namespace Mrterryh\Modules\Generators;

use Illuminate\Foundation\Application;
use Illuminate\Filesystem\Filesystem;
use Mrterryh\Modules\Module;

class ModuleRequestGenerator extends Generator
{	
	/**
	 * @var string
	 */
	protected $requestName;

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
	protected $stub = 'moduleRequest.stub';

	/**
	 * Class constructor.
	 * @param string      $requestName
	 * @param Application $app
	 * @param Module      $module
	 */
	public function __construct($requestName, Application $app, Module $module)
	{
		$this->requestName = $requestName;
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
	public function getRequestName()
	{
		return studly_case($this->requestName);
	}

	/**
	 * Returns the path to the destination file.
	 * @return string
	 */
	public function getFilePath()
	{
		return $this->module->getPath() . 'Http/Requests/' . $this->getRequestName();
	}

	/**
	 * @param  string $contents
	 * @return string
	 */
	public function replaceStubContents($contents)
	{
		return str_replace('{className}', studly_case($this->requestName), $contents);
	}
}