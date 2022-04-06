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
use OpenAPI\Schema\V3\Paths;
use OpenAPI\Schema\V3\PathItem;
use OpenAPI\Schema\V3\Operation;
use OpenAPI\Schema\V3\Parameter;
use OpenAPI\Schema\V3\Response;
use OpenAPI\Schema\V3\Schema;
use SprykerSdk\Zed\AppSdk\AppSdkConfig;
use Symfony\Component\Process\Process;

class OpenApiCodeBuilder implements OpenApiCodeBuilderInterface
{
    /**
     * @var \SprykerSdk\Zed\AppSdk\AppSdkConfig
     */
    protected AppSdkConfig $config;

    /**
     * @var array
     */
    protected $openApi;

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

        $openApiResponseTransfer = $this->buildCodeForPublishMessagesChannels($openApiResponseTransfer, $organization);

        if ($openApiResponseTransfer->getMessages()->count() === 0) {
            $messageTransfer = new MessageTransfer();
            $messageTransfer->setMessage('Something went wrong. Either not channels have been found or the channels do not have messages defined.');
            $openApiResponseTransfer->addError($messageTransfer);
        }

        return $openApiResponseTransfer;
    }

    /**
     * @param string $openApiFilePath
     */
    public function load(string $openApiFilePath)
    {
        $this->openApi = Parser::parse($openApiFilePath)->getAllValidFields();
    }

    /**
     * @param array $openApi
     * @param \Generated\Shared\Transfer\OpenApiResponseTransfer $openApiResponseTransfer
     * @param string $projectNamespace
     *
     * @return \Generated\Shared\Transfer\OpenApiResponseTransfer
     */
    protected function buildCodeForPublishMessagesChannels(
        OpenApiResponseTransfer $openApiResponseTransfer,
        string $projectNamespace
    ): OpenApiResponseTransfer {
        
        $commandLines = [];

        foreach($this->getOpenApiPathItems($this->getOpenApiPaths()) as $path => $pathItem){
            foreach ($this->getOperation($pathItem) as $method => $operation) {
                foreach ($this->getOperationParameters($operation) as $key => $parameter) {
                    $this->getParameterSchema($parameter);
                }

                foreach ($this->getOperationResponses($operation) as $response) {
                    dd($response);
                    $this->getParameterSchema($response);
                }
            }
        }

        dd($this->openApi);

        // foreach ($this->getSchemas($openApi) as $className => $schema) {
        //     $transferPropertiesToAdd = [];

        //     foreach ($schema->properties->getPatternedFields() as $fields) {
        //         $transferPropertiesToAdd[] = $fields;
        //     }

        //     $commandLines[] = [
        //         'vendor/bin/spryk-run',
        //         'AddSharedTransferProperty',
        //         '--mode', $this->sprykMode,
        //         '--organization', $projectNamespace,
        //         '--module', $className,
        //         '--name', $className,
        //         '--propertyName', implode(',', $transferPropertiesToAdd),
        //         '-n',
        //         '-v',
        //     ];
        //     $messageTransfer = new MessageTransfer();
        //     $messageTransfer->setMessage(sprintf('Added transfer property for "%s" to the module "%s".', $className, $className));
        //     $openApiResponseTransfer->addMessage($messageTransfer);
        // }

        // dd($commandLines);

        // $this->runCommandLines($commandLines);
    
        return $openApiResponseTransfer;
    }

    /**
     * @return string
     */
    protected function getOpenApiVersion(): string{
        return $this->openApip['openapi'];
    }

    /**
     * @return object
     */
    protected function getOpenApiInfo(): array{
        return $this->openApi['info'];
    }

    /**
     * @return array
     */
    protected function getOpenApiServers(): array{
        return $this->openApi['servers'];
    }

    /**
     * @return \OpenAPI\Schema\V3\Paths
     */
    protected function getOpenApiPaths(): Paths{
        return $this->openApi['paths'];
    }

    /**
     * @param \OpenAPI\Schema\V3\Paths
     * 
     * @return itrable \OpenAPI\Schema\V3\PathItem
     */
    protected function getOpenApiPathItems(Paths $paths): iterable{
        return $paths->getPatternedFields();
    }

    /**
     * @return array
     */
    protected function getOpenApiConponents(): array{
        return $this->openApi['components'];
    }

    /**
     * @param \OpenAPI\Schema\V3\PathIten
     * 
     * @return itrable \OpenAPI\Schema\V3\Operation
     */
    protected function getOperation(PathItem $pathItem): iterable{
        return $pathItem->getAllValidFields();
    }

    /**
     * @param \OpenAPI\Schema\V3\Operation
     * 
     * @return itrable \OpenAPI\Schema\V3\Parameter
     */
    protected function getOperationParameters(Operation $operation): iterable{
        return $operation->parameters;
    }

    /**
     * @param \OpenAPI\Schema\V3\Parameter
     * 
     * @return \OpenAPI\Schema\V3\Schema
     */
    protected function getParameterSchema(Parameter $parameter): Schema{
        return $parameter->schema;
    }
    
    /**
     * @param \OpenAPI\Schema\V3\Operation
     * 
     * @return itrable \OpenAPI\Schema\V3\Response
     */
    protected function getOperationResponses(Operation $operation): iterable{
        return $operation->responses;
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
