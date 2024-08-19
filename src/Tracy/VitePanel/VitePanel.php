<?php

declare(strict_types=1);

namespace App\Core\Tracy\VitePanel;

use Tracy\IBarPanel;

class VitePanel implements IBarPanel
{
	public function getTab(): string
	{
		return file_get_contents(__DIR__ . '/VitePanel.html');
	}


	public function getPanel(): string
	{
		return '';
	}
}
