<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Zed\AopSdk\Business\OpenApi\Builder;

use cebe\openapi\Reader;
use cebe\openapi\spec\OpenApi;
use cebe\openapi\spec\Operation;
use cebe\openapi\spec\PathItem;
use cebe\openapi\spec\Reference;
use cebe\openapi\spec\Schema;
use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\InflectorFactory;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\OpenApiRequestTransfer;
use Generated\Shared\Transfer\OpenApiResponseTransfer;
use SprykerSdk\Zed\AopSdk\AopSdkConfig;
use Symfony\Component\Process\Process;

class OpenApiCodeBuilder implements OpenApiCodeBuilderInterface
{
    /**
     * @var string
     */
    protected const APPSRESOURCE = 'AppsResource';

    /**
     * @var string
     */
    protected const SPRYKER = 'Spryker';

    /**
     * @var string
     */
    protected const CORE = 'core';

    /**
     * @var string
     */
    protected const ATTRIBUTE_SUFFIX = 'Attributes';

    /**
     * @var \SprykerSdk\Zed\AopSdk\AopSdkConfig
     */
    protected AopSdkConfig $config;

    /**
     * @var string
     */
    protected string $sprykMode = 'project';

    /**
     * @var string
     */
    protected string $moduleName = '';

    /**
     * @var string
     */
    protected string $organization = '';

    /**
     * @var array
     */
    protected array $parsedData = [];

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

        //Set default value for organization
        $this->organization = $openApiRequestTransfer->getOrganizationOrFail();
        if ($this->organization === static::SPRYKER) {
            $this->sprykMode = static::CORE; //Set sprykMode based on organization
        }

        $openApiResponseTransfer = $this->buildCodeForOpenApi($openApi, $openApiResponseTransfer);

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
     * @return \cebe\openapi\spec\OpenApi
     */
    public function load(string $openApiFilePath): OpenApi
    {
        return Reader::readFromYamlFile((string)realpath($openApiFilePath));
    }

    /**
     * @param string $resourceUri
     *
     * @return string
     */
    protected function getModuleName(string $resourceUri): string
    {
        $resources = explode('/', trim($resourceUri, '/'));

        return ucwords(current($resources)) . 'Api';
    }

    /**
     * @param string $resourceUri
     *
     * @return string
     */
    protected function getController(string $resourceUri): string
    {
        $controllerName = static::APPSRESOURCE;

        $resources = explode('/', trim($resourceUri, '/'));

        $lastResource = end($resources);

        if (count($resources) > 1 && is_string($lastResource) && strpos($lastResource, '{') === false) {
            $controllerName = $this->getInflectorFactory()->classify($lastResource);
        }

        return "{$controllerName}Controller";
    }

    /**
     * @return \Doctrine\Inflector\Inflector
     */
    protected function getInflectorFactory(): Inflector
    {
        return InflectorFactory::create()->build();
    }

    /**
     * @param \cebe\openapi\spec\OpenApi $openApi
     * @param \Generated\Shared\Transfer\OpenApiResponseTransfer $openApiResponseTransfer
     *
     * @return \Generated\Shared\Transfer\OpenApiResponseTransfer
     */
    protected function buildCodeForOpenApi(
        OpenApi $openApi,
        OpenApiResponseTransfer $openApiResponseTransfer
    ): OpenApiResponseTransfer {
        /** @var \cebe\openapi\spec\PathItem $pathItem */
        foreach ($openApi->paths->getPaths() as $path => $pathItem) {
            //Parse data api path wise
            $this->parseApiPathItem($path, $pathItem);
        }

        //Generating Commands using parsed data
        $this->generateCommands();

        return $openApiResponseTransfer;
    }

