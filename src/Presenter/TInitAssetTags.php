<?php

declare(strict_types=1);

namespace MartinHons\Features\Control;

use Exception;
use Nette\Http\Request;
use Nette\IOException;
use Nette\Utils\FileSystem;
use Nette\Utils\Html;
use Nette\Utils\Strings;
use stdClass;

trait TInitAssetTags
{
    private Request $request;
    public function injectRequest(Request $request): void
    {
        $this->request = $request;
    }

    public function initAssetTags(): string
    {
        try {
            $manifest = FileSystem::read('../www/build/.vite/manifest.json');
        }
        catch(IOException) {
            throw new Exception('The manifest.json file was not generated. First run the command: "npm run build".');
        }

        $assetPaths = [];
        $url = '/build/';
        $devServer = DEBUG_MODE && ($this->request->getCookie('viteDev') === 'true');
        if ($devServer) {
            $url = 'http://localhost:'.getenv('VITE_PORT').'/';
            $assetPaths[] =  $url.'@vite/client';
        }

        $module = $this->getCurrentModule();
        foreach(json_decode($manifest) as $source) {
            if(str_starts_with($source->src, 'app/Modules/'.$module)) {
                $assetPaths[] = $this->getSourcePath($source, $url, $devServer);
            }
        }

        return $this->assetTags($assetPaths);
    }

    private function getSourcePath(stdClass $source, string $url, bool $devServer): string
    {
        if ($devServer) {
            return $url . $source->src;
        }
        return $url . $source->file;
    }

    private function assetTags(array $assetPaths): string
    {
        $tags = '';
        foreach($assetPaths as $assetPath) {
            $ext = Strings::after($assetPath, '.', -1);

            if (in_array($ext, ['css','scss'])) {
                $tags .= Html::el('link')->rel('stylesheet')->href($assetPath);
            }
            else {
                $tags .= Html::el('script')->src($assetPath)->type('module');
            }
        }
        return $tags;
    }
}
