<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Helper;

use Codeception\Module;

class TranslationHelper extends Module
{
    use AcpHelperTrait;

    /**
     * return void
     *
     * @return void
     */
    public function haveManifestsAndConfigurationFile(): void
    {
        $structure = [
            'config' => [
                'app' => [
                    'configuration.json' => file_get_contents(codecept_data_dir('valid/configuration/translation.json')),
                    'manifest' => [
                        'en_US.json' => file_get_contents(codecept_data_dir('valid/manifest/en_US.json')),
                        'de_DE.json' => file_get_contents(codecept_data_dir('valid/manifest/de_DE.json')),
                    ],
                ],
            ],
        ];

        $this->getAcpHelper()->mockDirectoryStructure($structure);
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
}
