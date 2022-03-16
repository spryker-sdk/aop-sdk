<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\ManifestRequestTransfer;
use Generated\Shared\Transfer\ManifestResponseTransfer;
use Generated\Shared\Transfer\ManifestTransfer;
use org\bovigo\vfs\vfsStream;
use SprykerSdk\Zed\AppSdk\AppSdkConfig;
use SprykerTest\Shared\Testify\Helper\ConfigHelperTrait;
use SprykerTest\Zed\Testify\Helper\Business\BusinessHelperTrait;

class ManifestHelper extends Module
{
    use BusinessHelperTrait;
    use ConfigHelperTrait;

    /**
     * @var string|null
     */
    protected ?string $rootUrl = null;

    /**
     * @return \Generated\Shared\Transfer\ManifestRequestTransfer
     */
    public function haveManifestAddRequest(): ManifestRequestTransfer
    {
        $this->getValidatorHelper()->mockRoot($this->getRootUrl());

        $config = $this->getValidatorHelper()->getConfig() ?? new AppSdkConfig();

        $manifestTransfer = new ManifestTransfer();
        $manifestTransfer
            ->setName('Manifest3')
            ->setLocaleName('ZZ');

        $manifestRequestTransfer = new ManifestRequestTransfer();
        $manifestRequestTransfer
            ->setManifestPath($config->getDefaultManifestPath())
            ->setManifest($manifestTransfer);

        return $manifestRequestTransfer;
    }

    /**
     * @return string
     */
    protected function getRootUrl(): string
    {
        if (!$this->rootUrl) {
            $this->rootUrl = vfsStream::setup('root')->url();
        }

        return $this->rootUrl;
    }

    /**
     * return void
     *
     * @return void
     */
    public function haveManifestFile(): void
    {
        $this->prepareManifestFile(codecept_data_dir('app/manifest/valid/manifest_base.json'));
    }

    /**
     * @param string $pathToManifest
     *
     * @return void
     */
    protected function prepareManifestFile(string $pathToManifest): void
    {
        $filePath = sprintf('%s/config/app/manifest/en_US.json', $this->getRootUrl());

        if (!is_dir(dirname($filePath))) {
            mkdir(dirname($filePath), 0770, true);
        }
        file_put_contents($filePath, file_get_contents($pathToManifest));

        $this->getValidatorHelper()->mockRoot($this->getRootUrl());
    }

    /**
     * @return void
     */
    public function mockRootPath(): void
    {
        $root = vfsStream::setup('root');
        $this->getValidatorHelper()->mockRoot($root->url());
    }

    /**
     * @return \SprykerSdkTest\Helper\ValidatorHelper
     */
    protected function getValidatorHelper(): ValidatorHelper
    {
        return $this->getModule('\\' . ValidatorHelper::class);
    }

    /**
     * @param \Generated\Shared\Transfer\ManifestResponseTransfer $manifestResponseTransfer
     *
     * @return void
     */
    public function assertManifestResponseHasNoErrors(ManifestResponseTransfer $manifestResponseTransfer): void
    {
        $this->assertCount(0, $manifestResponseTransfer->getErrors(), sprintf(
            'Expected that no errors given but there are errors. Errors: "%s"',
            implode(', ', $this->getMessagesFromManifestResponseTransfer($manifestResponseTransfer)),
        ));
    }

    /**
     * @param \Generated\Shared\Transfer\ManifestResponseTransfer $manifestResponseTransfer
     *
     * @return array
     */
    public function getMessagesFromManifestResponseTransfer(ManifestResponseTransfer $manifestResponseTransfer): array
    {
        $messages = [];

        foreach ($manifestResponseTransfer->getErrors() as $messageTransfer) {
            $messages[] = $messageTransfer->getMessage();
        }

        return $messages;
    }
}
