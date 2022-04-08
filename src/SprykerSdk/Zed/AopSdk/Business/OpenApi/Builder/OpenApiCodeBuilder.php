<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AopSdk\Business\OpenApi\Builder;

use cebe\openapi\Reader;
use cebe\openapi\spec\Schema;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\OpenApiRequestTransfer;
use Generated\Shared\Transfer\OpenApiResponseTransfer;
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
     *
     * @return void
     */
    public function load(string $openApiFilePath)
    {
        $this->openApi = Reader::readFromYamlFile(realpath($openApiFilePath));
    }

    /**
     * @param \Generated\Shared\Transfer\OpenApiResponseTransfer|string $openApiResponseTransfer
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

        /** @var \cebe\openapi\spec\PathItem $pathItem */
        foreach ($this->openApi->paths->getPaths() as $path => $pathItem) {
            /** @var \cebe\openapi\spec\Operation $operation */
            foreach ($pathItem->getOperations() as $method => $operation) {

                /** @var \cebe\openapi\spec\Parameter|\cebe\openapi\spec\Reference $parameter */
                foreach ($operation->parameters as $parameterKey => $parameter) {
                    $parseData[$path][$method]['Parameters'][$parameterKey] = json_decode(json_encode($parameter->getSerializableData()), true);
                    $referencePath = $parameter->getDocumentPosition()->getPath();
                    if (in_array('components', $referencePath)) {
                        $parseData[$path][$method]['Parameters'][$parameterKey]['Reference'] = end($referencePath);
                    }
                }

                if ($operation->requestBody) {
                    /** @var \cebe\openapi\spec\RequestBody $mediaType */
                    foreach ($operation->requestBody->content as $contentType => $mediaType) {
                        $parseData[$path][$method]['RequestBody'] = $this->parseParameters($mediaType->schema);
                    }
                }

                /** @var \cebe\openapi\spec\Response|\cebe\openapi\spec\Reference $content */
                foreach ($operation->responses as $status => $content) {
                    if ($content->content) {
                        foreach ($content->content as $contentType => $response) {
                            $parseData[$path][$method]['Responses'][$status] = $this->parseParameters($response->schema);
                        }
                    } else {
                        $parseData[$path][$method]['Responses'][$status] = $content->description;
                    }
                }
            }
        }

        dd($parseData);

        return $openApiResponseTransfer;
    }

    /**
     * @param \cebe\openapi\spec\Schema $schema
     *
     * @return array
     */
    protected function parseParameters(Schema $schema): array
    {
        $response = [];

        $referencePath = $schema->getDocumentPosition()->getPath();
        $reference = end($referencePath);

        foreach ($schema->properties as $key => $schemaObject) {
            if ($schemaObject->properties) {
                $response[$reference][$key] = $this->parseParameters($schemaObject);
            } else {
                $response[$reference][$key] = $schemaObject->type;
            }
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
