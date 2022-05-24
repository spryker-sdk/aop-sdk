<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Helper;

use Codeception\Module;

class AppTranslationValidatorHelper extends Module
{
    use AcpHelperTrait;

    /**
     * @param string $translationFile
     *
     * @return array<array<array<\array>>>
     */
    protected function getValidBaseStructure(string $translationFile): array
    {
        return [
            'config' => [
                'app' => [
                    'translation.json' => file_get_contents(codecept_data_dir($translationFile)),
                    'configuration.json' => file_get_contents(codecept_data_dir('valid/configuration/translation.json')),
                    'manifest' => [
                        'de_DE.json' => file_get_contents(codecept_data_dir('valid/manifest/de_DE.json')),
                        'en_US.json' => file_get_contents(codecept_data_dir('valid/manifest/en_US.json')),
                    ],
                ],
            ],
        ];
    }

    /**
     * @return void
     */
    public function haveValidTranslationWithManifestAndConfiguration(): void
    {
        $structure = $this->getValidBaseStructure('valid/translation/translation.json');

        $this->prepareTranslation($structure);
    }

    /**
     * @return void
     */
    public function haveValidTranslationWithoutManifest(): void
    {
        $structure = [
            'config' => [
                'app' => [
                    'translation.json' => file_get_contents(codecept_data_dir('valid/translation/translation.json')),
                    'configuration.json' => file_get_contents(codecept_data_dir('valid/configuration/translation.json')),
                ],
            ],
        ];

        $this->prepareTranslation($structure);
    }

    /**
     * @return void
     */
    public function haveValidTranslationWithManifestAndWithoutConfiguration(): void
    {
        $structure = [
            'config' => [
                'app' => [
                    'translation.json' => file_get_contents(codecept_data_dir('valid/translation/translation.json')),
                    'manifest' => [
                        'de_DE.json' => file_get_contents(codecept_data_dir('valid/manifest/de_DE.json')),
                        'en_US.json' => file_get_contents(codecept_data_dir('valid/manifest/en_US.json')),
                    ],
                ],
            ],
        ];

        $this->prepareTranslation($structure);
    }

    /**
     * @return void
     */
    public function haveMissingTranslationValueTranslationFile(): void
    {
        $structure = $this->getValidBaseStructure('invalid/translation/missingTranslationValue.json');

        $this->prepareTranslation($structure);
    }

    /**
     * @return void
     */
    public function haveInvalidTranslationFile(): void
    {
        $structure = $this->getValidBaseStructure('invalid/translation/invalid.json');

        $this->prepareTranslation($structure);
    }

    /**
     * @param array $structure
     *
     * @return void
     */
    protected function prepareTranslation(array $structure): void
    {
        $this->getAcpHelper()->mockDirectoryStructure($structure);
    }
}
