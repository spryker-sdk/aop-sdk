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

class ManifestHelper extends Module
{
    use AppSdkHelperTrait;

    /**
     * @return \Generated\Shared\Transfer\ManifestRequestTransfer
     */
    public function haveManifestCreateRequest(): ManifestRequestTransfer
    {
        $config = $this->getAppSdkHelper()->getConfig();

        $manifestTransfer = new ManifestTransfer();
        $manifestTransfer
            ->setName('Manifest')
            ->setLocaleName('en_US');

        $manifestRequestTransfer = new ManifestRequestTransfer();
        $manifestRequestTransfer
            ->setManifestPath($config->getDefaultManifestPath())
            ->setManifest($manifestTransfer);

        return $manifestRequestTransfer;
    }

    /**
     * return void
     *
     * @return void
     */
    public function haveManifestFile(): void
    {
        $this->prepareManifestFile(codecept_data_dir('valid/manifest/valid/manifest_base.json'));
    }

    /**
     * @param string $pathToManifest
     *
     * @return void
     */
    protected function prepareManifestFile(string $pathToManifest): void
    {
        $filePath = sprintf('%s/config/app/manifest/en_US.json', $this->getAppSdkHelper()->getRootPath());

        if (!is_dir(dirname($filePath))) {
            mkdir(dirname($filePath), 0770, true);
        }
        file_put_contents($filePath, file_get_contents($pathToManifest));
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
