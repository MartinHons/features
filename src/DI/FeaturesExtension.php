<?php

declare(strict_types=1);

namespace MartinHons\NextrasUpdater\DI;

use Nette\DI\CompilerExtension;
use Nette\Schema\Expect;
use Nette\Schema\Schema;

class FeaturesExtension extends CompilerExtension
{

	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'vite_port' => Expect::string()->dynamic(),
			'build_folder' => Expect::string()->dynamic(),
			'module_path' => Expect::string()->dynamic(),
		]);
	}


	public function loadConfiguration()
	{
		$config = $this->getConfig();
		$builder = $this->getContainerBuilder();

		$builder->addDefinition('entityGenerator')->setFactory(EntityGenerator::class, [$config]);
	}

}
