<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Helper;

use Codeception\Module;
use Transfer\ManifestCollectionTransfer;
use Transfer\ManifestConfigurationTransfer;
use Transfer\ManifestRequestTransfer;
use Transfer\ManifestResponseTransfer;
use Transfer\ManifestTransfer;

class ManifestHelper extends Module
{
    use AcpHelperTrait;

    /**
     * @return \Transfer\ManifestRequestTransfer
     */
    public function haveManifestCreateRequest(string $locale = 'en_US'): ManifestRequestTransfer
    {
        $config = $this->getAcpHelper()->getConfig();

        $manifestTransfer = new ManifestTransfer();
        $manifestTransfer
            ->setName('Manifest')
            ->setLocaleName($locale);

        $manifestRequestTransfer = new ManifestRequestTransfer();
        $manifestRequestTransfer
            ->setManifestPath($config->getDefaultManifestPath())
            ->setManifest($manifestTransfer);

        return $manifestRequestTransfer;
    }

    /**
     * @return \Transfer\ManifestCollectionTransfer
     */
    public function haveManifestCollection(): ManifestCollectionTransfer
    {
        $manifestCollectionTransfer = new ManifestCollectionTransfer();
        $configurationTransfer = new ManifestConfigurationTransfer();

        $manifestCollectionTransfer->setConfiguration($configurationTransfer);
        $configurationTransfer->setConfiguration([
            'properties' => [
                [
                    'title' => 'Title',
                    'placeholder' => 'Placeholder',
                    'oneOf' => [
                        [
                            'description' => 'Description1',
                        ],
                    ],
                    'items' => [
                        'oneOf' => [
                            [
                                'description' => 'Description2',
                                'enum' => [
                                    'Option2',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        return $manifestCollectionTransfer;
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
     * @param $locale
     *
     * @return mixed
     */
    public function haveRealManifestExampleData($locale = 'en_US')
    {
        $manifestRealExamplePath = codecept_absolute_path('config/app/manifest/' . $locale . '.json');

        return json_decode(
            file_get_contents($manifestRealExamplePath),
            true,
            512,
            JSON_THROW_ON_ERROR,
        );
    }

    /**
     * @param string $pathToManifest
     *
     * @return void
     */
    protected function prepareManifestFile(string $pathToManifest): void
    {
        $filePath = sprintf('%s/config/app/manifest/en_US.json', $this->getAcpHelper()->getRootPath());

        if (!is_dir(dirname($filePath))) {
            mkdir(dirname($filePath), 0770, true);
        }
        file_put_contents($filePath, file_get_contents($pathToManifest));
    }

    /**
     * @param \Transfer\ManifestResponseTransfer $manifestResponseTransfer
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
     * @param \Transfer\ManifestResponseTransfer $manifestResponseTransfer
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
