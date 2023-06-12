<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Helper;

use Codeception\Module;

class ChannelNameValidatorHelper extends Module
{
    use AcpHelperTrait;

    /**
     * @return void
     */
    public function haveValidProjectConfiguration(): void
    {
        $files = [
            'config_default.php' => file_get_contents(codecept_data_dir('valid/projectConfiguration/configuration.php')),
        ];

        $this->prepareProjectConfiguration($files);
    }

    /**
     * @return void
     */
    public function haveInvalidProjectConfiguration(): void
    {
        $files = [
            'config_default.php' => file_get_contents(codecept_data_dir('invalid/projectConfiguration/configuration.php')),
        ];

        $this->prepareProjectConfiguration($files);
    }

    /**
     * @return void
     */
    public function haveEmptyProjectConfiguration(): void
    {
        $files = [
            'config_default.php' => file_get_contents(codecept_data_dir('valid/projectConfiguration/empty_configuration.php')),
        ];

        $this->prepareProjectConfiguration($files);
    }

    /**
     * @return void
     */
    public function haveMissingChannelProjectConfiguration(): void
    {
        $files = [
            'config_default.php' => file_get_contents(codecept_data_dir('invalid/projectConfiguration/configuration_with_missing_channel.php')),
        ];

        $this->prepareProjectConfiguration($files);
    }

    /**
     * @param array $files
     *
     * @return void
     */
    protected function prepareProjectConfiguration(array $files): void
    {
        $structure = [
            'config' => [
                'Shared' => $files,
            ],
        ];

        $this->getAcpHelper()->mockDirectoryStructure($structure);
    }
}
