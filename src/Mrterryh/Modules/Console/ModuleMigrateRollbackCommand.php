<?php

namespace Mrterryh\Modules\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Mrterryh\Modules\Repository;
use Mrterryh\Modules\Module;

class ModuleMigrateRollbackCommand extends Command
{
	/**
	 * @var string
	 */
	protected $name = 'module:migrate-rollback';

	/**
	 * @var string
	 */
	protected $description = 'Rolls back the migrations for the given module.';

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
		$moduleName = $this->argument('name');
		$module = $this->moduleRepo->getByName($moduleName);

		if (!$module)
			return $this->error("Module [$moduleName] does not exist.");

		$migrationsPath = $module->getMigrationPath();
		$files = $this->laravel['files']->glob($migrationsPath . '*');

		foreach ($files as $file) {
			$this->laravel['files']->requireOnce($file);
		}

		$this->call('migrate:rollback', $this->getParameters($module));
	}

	/**
	 * Get the console command parameters.
	 * @param Module $module
	 * @return array
	 */
	protected function getParameters(Module $module)
	{
		$params = [];

		if ($option = $this->option('database')) $params['--database'] = $option;
		if ($option = $this->option('force')) $params['--force'] = $option;
		if ($option = $this->option('pretend')) $params['--pretend'] = $option;

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
			['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use.'],
			['force', null, InputOption::VALUE_NONE, 'Force the operation to run while in production.'],
			['pretend', null, InputOption::VALUE_NONE, 'Dump the SQL queries that would be run.']
		];
	}
}