<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Helper;

use Codeception\Module;

class AppConfigurationValidatorHelper extends Module
{
    use AppSdkHelperTrait;

    /**
     * @return void
     */
    public function haveValidConfiguration(): void
    {
        $files = [
            'configuration.json' => file_get_contents(codecept_data_dir('valid/configuration/configuration.json')),
        ];

        $this->prepareConfiguration($files);
    }

    /**
     * @return void
     */
    public function haveInvalidConfigurationFile(): void
    {
        $files = [
            'configuration.json' => file_get_contents(codecept_data_dir('invalid/configuration/configuration.json')),
        ];

        $this->prepareConfiguration($files);
    }

    /**
     * @param array $files
     *
     * @return void
     */
    protected function prepareConfiguration(array $files): void
    {
        $structure = [
            'config' => [
                'app' => [
                    'configuration' => $files,
                ],
            ],
        ];

        $this->getAppSdkHelper()->mockDirectoryStructure($structure);
    }
}
