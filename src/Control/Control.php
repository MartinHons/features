<?php

declare(strict_types=1);

namespace MartinHons\Features\Control;

use Nette\Application\UI\Control as NetteControl;

abstract class Control extends NetteControl
{
	protected Props $props;


	public function setProps(array $props): void
	{
		$this->props = new ($this->getPropsClass())($props);
		$this->template->props = $this->props;
	}


	abstract public function getPropsClass(): string;
}
