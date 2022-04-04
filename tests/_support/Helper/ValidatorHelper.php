<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\ValidateRequestTransfer;
use Generated\Shared\Transfer\ValidateResponseTransfer;

class ValidatorHelper extends Module
{
    use AppSdkHelperTrait;

    /**
     * @return void
     */
    public function haveValidConfigurations(): void
    {
        $structure = $this->getValidBaseStructure();

        $this->getAppSdkHelper()->mockDirectoryStructure($structure);
    }

    /**
     * @return array<array<array<\array>>>
     */
    protected function getValidBaseStructure(): array
    {
        return [
            'config' => [
                'app' => [
                    'translation' => [
                        'translation.json' => file_get_contents(codecept_data_dir('valid/translation/translation.json')),
                    ],
                    'manifest' => [
                        'de_DE.json' => file_get_contents(codecept_data_dir('valid/manifest/de_DE.json')),
                        'en_US.json' => file_get_contents(codecept_data_dir('valid/manifest/en_US.json')),
                    ],
                    'configuration' => [
                        'configuration.json' => file_get_contents(codecept_data_dir('valid/configuration/translation.json')),
                    ],
                ],
                'api' => [
                    'asyncapi' => [
                        'asyncapi.yml' => file_get_contents(codecept_data_dir('api/asyncapi/valid/base_asyncapi.schema.yml')),
                    ],
                ],
            ],
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\ValidateRequestTransfer
     */
    public function haveValidateRequest(): ValidateRequestTransfer
    {
        $config = $this->getAppSdkHelper()->getConfig();

        $validateRequest = new ValidateRequestTransfer();
        $validateRequest->setAsyncApiFile($config->getDefaultAsyncApiFile());
        $validateRequest->setManifestPath($config->getDefaultManifestPath());
        $validateRequest->setConfigurationFile($config->getDefaultConfigurationFile());
        $validateRequest->setTranslationFile($config->getDefaultTranslationFile());

        return $validateRequest;
    }

    /**
     * @param \Generated\Shared\Transfer\ValidateResponseTransfer $validateResponseTransfer
     *
     * @return array
     */
    public function getMessagesFromValidateResponseTransfer(ValidateResponseTransfer $validateResponseTransfer): array
    {
        $messages = [];

        foreach ($validateResponseTransfer->getErrors() as $messageTransfer) {
            $messages[] = $messageTransfer->getMessage();
        }

        return $messages;
    }
}
