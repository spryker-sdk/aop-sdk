<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Helper;

use Codeception\Module;
use org\bovigo\vfs\vfsStream;

class AppAsyncApiValidatorHelper extends Module
{
    /**
     * @return void
     */
    public function haveValidAsyncApiFile(): void
    {
        $files = [
            'asyncapi.yml' => file_get_contents(codecept_data_dir('api/asyncapi/builder/asyncapi.yml')),
        ];

        $this->prepareAsyncApiSchema($files);
    }

    /**
     * @return void
     */
    public function haveInvalidAsyncApiFile(): void
    {
        $files = [
            'asyncapi.yml' => file_get_contents(codecept_data_dir('api/asyncapi/valid/invalid_base_asyncapi.schema.yml')),
        ];

        $this->prepareAsyncApiSchema($files);
    }

    /**
     * @return void
     */
    public function haveAsyncApiFileWithMissingRequiredFields(): void
    {
        $files = [
            'asyncapi.yml' => file_get_contents(codecept_data_dir('api/asyncapi/valid/base_asyncapi.schema.yml')),
        ];

        $this->prepareAsyncApiSchema($files);
    }

    /**
     * @return void
     */
    public function haveAsyncApiFileHaveDuplicateMessage(): void
    {
        $files = [
            'asyncapi.yml' => file_get_contents(codecept_data_dir('api/asyncapi/validation/asyncapi-duplicated-message-names.yml')),
        ];

        $this->prepareAsyncApiSchema($files);
    }

    /**
     * @param array $files
     *
     * @return void
     */
    protected function prepareAsyncApiSchema(array $files): void
    {
        $structure = [
            'config' => [
                'api' => [
                    'asyncapi' => $files,
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
