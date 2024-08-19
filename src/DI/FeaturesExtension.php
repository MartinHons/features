<?php

declare(strict_types=1);

namespace MartinHons\Features\DI;

use MartinHons\Features\Config\Config;
use Nette\DI\CompilerExtension;
use Nette\Schema\Expect;
use Nette\Schema\Schema;

class FeaturesExtension extends CompilerExtension
{
	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'vitePort' => Expect::string()->dynamic()->required(),
			'buildFolder' => Expect::string()->dynamic()->default('build'),
			'modulePath' => Expect::string()->dynamic()->default('app/Modules'),
		]);
	}


	public function loadConfiguration()
	{
		$config = (array) $this->getConfig();
		$builder = $this->getContainerBuilder();

		$def = $builder->addDefinition($this->prefix('config'))->setType(Config::class);
		$def->setArgument('wwwDir', $builder->parameters['wwwDir']);
		$def->setArgument('debugMode', $builder->parameters['debugMode']);

		foreach ($config as $key => $value) {
			$def->setArgument($key, $value);
		}
	}
}
