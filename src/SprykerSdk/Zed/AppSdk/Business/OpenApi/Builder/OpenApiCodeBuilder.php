<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AppSdk\Business\OpenApi\Builder;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\OpenApiRequestTransfer;
use Generated\Shared\Transfer\OpenApiResponseTransfer;
use OpenAPI\Parser;
use SprykerSdk\Zed\AppSdk\AppSdkConfig;
use Symfony\Component\Process\Process;

class OpenApiCodeBuilder implements OpenApiCodeBuilderInterface
{
    /**
     * @var \SprykerSdk\Zed\AppSdk\AppSdkConfig
     */
    protected AppSdkConfig $config;

    /**
     * @var string
     */
    protected string $sprykMode = 'project';

    /**
     * @param \SprykerSdk\Zed\AppSdk\AppSdkConfig $config
     */
    public function __construct(AppSdkConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\OpenApiRequestTransfer $openApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OpenApiResponseTransfer
     */
    public function build(OpenApiRequestTransfer $openApiRequestTransfer): OpenApiResponseTransfer
    {
        $openApiResponseTransfer = new OpenApiResponseTransfer();
        $this->load($openApiRequestTransfer->getTargetFileOrFail());

        $organization = $openApiRequestTransfer->getOrganizationOrFail();

        if ($organization === 'Spryker') {
            $this->sprykMode = 'core';
        }

        // $openApiResponseTransfer = $this->buildCodeForPublishMessagesChannels($openApi, $openApiResponseTransfer, $organization);

        if ($openApiResponseTransfer->getMessages()->count() === 0) {
            $messageTransfer = new MessageTransfer();
            $messageTransfer->setMessage('Something went wrong. Either not channels have been found or the channels do not have messages defined.');
            $openApiResponseTransfer->addError($messageTransfer);
        }

        return $openApiResponseTransfer;
    }

    /**
     * @param string $openApiFilePath
     *
     * @return void
     */
    public function load(string $openApiFilePath): void
    {
        $OpenAPI = Parser::parse($openApiFilePath);

        dd($OpenAPI);
        // return $openApiResponseTransfer;
    }

    /**
     * @codeCoverageIgnore
     *
     * @param array<array> $commandLines
     *
     * @return void
     */
    protected function runCommandLines(array $commandLines): void
    {
        foreach ($commandLines as $commandLine) {
            $process = new Process($commandLine, $this->config->getProjectRootPath());
            $process->run(function ($a, $buffer) {
                echo $buffer;
                // For debugging purposes, set a breakpoint here to see issues.
            });
        }
    }
}
