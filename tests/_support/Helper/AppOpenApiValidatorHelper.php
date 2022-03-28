<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Helper;

use Codeception\Module;
use org\bovigo\vfs\vfsStream;

class AppOpenApiValidatorHelper extends Module
{
    /**
     * @return void
     */
    public function haveValidOpenApiFile(): void
    {
        $files = [
            'openapi.yml' => file_get_contents(codecept_data_dir('api/openapi/valid/valid_openapi.yml')),
        ];

        $this->prepareOpenApiSchema($files);
    }

    /**
     * @param array $files
     *
     * @return void
     */
    protected function prepareOpenApiSchema(array $files): void
    {
        $structure = [
            'config' => [
                'api' => [
                    'openapi' => $files,
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
