<?php

namespace Mrterryh\Modules\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Mrterryh\Modules\Repository;
use Mrterryh\Modules\Module;

class ModuleListCommand extends Command
{
	/**
	 * @var string
	 */
	protected $name = 'module:list';

	/**
	 * @var string
	 */
	protected $description = 'Lists all modules.';

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
		$modules = $this->moduleRepo->getModules();

		foreach ($modules as $module)
			$rows[] = $this->moduleToRow($module);

		$this->table(['Name', 'Description', 'Status'], $rows);
	}

	/**
	 * Converts a module object to a table row.
	 * @param  Module $module
	 * @return array
	 */
	public function moduleToRow(Module $module)
	{
		return [
			'name'			=>	$module->name,
			'description'	=>	$module->description,
			'enabled'		=>	$module->isEnabled() ? 'Enabled' : 'Disabled'
		];
	}
}