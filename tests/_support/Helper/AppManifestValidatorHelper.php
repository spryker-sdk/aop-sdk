<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Helper;

use Codeception\Module;
use InvalidArgumentException;

class AppManifestValidatorHelper extends Module
{
    use AcpHelperTrait;

    /**
     * @var array<string>
     */
    protected array $availableLocales = [
        'en_US',
        'de_DE',
    ];

    /**
     * @param string $locale
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public function haveValidManifestFile(string $locale = 'en_US'): void
    {
        if (!in_array($locale, $this->availableLocales)) {
            throw new InvalidArgumentException(sprintf('You can only select one of: %s', implode(', ', $this->availableLocales)));
        }

        $files = [
            sprintf('%s.json', $locale) => file_get_contents(codecept_data_dir(sprintf('valid/manifest/%s.json', $locale))),
        ];

        $this->prepareManifest($files);
    }

    /**
     * @param array<string> $locales
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public function haveValidManifestFiles(array $locales): void
    {
        $files = [];

        foreach ($locales as $locale) {
            if (!in_array($locale, $this->availableLocales)) {
                throw new InvalidArgumentException(sprintf('You can only select one of: %s', implode(', ', $this->availableLocales)));
            }

            $files[sprintf('%s.json', $locale)] = file_get_contents(codecept_data_dir(sprintf('valid/manifest/%s.json', $locale)));
        }

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
     * @return void
     */
    public function haveManifestFileWithInvalidBusinessModel(): void
    {
        $files = [
            'en_US.json' => file_get_contents(codecept_data_dir('invalid/manifest/InvalidBusinessModelsField.json')),
        ];

        $this->prepareManifest($files);
    }

    /**
     * @param string $manifestLocale
     *
     * @return void
     */
    public function haveRealManifestExampleFile($manifestLocale = 'en_US'): void
    {
        $files = [
            $manifestLocale . '.json.dist' => file_get_contents(codecept_absolute_path('config/app/manifest/' . $manifestLocale . '.json.dist')),
        ];

        $this->prepareManifest($files);
    }

    /**
     * @param string $manifestLocale
     *
     * @return void
     */
    public function haveEmptyManifestExampleFile($manifestLocale = 'en_US'): void
    {
        $files = [
            $manifestLocale . '.json.dist' => '',
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
        $this->getAcpHelper()->mockDirectoryStructure($structure);
    }

    /**
     * @return void
     */
    public function haveManifestFileWithInvalidDevelopedByRequiredField(): void
    {
        $files = [
            'en_US.json' => file_get_contents(codecept_data_dir('invalid/manifest/nullDevelopedByRequiredField.json')),
            'de_DE.json' => file_get_contents(codecept_data_dir('invalid/manifest/emptyDevelopedByRequiredField.json')),
        ];

        $this->prepareManifest($files);
    }
}
