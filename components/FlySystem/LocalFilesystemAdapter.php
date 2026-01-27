<?php

namespace app\components\FlySystem;

use DateTimeInterface;
use diecoding\flysystem\AbstractComponent;
use League\Flysystem\Config;
use League\Flysystem\PathPrefixer;
use League\Flysystem\UrlGeneration\PublicUrlGenerator;
use League\Flysystem\UrlGeneration\TemporaryUrlGenerator;
use yii\helpers\Url;

final class LocalFilesystemAdapter extends \League\Flysystem\Local\LocalFilesystemAdapter implements PublicUrlGenerator, TemporaryUrlGenerator
{
    public AbstractComponent $component;
    public bool              $skipPrefixer = false;

    public function publicUrl(string $path, Config $config): string
    {
        if (!$this->skipPrefixer && $this->component->prefix) {
            $prefixer = new PathPrefixer($this->component->prefix);
            $path     = $prefixer->stripPrefix($path);
        }

        return $this->generateUrlAction($path);
    }

    public function temporaryUrl(string $path, DateTimeInterface $expiresAt, Config $config): string
    {
        if (!$this->skipPrefixer && $this->component->prefix) {
            $prefixer = new PathPrefixer($this->component->prefix);
            $path     = $prefixer->stripPrefix($path);
        }

        return $this->generateUrlAction($path);
    }

    private function generateUrlAction(string $path): string
    {
        return Url::toRoute("{$this->component->action}/$path", true);
    }
}
