<?php

declare(strict_types=1);

namespace MartinHons\Features\Config;


final class Config
{
	public function __construct(
		private string $wwwDir,
		private string $vitePort,
		private string $buildFolder,
		private string $modulePath,
		private bool $debugMode,
	) {
		$this->modulePath = trim($modulePath, '/');
	}


	public function getVitePort(): string
	{
		return $this->vitePort;
	}


	public function getBuildFolder(): string
	{
		return $this->buildFolder;
	}


	public function getWwwDir(): string
	{
		return $this->wwwDir;
	}


	public function getModulePath(): string
	{
		return $this->modulePath;
	}


	public function getDebugMode(): bool
	{
		return $this->debugMode;
	}
}
