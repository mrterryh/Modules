<?php

namespace Mrterryh\Modules\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Mrterryh\Modules\Repository;
use Mrterryh\Modules\Generators\ModuleMigrationGenerator;

class MakeModuleMigrationCommand extends Command
{
	/**
	 * @var string
	 */
	protected $name = 'module:make-migration';

	/**
	 * @var string
	 */
	protected $description = 'Creates a new module database migration.';

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
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$moduleName = $this->argument('name');
		$module = $this->moduleRepo->getByName($moduleName);

		if (!$module)
			return $this->error("Module [$moduleName] does not exist.");

		$migrationName = $this->argument('migration');

		$generator = new ModuleMigrationGenerator($migrationName, $this->laravel, $module);
		$generator->generate();

		$this->info("The migration [$migrationName] has been created for module [$moduleName].");
	}

	/**
	 * Get the console command arguments.
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['name', InputArgument::REQUIRED, 'The name of your module.'],
			['migration', InputArgument::REQUIRED, 'The name of your migration.'],
		];
	}

	/**
	 * Get the console command options.
	 * @return array
	 */
	protected function getOptions()
	{
		return [];
	}
}