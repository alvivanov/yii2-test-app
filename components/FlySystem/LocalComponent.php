<?php

namespace app\components\FlySystem;

use diecoding\flysystem\AbstractComponent;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\PathNormalizer;
use League\Flysystem\PathPrefixing\PathPrefixedAdapter;
use Yii;

final class LocalComponent extends AbstractComponent
{
    public string $action;

    public string $path;

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        $this->validateProperties(['path', 'action']);

        parent::init();
    }

    /**
     * @param PathNormalizer|null $pathNormalizer type must be nullable
     *
     * @inheritDoc
     */
    public function normalizePath(string $path, ?PathNormalizer $pathNormalizer = null): string
    {
        return parent::normalizePath($path, $pathNormalizer);
    }

    protected function initAdapter(): FilesystemAdapter
    {
        $this->path = (string) Yii::getAlias($this->path);

        $adapter            = new LocalFilesystemAdapter($this->path);
        $adapter->component = $this;

        if ($this->prefix) {
            $adapter = new PathPrefixedAdapter($adapter, $this->prefix);
        }

        return $adapter;
    }
}
