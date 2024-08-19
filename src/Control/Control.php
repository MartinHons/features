<?php

declare(strict_types=1);

namespace MartinHons\Features\Control;

use Nette\Application\UI\Control as NetteControl;

abstract class Control extends NetteControl
{
	protected Props $props;


	/**
	 * @param array<mixed> $props
	 */
	public function setProps(array $props): void
	{
		$propsObj = new ($this->getPropsClass())($props);
		if ($propsObj instanceof Props) {
			$this->props = $propsObj;
			$this->template->props = $this->props;
		}
	}


	abstract public function getPropsClass(): string;
}
