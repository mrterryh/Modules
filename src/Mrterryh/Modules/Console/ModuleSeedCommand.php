<?php

namespace Mrterryh\Modules\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Mrterryh\Modules\Repository;
use Mrterryh\Modules\Module;

class ModuleSeedCommand extends Command
{
	/**
	 * @var string
	 */
	protected $name = 'module:seed';

	/**
	 * @var string
	 */
	protected $description = 'Runs the database seeds for the specified module.';

	/**
	 * @var Repository
	 */
	protected $moduleRepo;

	/**
	 * Class constructor.
	 * @param Repository $moduleRepo
	 */
	public function __construct(Repository $moduleRepo)
	{
		$this->moduleRepo = $moduleRepo;

		parent::__construct();
	}

	/**
	 * Execute the console command.
	 */
	public function fire()
	{
		$name = $this->argument('name');
		$module = $this->moduleRepo->getByName($name);

		if (!$module)
			return $this->error("The [$name] module does not exist.");

		$params = $this->getParameters($module);

		$this->call('db:seed', $params);
	}

	/**
	 * Get the console command parameters.
	 * @param Module $module
	 * @return array
	 */
	protected function getParameters(Module $module)
	{
		$params = [];

		$namespace = $module->getNamespace();
		$dbSeeder = $module->getName() . 'DatabaseSeeder';
		$dbSeeder = $namespace . 'Database\Seeders\\' . $dbSeeder;

		$params['--class'] = $this->option('class') ? $this->option('class') : $dbSeeder;
		$params['--database'] = $this->option('database');

		return $params;
	}

	/**
	 * Get the console command arguments.
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['name', InputArgument::REQUIRED, 'The name of the module.'],
		];
	}

	/**
	 * Get the console command options.
	 * @return array
	 */
	protected function getOptions()
	{
		return [
			['class', null, InputOption::VALUE_OPTIONAL, 'The class name of the module\'s root seeder.'],
			['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to seed.']
		];
	}
}