    /**
     * @param string $path
     * @param \cebe\openapi\spec\PathItem $pathItem
     *
     * @return void
     */
    protected function parseApiPathItem(string $path, PathItem $pathItem): void
    {
        /** @var \cebe\openapi\spec\Operation $operation */
        foreach ($pathItem->getOperations() as $method => $operation) {
            //Set Module Name
            $this->parsedData[$path][$method]['moduleName'] = $this->getModuleName($path);
            //Set Controller Name
            $this->parsedData[$path][$method]['controllerName'] = $this->getController($path);

            if ($operation->requestBody) {
                //Set Request Body by parsing
                $this->parseRequestBody($operation, $path, $method);
            }

            //Set Response Body by parsing
            $this->parseResponseBody($operation, $path, $method);
        }
    }

    /**
     * @param \cebe\openapi\spec\Operation $operation
     * @param string $path
     * @param string $method
     *
     * @return void
     */
    protected function parseRequestBody(Operation $operation, string $path, string $method): void
    {
        /** @var \cebe\openapi\spec\RequestBody $mediaType */
        foreach ($this->getRequestBodyContent($operation) as $mediaType) {
            // if (!isset($mediaType->schema)) {
            //     return;
            // }
            
            if ($mediaType->schema instanceof Schema) {
                $this->parsedData[$path][$method]['requestBody'][$this->getPathUsingSchema($mediaType->schema)] = $this->requestBodyParserBySchema($mediaType->schema);
            }
            if ($mediaType->schema instanceof Reference) {
                $this->parsedData[$path][$method]['requestBody'][$this->getPathUsingReference($mediaType->schema)] = $this->requestBodyParserByReference($mediaType->schema);
            }
        }
    }

    /**
     * @param \cebe\openapi\spec\Operation $operation
     *
     * @return iterable
     */
    protected function getRequestBodyContent(Operation $operation): iterable
    {
        return isset($operation->requestBody) && isset($operation->requestBody->content) ? $operation->requestBody->content : [];
    }

    /**
     * @param \cebe\openapi\spec\Schema $schema
     *
     * @return iterable
     */
    protected function getPropertiesBySchema(Schema $schema): iterable
    {
        if (isset($schema->properties)) {
            return $schema->properties;
        }

        return [];
    }

    /**
     * @param \cebe\openapi\spec\Reference $schema
     *
     * @return iterable
     */
    protected function getPropertiesByReference(Reference $schema): iterable
    {
        if (isset($schema->properties)) {
            return $schema->properties;
        }

        return [];
    }

    /**
     * @param \cebe\openapi\spec\Schema $schema
     *
     * @return array
     */
    protected function requestBodyParserBySchema(Schema $schema): array
    {
        
        $response = [];
        foreach ($schema->properties as $key => $schemaObject) {
            if ($schemaObject instanceof Schema && isset(($schemaObject->properties)) && !empty($schemaObject->properties)) {
                return $this->requestBodyParserBySchema($schemaObject);
            }
            if ($schemaObject instanceof Reference && isset(($schemaObject->properties)) && !empty($schemaObject->properties)) {
                return $this->requestBodyParserByReference($schemaObject);
            }
        }
     

        foreach ($schema->properties as $key => $schemaObject) {
            
            if (!isset($schemaObject->type) && !isset($schemaObject->items)) {
                continue;
            }
            if ($schemaObject->type !== 'array') {
                $response[$key] = $schemaObject->type;

                continue;
            }

            if (isset($schemaObject->items->properties) && isset($schemaObject->items->properties['type'])) {
                $response[$key] = 'array[]:' . $schemaObject->items->properties['type']->type;

                continue;
            }
            if (isset($schemaObject->items->type)) {
                $response[$key] = 'array[]:' . $schemaObject->items->type;

                continue;
            }
            $pathUsingSchemaObject = '';
            if ($schemaObject->items instanceof Schema) {
                $pathUsingSchemaObject = $this->getPathUsingSchema($schemaObject->items);
            }
            if ($schemaObject->items instanceof Reference) {
                $pathUsingSchemaObject = $this->getPathUsingReference($schemaObject->items);
            }
            $response[$this->getInflectorFactory()->camelize($this->getPathUsingSchema($schema))] = $pathUsingSchemaObject . '[]:' . $this->getInflectorFactory()->camelize($pathUsingSchemaObject);
        }

        return $response;
    }

