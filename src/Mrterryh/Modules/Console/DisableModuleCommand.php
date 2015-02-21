<?php

namespace Mrterryh\Modules\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Mrterryh\Modules\Repository;

class DisableModuleCommand extends Command
{
	/**
	 * @var string
	 */
	protected $name = 'module:disable';

	/**
	 * @var string
	 */
	protected $description = 'Disables the specified module.';

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

		if (!$module->isEnabled())
			return $this->error("The [$name] module is already disabled.");

		$module->disable();

		$this->info("The [$name] module has been disabled.");
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
}