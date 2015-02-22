<?php

namespace Mrterryh\Modules;

use Illuminate\Foundation\Application;

class Module
{
	/**
	 * @var Application
	 */
	protected $app;

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var array|null
	 */
	protected $data;

	/**
	 * Class constructor.
	 * @param Application $app
	 * @param string      $name
	 */
	public function __construct(Application $app, $name)
	{
		$this->app = $app;
		$this->name = $name;
	}

	/**
	 * Returns the module name.
	 * @return string
	 */
	public function getName()
	{
		return $this->getData()->name;
	}

	/**
	 * Returns the module namespace.
	 * @return string
	 */
	public function getNamespace()
	{
		return 'Modules\\' . $this->name . '\\';
	}

	/**
	 * Returns the data for the module.
	 * @return array
	 */
	public function getData()
	{
		if (!$this->data)
			$this->data = $this->loadData();

		return $this->data;
	}

	/**
	 * Determines whether the module is enabled.
	 * @return boolean
	 */
	public function isEnabled()
	{
		return $this->getData()->enabled;
	}

	/**
	 * Returns the path to the module directory.
	 * @return string
	 */
	public function getPath()
	{
		return base_path('Modules/' . $this->name . '/');
	}

	/**
	 * Returns the path to the database migrations directory.
	 * @return string
	 */
	public function getMigrationPath()
	{
		return $this->getPath() . 'Database/Migrations/';
	}

	/**
	 * Enables the module.
	 * @return mixed
	 */
	public function enable()
	{
		return $this->setData('enabled', true);
	}

	/**
	 * Disables the module.
	 * @return mixed
	 */
	public function disable()
	{
		return $this->setData('enabled', false);
	}

	/**
	 * Registers the service provider.
	 * @return mixed
	 */
	public function registerServiceProvider()
	{
		$provider = 'Modules\\' . $this->name . '\\Providers\\' . $this->name . 'ServiceProvider';

		return $this->app->register($provider);
	}

	/**
	 * Quicker method to access the module data.
	 * @param  string $key
	 * @return mixed
	 */
	public function __get($key)
	{
		return $this->getData()->$key;
	}

	/**
	 * Returns the JSON decoded module data, loaded from the module.json file.
	 * @return array
	 */
	protected function loadData()
	{
		$jsonPath = $this->getPath() . 'module.json';
		$contents = $this->app['files']->get($jsonPath);

		return json_decode($contents);
	}

	/**
	 * Sets a value in the module.json file and saves it if $save is true.
	 * @param  string  $key
	 * @param  mixed  $value
	 * @param  boolean $save
	 * @return mixed
	 */
	protected function setData($key, $value, $save = true)
	{
		$data = $this->getData();
		$data->$key = $value;

		if (!$save) return;

		$data = json_encode($this->data, \JSON_PRETTY_PRINT);
		$path = $this->getPath() . 'module.json';

		return $this->app['files']->put($path, $data);
	}
}