<?php

declare(strict_types=1);

namespace App\Core\Tracy\VitePanel;

use RuntimeException;
use Tracy\IBarPanel;

class VitePanel implements IBarPanel
{
	public function getTab(): string
	{
		$content = file_get_contents(__DIR__ . '/VitePanel.html');
		if ($content === false) {
			throw new RuntimeException('Failed to read the content of VitePanel.html');
		}
		return $content;
	}


	public function getPanel(): string
	{
		return '';
	}
}
