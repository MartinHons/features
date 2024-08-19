<?php

declare(strict_types=1);

namespace MartinHons\Features\Presenter;

use Nette\Application\UI\Presenter as NettePresenter;
use Nette\Utils\Strings;
use RuntimeException;

abstract class Presenter extends NettePresenter
{
	use TInitAssetTags;

	public function startup()
	{
		parent::startup();
		$this->onRender[] = function () {
			$this->template->assetTags = $this->initAssetTags();
		};
	}


	/** Returns name of current module */
	public function getCurrentModule(): string
	{
		$moduleName = $this->getName();
		if ($moduleName === null) {
			throw new RuntimeException('Error reading current module name');
		}
		return Strings::before($moduleName, ':') . 'Module';
	}
}
