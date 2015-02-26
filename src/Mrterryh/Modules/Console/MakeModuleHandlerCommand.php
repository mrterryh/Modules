<?php

namespace Mrterryh\Modules\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Mrterryh\Modules\Repository;
use Mrterryh\Modules\Generators\ModuleHandlerGenerator;

class MakeModuleHandlerCommand extends Command
{
	/**
	 * @var string
	 */
	protected $name = 'module:make-handler';

	/**
	 * @var string
	 */
	protected $description = 'Creates a new handler (event or command) for the specified module.';

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
		$handlerType = $this->argument('type');

		if ($handlerType != 'event' && $handlerType != 'command')
			return $this->error("Invalid handler type: [$handlerType].");

		$moduleName = $this->argument('moduleName');
		$module = $this->moduleRepo->getByName($moduleName);

		if (!$module)
			return $this->error("Module [$moduleName] does not exist.");

		$handlerName = $this->argument('handlerName');
		$handlingName = $this->argument('handlingName');

		$generator = new ModuleHandlerGenerator($handlerName, $handlingName, $handlerType, $this->laravel, $module);
		$generator->generate();

		$this->info("The handler [$handlerName] has been created for module [$moduleName].");
	}

	/**
	 * Get the console command arguments.
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['type', InputArgument::REQUIRED, 'The type of handler to create (event or command).'],
			['moduleName', InputArgument::REQUIRED, 'The name of your module.'],
			['handlerName', InputArgument::REQUIRED, 'The name of your handler.'],
			['handlingName', InputArgument::REQUIRED, 'The name of the class that the handler is handling.']
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