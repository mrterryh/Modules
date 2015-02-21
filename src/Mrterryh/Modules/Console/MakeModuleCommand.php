<?php

namespace Mrterryh\Modules\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Mrterryh\Modules\Generators\ModuleGenerator;

class MakeModuleCommand extends Command
{
	/**
	 * @var string
	 */
	protected $name = 'module:make';

	/**
	 * @var string
	 */
	protected $description = 'Creates a new module.';

	/**
	 * Class constructor.
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$generator = new ModuleGenerator($this->argument('name'), $this->laravel['files'], $this);
		$generator->generate();
	}

	/**
	 * Get the console command arguments.
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['name', InputArgument::REQUIRED, 'The name of your module.'],
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