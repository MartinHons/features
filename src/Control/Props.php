<?php

declare(strict_types=1);

namespace MartinHons\Features\Control;

use Nette\Schema\Expect;
use Nette\Schema\Processor;
use Nette\Schema\Schema;
use RuntimeException;
use stdClass;

abstract class Props
{
	private stdClass $data;


	/**
	 * @param array<mixed> $props
	 */
	public function __construct(array $props)
	{
		$data = (new Processor)->process(Expect::structure($this->define()), $props);
		if (!$data instanceof stdClass) {
			throw new RuntimeException('Error while props creating');
		}
		$this->data = $data;
	}


	public function __get(string $name): mixed
	{
		return $this->data->{$name};
	}


	/**
	 * @return array<Schema>
	 */
	abstract protected function define(): array;
}
