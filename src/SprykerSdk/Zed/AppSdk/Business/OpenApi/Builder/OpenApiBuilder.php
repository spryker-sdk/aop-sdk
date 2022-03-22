<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AppSdk\Business\OpenApi\Builder;

use Generated\Shared\Transfer\OpenApiRequestTransfer;
use Generated\Shared\Transfer\OpenApiResponseTransfer;
use Symfony\Component\Yaml\Yaml;

class OpenApiBuilder implements OpenApiBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\OpenApiRequestTransfer $OpenApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OpenApiResponseTransfer
     */
    public function addOpenApi(OpenApiRequestTransfer $OpenApiRequestTransfer): OpenApiResponseTransfer
    {
        $OpenApiResponseTransfer = new OpenApiResponseTransfer();

        $OpenApi = [
            'openapi' => '3.0.0',
            'info' => [
                'title' => $OpenApiRequestTransfer->getOpenApiOrFail()->getTitleOrFail(),
                'version' => $OpenApiRequestTransfer->getOpenApiOrFail()->getVersionOrFail(),
            ],
        ];

        $targetFilePath = $OpenApiRequestTransfer->getTargetFileOrFail();

        $this->writeToFile($targetFilePath, $OpenApi);

        return $OpenApiResponseTransfer;
    }

    /**
     * @param string $targetFile
     * @param array $openApi
     *
     * @return void
     */
    protected function writeToFile(string $targetFile, array $openApi): void
    {
        $openApiSchemaYaml = Yaml::dump($openApi, 100);

        $dirname = dirname($targetFile);

        if (!is_dir($dirname)) {
            mkdir($dirname, 0770, true);
        }

        file_put_contents($targetFile, $openApiSchemaYaml);
    }
}