    /**
     * @param \cebe\openapi\spec\Reference $schema
     *
     * @return array
     */
    protected function requestBodyParserByReference(Reference $schema): array
    {
    
        $response = [];
        foreach ($schema->properties as $key => $schemaObject) {
            if ($schemaObject instanceof Schema && isset(($schemaObject->properties)) && !empty($schemaObject->properties)) {
                return $this->requestBodyParserBySchema($schemaObject);
            }
            if ($schemaObject instanceof Reference && isset(($schemaObject->properties)) && !empty($schemaObject->properties)) {
                return $this->requestBodyParserByReference($schemaObject);
            }
        }

        foreach ($this->getPropertiesByReference($schema) as $key => $schemaObject) {
            if (!isset($schemaObject->type) && !isset($schemaObject->items)) {
                continue;
            }
            if ($schemaObject->type !== 'array') {
                $response[$key] = $schemaObject->type;

                continue;
            }
            if (isset($schemaObject->items->type)) {
                $response[$key] = 'array[]:' . $schemaObject->items->type;

                continue;
            }
            if (isset($schemaObject->items->properties) && isset($schemaObject->items->properties['type'])) {
                $response[$key] = 'array[]:' . $schemaObject->items->properties['type']->type;

                continue;
            }
            $response[$this->getInflectorFactory()->camelize($this->getPathUsingReference($schema))] = $this->getPathUsingSchema($schemaObject->items) . '[]:' . $this->getInflectorFactory()->camelize($this->getPathUsingSchema($schemaObject->items));
        }

        return $response;
    }

    /**
     * @param \cebe\openapi\spec\Operation $operation
     * @param string $path
     * @param string $method
     *
     * @return void
     */
    protected function parseResponseBody(Operation $operation, string $path, string $method): void
    {
        /** @var \cebe\openapi\spec\Response|\cebe\openapi\spec\Reference $content */
        foreach ($this->getResponses($operation) as $status => $content) {
            if (isset($content->content) && !empty($content->content)) {
                foreach ($content->content as $response) {
                    if (!isset($response->schema)) {
                        continue;
                    }
                    if ($response->schema instanceof Schema) {
                        $this->parsedData[$path][$method]['responses'][$this->getPathUsingSchema($response->schema)] = $this->responseBodyParserBySchema($response->schema);
                    }
                    if ($response->schema instanceof Reference) {
                        $this->parsedData[$path][$method]['responses'][$this->getPathUsingReference($response->schema)] = $this->responseBodyParserByReference($response->schema);
                    }
                }
            }
        }
    }

    /**
     * @param \cebe\openapi\spec\Operation $operation
     *
     * @return array
     */
    protected function getResponses(Operation $operation): iterable
    {
        return $operation->responses ?? [];
    }

    /**
     * @param \cebe\openapi\spec\Schema $schema
     *
     * @return array
     */
    protected function responseBodyParserBySchema(Schema $schema): array
    {
        $response = [];

        foreach ($this->getPropertiesBySchema($schema) as $key => $schemaObject) {
            if ($schemaObject instanceof Schema && isset($schemaObject->properties) && !empty($schemaObject->properties)) {
                return $this->responseBodyParserBySchema($schemaObject);
            }
            if ($schemaObject instanceof Reference && isset($schemaObject->properties) && !empty($schemaObject->properties)) {
                return $this->responseBodyParserByReference($schemaObject);
            }

            if (!isset($schemaObject->type) && !isset($schemaObject->items)) {
                continue;
            }

            if ($schemaObject->type !== 'array') {
                $response[$key] = $schemaObject->type;

                continue;
            }
            if (isset($schemaObject->items->type)) {
                $response[$key] = 'array[]:' . $schemaObject->items->type;

                continue;
            }
            if (isset($schemaObject->items->properties) && isset($schemaObject->items->properties['type'])) {
                $response[$key] = 'array[]:' . $schemaObject->items->properties['type']->type;

                continue;
            }
            $pathUsingSchemaObject = '';
            if ($schemaObject->items instanceof Schema) {
                $pathUsingSchemaObject = $this->getPathUsingSchema($schemaObject->items);
            }
            if ($schemaObject->items instanceof Reference) {
                $pathUsingSchemaObject = $this->getPathUsingReference($schemaObject->items);
            }

            $response[$this->getInflectorFactory()->camelize($this->getPathUsingSchema($schema))] = $pathUsingSchemaObject . '[]:' . $this->getInflectorFactory()->camelize($pathUsingSchemaObject);
        }

        return $response;
    }

