<?php

declare(strict_types=1);

namespace MartinHons\Features\Control;

use Nette\Schema\Expect;
use Nette\Schema\Processor;
use stdClass;

abstract class Props
{
	private stdClass $data;


	public function __construct(array $props)
	{
		$this->data = (new Processor)->process(Expect::structure($this->define()), $props);
	}


	public function __get(string $name): mixed
	{
		return $this->data->{$name};
	}


	abstract protected function define(): array;
}
