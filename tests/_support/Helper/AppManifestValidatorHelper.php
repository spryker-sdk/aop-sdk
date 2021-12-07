<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Helper;

use Codeception\Module;
use org\bovigo\vfs\vfsStream;

class AppManifestValidatorHelper extends Module
{
    /**
     * @return void
     */
    public function haveValidManifestFile(): void
    {
        $files = [
            'en_US.json' => file_get_contents(codecept_data_dir('valid/manifest/en_US.json')),
        ];

        $this->prepareManifest($files);
    }

    /**
     * @return void
     */
    public function haveManifestFileWithMissingRequiredFields(): void
    {
        $files = [
            'en_US.json' => file_get_contents(codecept_data_dir('invalid/manifest/missingRequiredField.json')),
        ];

        $this->prepareManifest($files);
    }

    /**
     * @return void
     */
    public function haveInvalidManifestFile(): void
    {
        $files = [
            'en_US.json' => file_get_contents(codecept_data_dir('invalid/manifest/invalid.json')),
        ];

        $this->prepareManifest($files);
    }

    /**
     * @return void
     */
    public function haveManifestFileWithMissingRequiredFieldsInPageBlock(): void
    {
        $files = [
            'en_US.json' => file_get_contents(codecept_data_dir('invalid/manifest/missingRequiredPageBlockField.json')),
        ];

        $this->prepareManifest($files);
    }

    /**
     * @return void
     */
    public function haveManifestFileWithInvalidPageBlockType(): void
    {
        $files = [
            'en_US.json' => file_get_contents(codecept_data_dir('invalid/manifest/invalidTypeInPageBlockField.json')),
        ];

        $this->prepareManifest($files);
    }

    /**
     * @param array $files
     *
     * @return void
     */
    protected function prepareManifest(array $files): void
    {
        $structure = [
            'config' => [
                'app' => [
                    'manifest' => $files,
                ],
            ],
        ];
        $root = vfsStream::setup('root', null, $structure);

        $this->getValidatorHelper()->mockRoot($root->url());
    }

    /**
     * @return \SprykerSdkTest\Helper\ValidatorHelper
     */
    protected function getValidatorHelper(): ValidatorHelper
    {
        return $this->getModule('\\' . ValidatorHelper::class);
    }
}
