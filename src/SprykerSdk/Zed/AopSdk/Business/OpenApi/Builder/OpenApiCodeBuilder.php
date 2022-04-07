<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AopSdk\Business\OpenApi\Builder;

use cebe\openapi\spec\OpenApi;
use cebe\openapi\spec\Reference;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\OpenApiRequestTransfer;
use Generated\Shared\Transfer\OpenApiResponseTransfer;
use cebe\openapi\Reader;
use cebe\openapi\spec\Paths;
use cebe\openapi\spec\PathItem;
use cebe\openapi\spec\Operation;
use cebe\openapi\spec\Parameter;
use cebe\openapi\spec\RequestBody;
use cebe\openapi\spec\Response;
use cebe\openapi\spec\Schema;
use SprykerSdk\Zed\AopSdk\AopSdkConfig;
use Symfony\Component\Process\Process;

class OpenApiCodeBuilder implements OpenApiCodeBuilderInterface
{
    /**
     * @var \SprykerSdk\Zed\AopSdk\AopSdkConfig
     */
    protected AopSdkConfig $config;

    /**
     * @var array
     */
    protected $openApi;

    /**
     * @var string
     */
    protected string $sprykMode = 'project';

    /**
     * @param \SprykerSdk\Zed\AopSdk\AopSdkConfig $config
     */
    public function __construct(AopSdkConfig $config)
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

        $openApiResponseTransfer = $this->buildCodeForOpenApi($openApiResponseTransfer, $organization);

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
        $this->openApi = Reader::readFromYamlFile(realpath($openApiFilePath));
    }

    /**
     * @param array $openApi
     * @param \Generated\Shared\Transfer\OpenApiResponseTransfer $openApiResponseTransfer
     * @param string $projectNamespace
     *
     * @return \Generated\Shared\Transfer\OpenApiResponseTransfer
     */
    protected function buildCodeForOpenApi(
        OpenApiResponseTransfer $openApiResponseTransfer,
        string $projectNamespace
    ): OpenApiResponseTransfer {

        $commandLines = [];

        $parseData = [];

        /** @var PathItem $pathItem */
        foreach($this->openApi->paths->getPaths() as $path => $pathItem){
            /** @var Operation $operation */
            foreach ($pathItem->getOperations() as $method => $operation) {
                /** @var Parameter|Reference $parameter */
                foreach ($operation->parameters as $parameterKey => $parameter) {
                    $parseData[$path][$method]["Parameters"][$parameterKey]["Params"] = json_decode(json_encode($parameter->getSerializableData()), true);
                    $parseData[$path][$method]["Parameters"][$parameterKey]["Reference"] = $parameter->getDocumentPosition()->getPath();
                }
                
                /** @var RequestBody $parameter */
                if($operation->requestBody){
                    foreach ($operation->requestBody->content as $contentType => $mediaType) {
                        $parseData[$path][$method]["RequestBody"]["ContentType"] = $contentType;
                        $parseData[$path][$method]["RequestBody"]["RequestData"] = $this->parseParameters($mediaType->schema);
                    }
                }

                /** @var Response|Reference $response */
                foreach ($operation->responses as $status => $response) {
                    $parseData[$path][$method]["Responses"][$status]["Response"] = json_decode(json_encode($response->getSerializableData()), true);
                    $parseData[$path][$method]["Responses"][$status]["Reference"] = $response->getDocumentPosition()->getPath();
                }
            }
        }

        dd($parseData);

        return $openApiResponseTransfer;
    }


    protected function parseParameters($schema){
        $response = [];
        
        foreach ($schema->properties as $schemaObject) {
            $response["Params"] = $this->parseParameters($schemaObject);
            $response["Properties"] = $schemaObject->getSerializableData();
            $response["Reference"] = $schema->getDocumentPosition()->getPath();
        }

        return $response;
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