    /**
     * @param \cebe\openapi\spec\Reference $schema
     *
     * @return array
     */
    protected function responseBodyParserByReference(Reference $schema): array
    {
        $response = [];

        foreach ($this->getPropertiesByReference($schema) as $key => $schemaObject) {
            if ($schemaObject instanceof Schema && isset($schemaObject->properties) && !empty($schemaObject->properties)) {
                return $this->responseBodyParserBySchema($schemaObject);
            }
            if ($schemaObject instanceof Reference && isset($schemaObject->properties) && !empty($schemaObject->properties)) {
                return $this->responseBodyParserByReference($schemaObject);
            }

            if (!isset($schemaObject->type) && !isset($schemaObject->items)) {
                continue;
            }

            if ($schemaObject->type !== 'array') {
                $response[$key] = $schemaObject->type;

                continue;
            }
            if (isset($schemaObject->items) && isset($schemaObject->items->type)) {
                $response[$key] = 'array[]:' . $schemaObject->items->type;

                continue;
            }
            if (isset($schemaObject->items) && isset($schemaObject->items->properties) && isset($schemaObject->items->properties['type'])) {
                $response[$key] = 'array[]:' . $schemaObject->items->properties['type']->type;

                continue;
            }
            $response[$this->getInflectorFactory()->camelize($this->getPathUsingReference($schema))] = $this->getPathUsingSchema($schemaObject->items) . '[]:' . $this->getInflectorFactory()->camelize($this->getPathUsingSchema($schemaObject->items));
        }

        return $response;
    }

    /**
     * @param \cebe\openapi\spec\Schema $schema
     *
     * @return string
     */
    protected function getPathUsingSchema(Schema $schema): string
    {
        if ($schema->getDocumentPosition()) {
            $referencePath = $schema->getDocumentPosition()->getPath();

            return end($referencePath);
        }

        return '';
    }

    /**
     * @param \cebe\openapi\spec\Reference $schema
     *
     * @return string
     */
    protected function getPathUsingReference(Reference $schema): string
    {
        if ($schema->getDocumentPosition()) {
            $referencePath = $schema->getDocumentPosition()->getPath();

            return end($referencePath);
        }

        return '';
    }

    /**
     * @param array $parameters
     *
     * @return array
     */
    protected function generateParameters(array $parameters): array
    {
        $response = [];
        foreach ($parameters as $key => $value) {
            $response[] = "{$key}:{$value}";
        }

        return $response;
    }

    /**
     * @return void
     */
    protected function generateCommands(): void
    {
        $commandLines = [];

        foreach ($this->parsedData as $uri => $operations) {
            foreach ($operations as $method => $data) {
                if (isset($data['requestBody'])) {
                    foreach ($data['requestBody'] as $command => $parameters) {
                        $commandLines[$command] = [
                            'vendor/bin/spryk-run',
                            'AddSharedTransferProperty',
                            '--mode', $this->sprykMode,
                            '--organization', $this->organization,
                            '--module', $data['moduleName'],
                            '--name', $command,
                            '--propertyName', implode(',', $this->generateParameters($parameters)),
                            '-n',
                            '-v',
                        ];
                    }
                }

                if (isset($data['responses'])) {
                    foreach ($data['responses'] as $command => $parameters) {
                        $commandLines[$command] = [
                            'vendor/bin/spryk-run',
                            'AddSharedTransferProperty',
                            '--mode', $this->sprykMode,
                            '--organization', $this->organization,
                            '--module', $data['moduleName'],
                            '--name', $command,
                            '--propertyName', implode(',', $this->generateParameters($parameters)),
                            '-n',
                            '-v',
                        ];
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