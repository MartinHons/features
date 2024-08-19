<?php

declare(strict_types=1);

namespace MartinHons\Features\Presenter;

use Exception;
use MartinHons\Features\Config\Config;
use Nette\Http\Request;
use Nette\IOException;
use Nette\Utils\FileSystem;
use Nette\Utils\Html;
use Nette\Utils\Strings;
use RuntimeException;
use stdClass;

trait TInitAssetTags
{
	private Request $request;

	private Config $config;


	public function injectRequest(Request $request): void
	{
		$this->request = $request;
	}


	public function injectConfig(Config $config): void
	{
		$this->config = $config;
	}


	/** Loads asset files from the manifest file and returns them as a string HTML tags */
	public function initAssetTags(): string
	{
		try {
			$manifest = FileSystem::read(sprintf('%s/%s/.vite/manifest.json', $this->config->getWwwDir(), $this->config->getBuildFolder()));
			$manifest = json_decode($manifest, true);
			if (!is_iterable($manifest)) {
				throw new RuntimeException('The manifest.json file seems corrupted.');
			}
		} catch (IOException) {
			throw new Exception('The manifest.json file was not generated. First run the command: "npm run build".');
		}

		$assetPaths = [];
		$url = '/' . $this->config->getBuildFolder() . '/';
		$devServer = $this->config->getDebugMode() && ($this->request->getCookie('viteDev') === 'true');
		if ($devServer) {
			$url = '//localhost:' . $this->config->getVitePort() . '/';
			$assetPaths[] = $url . '@vite/client';
		}

		$module = $this->getCurrentModule();
		foreach ($manifest as $source) {
			if (str_starts_with($source['src'], sprintf('%s/%s', $this->config->getModulePath(), $module))) {
				$assetPaths[] = $this->getAssetPath($source, $url, $devServer);
			}
		}

		return $this->assetTags($assetPaths);
	}


	/** Returns right path to asset */
	/**
	 * @param array<string|bool> $source
	 *
	 * @return string
	 */
	private function getAssetPath(array $source, string $url, bool $devServer): string
	{
		if ($devServer) {
			return $url . $source['src'];
		}
		return $url . $source['file'];
	}


	/**
	 * Creates HTML tags from the array of assets and returns them as a string
	 * @param array<string> $assetPaths
	 */
	private function assetTags(array $assetPaths): string
	{
		$tags = '';
		foreach ($assetPaths as $assetPath) {
			$ext = Strings::after($assetPath, '.', -1);

			if (in_array($ext, ['css', 'scss'], true)) {
				$tags .= Html::el('link')->rel('stylesheet')->href($assetPath);
			} else {
				$tags .= Html::el('script')->src($assetPath)->type('module');
			}
		}
		return $tags;
	}
}
