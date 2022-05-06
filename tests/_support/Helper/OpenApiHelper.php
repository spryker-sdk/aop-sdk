<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Helper;

use Codeception\Module;
use Codeception\Stub;
use Codeception\Stub\Expected;
use Doctrine\Inflector\InflectorFactory;
use Generated\Shared\Transfer\OpenApiRequestTransfer;
use Generated\Shared\Transfer\OpenApiTransfer;
use SprykerSdk\Zed\AopSdk\AopSdkConfig;
use SprykerSdk\Zed\AopSdk\Business\AopSdkBusinessFactory;
use SprykerSdk\Zed\AopSdk\Business\AopSdkFacade;
use SprykerSdk\Zed\AopSdk\Business\OpenApi\Builder\OpenApiCodeBuilder;
use SprykerSdk\Zed\AopSdk\Communication\Console\BuildCodeFromOpenApiConsole;

class OpenApiHelper extends Module
{
    use AopSdkHelperTrait;

    /**
     * @return \Generated\Shared\Transfer\OpenApiRequestTransfer
     */
    public function haveOpenApiAddRequest(): OpenApiRequestTransfer
    {
        $config = $this->getAopSdkHelper()->getConfig();

        $openApiTransfer = new OpenApiTransfer();
        $openApiTransfer
            ->setTitle('Test title')
            ->setVersion('0.1.0');

        $openApiRequestTransfer = new OpenApiRequestTransfer();
        $openApiRequestTransfer
            ->setTargetFile($config->getDefaultOpenApiFile())
            ->setOpenApi($openApiTransfer);

        return $openApiRequestTransfer;
    }

    /**
     * return void
     *
     * @return void
     */
    public function haveOpenApiFile(): void
    {
        $this->prepareOpenApiFile(codecept_data_dir('api/openapi/valid/valid_openapi.yml'));
    }

    /**
     * @param string $pathToOpenApi
     *
     * @return void
     */
    protected function prepareOpenApiFile(string $pathToOpenApi): void
    {
        $filePath = sprintf('%s/config/api/openapi/openapi.yml', $this->getAopSdkHelper()->getRootPath());

        if (!is_dir(dirname($filePath))) {
            mkdir(dirname($filePath), 0770, true);
        }
        file_put_contents($filePath, file_get_contents($pathToOpenApi));
    }

    /**
     * @return \SprykerSdk\Zed\AopSdk\Communication\Console\BuildCodeFromOpenApiConsole
     */
    public function getOpenApiBuilderConsoleMock(): BuildCodeFromOpenApiConsole
    {
        $openApiCodeBuilderStub = Stub::construct(
            OpenApiCodeBuilder::class,
            [
                new AopSdkConfig(),
                InflectorFactory::create()->build(),
            ],
            [
                'runProcess' => Expected::atLeastOnce(),
            ],
        );
        $factoryStub = Stub::make(AopSdkBusinessFactory::class, [
            'createOpenApiCodeBuilder' => $openApiCodeBuilderStub,
        ]);
        $facade = new AopSdkFacade();
        $facade->setFactory($factoryStub);

        $buildFromOpenApiConsole = new BuildCodeFromOpenApiConsole();
        $buildFromOpenApiConsole->setFacade($facade);

        return $buildFromOpenApiConsole;
    }
}
