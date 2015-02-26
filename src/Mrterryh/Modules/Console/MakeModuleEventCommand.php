<?php

namespace Mrterryh\Modules\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Mrterryh\Modules\Repository;
use Mrterryh\Modules\Generators\ModuleEventGenerator;

class MakeModuleEventCommand extends Command
{
	/**
	 * @var string
	 */
	protected $name = 'module:make-event';

	/**
	 * @var string
	 */
	protected $description = 'Creates a new event for the specified module.';

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
		$moduleName = $this->argument('moduleName');
		$module = $this->moduleRepo->getByName($moduleName);

		if (!$module)
			return $this->error("Module [$moduleName] does not exist.");

		$eventName = $this->argument('eventName');

		$generator = new ModuleEventGenerator($eventName, $this->laravel, $module);
		$generator->generate();

		$this->info("The event [$eventName] has been created for module [$moduleName].");
	}

	/**
	 * Get the console command arguments.
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['moduleName', InputArgument::REQUIRED, 'The name of your module.'],
			['eventName', InputArgument::REQUIRED, 'The name of your event.'],
		];
	}
}