<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AopSdk\Business\OpenApi\Builder;

use cebe\openapi\Reader;
use cebe\openapi\spec\OpenApi;
use cebe\openapi\spec\Schema;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\OpenApiRequestTransfer;
use Generated\Shared\Transfer\OpenApiResponseTransfer;
use SprykerSdk\Zed\AopSdk\AopSdkConfig;
use Symfony\Component\Process\Process;
use function Symfony\Component\String\u;

class OpenApiCodeBuilder implements OpenApiCodeBuilderInterface
{
    /**
     * @var \SprykerSdk\Zed\AopSdk\AopSdkConfig
     */
    protected AopSdkConfig $config;

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
        $openApi = $this->load($openApiRequestTransfer->getTargetFileOrFail());
        
        $organization = $openApiRequestTransfer->getOrganizationOrFail();

        if ($organization === 'Spryker') {
            $this->sprykMode = 'core';
        }

        $openApiResponseTransfer = $this->buildCodeForOpenApi($openApi, $openApiResponseTransfer, $organization);

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
        return Reader::readFromYamlFile(realpath($openApiFilePath));
    }

    /**
     * @param \cebe\openapi\spec\OpenApi $openApi
     * @param \Generated\Shared\Transfer\OpenApiResponseTransfer|string $openApiResponseTransfer
     * @param string $projectNamespace
     *
     * @return \Generated\Shared\Transfer\OpenApiResponseTransfer
     */
    protected function buildCodeForOpenApi(
        OpenApi $openApi,
        OpenApiResponseTransfer $openApiResponseTransfer,
        string $projectNamespace
    ): OpenApiResponseTransfer {
        $parseData = [];

        /** @var \cebe\openapi\spec\PathItem $pathItem */
        foreach ($openApi->paths->getPaths() as $path => $pathItem) {
            $resources = explode("/", trim($path,"/"));
            $moduleName = ucwords(current($resources));
            $controllerName = "AppsResource";

            $lastResource = end($resources);

            if(count($resources) > 1 && is_string($lastResource) && strpos($lastResource, "{") === false){
                $controllerName = u($lastResource)->camel()->title()->toString();
            }

            /** @var \cebe\openapi\spec\Operation $operation */
            foreach ($pathItem->getOperations() as $method => $operation) {

                $parseData[$path][$method]["moduleName"] = "{$moduleName}Api";
                $parseData[$path][$method]["controllerName"] = "{$controllerName}Controller";

                /** @var \cebe\openapi\spec\Parameter|\cebe\openapi\spec\Reference $parameter */
                foreach ($operation->parameters as $parameterKey => $parameter) {
                    $parseData[$path][$method]['parameters'][$parameterKey] = json_decode(json_encode($parameter->getSerializableData()), true);
                    $referencePath = $parameter->getDocumentPosition()->getPath();
                    if (in_array('components', $referencePath)) {
                        $parseData[$path][$method]['parameters'][$parameterKey]['reference'] = end($referencePath);
                    }
                }

                if ($operation->requestBody) {
                    /** @var \cebe\openapi\spec\RequestBody $mediaType */
                    foreach ($operation->requestBody->content as $contentType => $mediaType) {
                        $referencePath = $mediaType->schema->getDocumentPosition()->getPath();
                        $parseData[$path][$method]['requestBody'] = $this->parseParameters($mediaType->schema, end($referencePath));
                    }
                }

                /** @var \cebe\openapi\spec\Response|\cebe\openapi\spec\Reference $content */
                foreach ($operation->responses as $status => $content) {
                    if ($content->content) {
                        foreach ($content->content as $contentType => $response) {
                            $referencePath = $response->schema->getDocumentPosition()->getPath();
                            $parseData[$path][$method]['responses'][$status] = $this->parseParameters($response->schema, end($referencePath));
                        }
                    } else {
                        $parseData[$path][$method]['responses'][$status] = $content->description;
                    }
                }
            }
        }

        $this->generateCommands($parseData, $projectNamespace);

        dd($parseData);

        return $openApiResponseTransfer;
    }

    /**
     * @param \cebe\openapi\spec\Schema $schema
     * @param string $className
     *
     * @return array
     */
    protected function parseParameters(Schema $schema, string $className): array
    {
        $response = [];

        foreach ($schema->properties as $key => $schemaObject) {
            if ($schemaObject->properties) {
                return $this->parseParameters($schemaObject, $className);
            } else {
                $response[$className][] = "{$key}:{$schemaObject->type}";
            }
        }

        return $response;
    }

    /**
     * @param array $parseData
     * @param string $projectNamespace
     *
     * @return void
     */
    protected function generateCommands(array $parseData, string $projectNamespace): void
    {
        $commandLines = [];

        foreach ($parseData as $uri => $operations) {
            foreach ($operations as $method => $data) {
                if(isset($data["requestBody"])){
                    foreach ($data["requestBody"]  as $command => $parameters) {
                        $commandLines[$command] = [
                            'vendor/bin/spryk-run',
                            'AddSharedTransferProperty',
                            '--mode', $this->sprykMode,
                            '--organization', $projectNamespace,
                            '--module', $data['moduleName'],
                            '--name', "{$command}Transfer",
                            '--propertyName', implode(',', $parameters),
                            '-n',
                            '-v',
                        ];
                    }
                }
                
                if(isset($data["responses"])){
                    foreach ($data["responses"] as $response) {
                        if(is_array($response)){
                            foreach ($response as $command => $parameters) {
                                $commandLines[$command] = [
                                    'vendor/bin/spryk-run',
                                    'AddSharedTransferProperty',
                                    '--mode', $this->sprykMode,
                                    '--organization', $projectNamespace,
                                    '--module', $data['moduleName'],
                                    '--name', "{$command}Transfer",
                                    '--propertyName', implode(',', $parameters),
                                    '-n',
                                    '-v',
                                ];
                            }
                        }
                    }
                }
            }
        }

        dd(array_values($commandLines));

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
