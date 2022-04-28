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
    protected const SPRYKER = 'Spryker';

    /**
     * @var string
     */
    protected const CORE = 'core';

    /**
     * @var \SprykerSdk\Zed\AopSdk\AopSdkConfig
     */
    protected AopSdkConfig $config;

    /**
     * @var \Generated\Shared\Transfer\OpenApiResponseTransfer
     */
    protected OpenApiResponseTransfer $openApiResponseTransfer;

    /**
     * @var \Doctrine\Inflector\Inflector
     */
    protected Inflector $inflector;

    /**
     * @var string
     */
    protected string $sprykMode = 'project';

    /**
     * @var array
     */
    protected array $parseProperties = [];

    /**
     * @param \SprykerSdk\Zed\AopSdk\AopSdkConfig $config
     * @param \Doctrine\Inflector\Inflector $inflector
     */
    public function __construct(AopSdkConfig $config, Inflector $inflector)
    {
        $this->config = $config;
        $this->inflector = $inflector;
        $this->openApiResponseTransfer = new OpenApiResponseTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\OpenApiRequestTransfer $openApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OpenApiResponseTransfer
     */
    public function build(OpenApiRequestTransfer $openApiRequestTransfer): OpenApiResponseTransfer
    {
        $openApi = $this->load($openApiRequestTransfer->getTargetFileOrFail());

        $organization = $openApiRequestTransfer->getOrganizationOrFail();
        if ($organization === static::SPRYKER) {
            $this->sprykMode = static::CORE; //Set sprykMode based on organization
        }

        $this->generateTransfers($organization, $openApi);

        return $this->openApiResponseTransfer;
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
     * @param string $path
     * @param \cebe\openapi\spec\Operation $operation
     *
     * @return string
     */
    protected function getModuleName(string $path, Operation $operation): string
    {
        if (isset($operation->operationId)) { //Check operationId existing or not
            $operationId = explode('.', $operation->operationId);

            return $this->inflector->classify(current($operationId));
        }

        if ($path === '') { //Set error message
            $messageTransfer = new MessageTransfer();
            $messageTransfer->setMessage(sprintf('Module name not found for path %s', $path));
            $this->openApiResponseTransfer->addError($messageTransfer);

            return '';
        }

        $pathFragments = explode('/', trim($path, '/'));

        return sprintf(ucwords(current($pathFragments)) . '%s', 'Api');
    }

    /**
     * @param string $path
     * @param \cebe\openapi\spec\Operation $operation
     *
     * @return string
     */
    protected function getControllerName(string $path, Operation $operation): string
    {
        if (isset($operation->operationId)) {
            $operationId = explode('.', $operation->operationId);

            return $this->inflector->classify(sprintf(end($operationId) . '%s', 'Controller'));
        }

        $pathFragments = explode('/', trim($path, '/'));
        foreach (array_reverse($pathFragments) as $key => $resource) {
            if ($key === (count($pathFragments) - 1)) {
                $resource = sprintf($resource . '%s', 'Resource');
            }

            if (strpos($resource, '{') === false) {
                return $this->inflector->classify(sprintf("{$resource}%s", 'Controller'));
            }
        }

        $messageTransfer = new MessageTransfer();
        $messageTransfer->setMessage(sprintf('Controller name not found for path %s', $path));
        $this->openApiResponseTransfer->addError($messageTransfer);

        return '';
    }

    /**
     * @param string $organization
     * @param \cebe\openapi\spec\OpenApi $openApi
     *
     * @return void
     */
    protected function generateTransfers(
        string $organization,
        OpenApi $openApi
    ): void {
        $transferDefinitions = [];
        /** @var \cebe\openapi\spec\PathItem $pathItem */
        foreach ($openApi->paths->getPaths() as $path => $pathItem) {
            $transferDefinitions[$path] = $this->getTransferDefinitionFromPathItem($path, $pathItem);
        }

        if ($this->openApiResponseTransfer->getErrors()->count() === 0) {
            $transferBuildSprykCommands = $this->getTransferDefinitionSprykCommands($organization, $transferDefinitions);
            $this->runCommands($transferBuildSprykCommands);
        }
    }

    /**
     * @param string $path
     * @param \cebe\openapi\spec\PathItem $pathItem
     *
     * @return array
     */
    protected function getTransferDefinitionFromPathItem(string $path, PathItem $pathItem): array
    {
        $transferDefinitions = [];

        /** @var \cebe\openapi\spec\Operation $operation */
        foreach ($pathItem->getOperations() as $method => $operation) {
            $transferDefinitions[$method]['controllerName'] = $this->getControllerName($path, $operation);
            $transferDefinitions[$method]['moduleName'] = $this->getModuleName($path, $operation);

            if ($operation->requestBody) {
                $transferDefinitions[$method]['requestBody'] = $this->getRequestBodyPropertiesFromOperation($operation);
            }

            $transferDefinitions[$method]['responses'] = $this->getReponsePropertiesFromOperation($operation);
        }

        return $transferDefinitions;
    }

    /**
     * @param \cebe\openapi\spec\Operation $operation
     *
     * @return array
     */
    protected function getRequestBodyPropertiesFromOperation(Operation $operation): array
    {
        $requestBodyProperties = [];
        /** @var \cebe\openapi\spec\RequestBody $mediaType */
        foreach ($this->getRequestBodyFromOperation($operation) as $mediaType) {
            if (!isset($mediaType->schema)) {
                continue;
            }
            if ($mediaType->schema instanceof Schema) {
                $requestBodyProperties[$this->getClassNameFromSchema($mediaType->schema)] = $this->getRequestBodyPropertiesFromSchema($mediaType->schema);
            }
            if ($mediaType->schema instanceof Reference) {
                $requestBodyProperties[$this->getClassNameFromReference($mediaType->schema)] = $this->getRequestBodyPropertiesFromReference($mediaType->schema);
            }
        }

        return $requestBodyProperties;
    }

    /**
     * @param \cebe\openapi\spec\Operation $operation
     *
     * @return iterable
     */
    protected function getRequestBodyFromOperation(Operation $operation): iterable
    {
        return isset($operation->requestBody) && isset($operation->requestBody->content) ? $operation->requestBody->content : [];
    }

    /**
     * @param \cebe\openapi\spec\Schema $schema
     *
     * @return iterable
     */
    protected function getPropertiesFromSchema(Schema $schema): iterable
    {
        if (isset($schema->properties)) {
            return $schema->properties;
        }

        return [];
    }

    /**
     * @param \cebe\openapi\spec\Reference $reference
     *
     * @return iterable
     */
    protected function getPropertiesFromReference(Reference $reference): iterable
    {
        if (isset($reference->properties)) {
            return $reference->properties;
        }

        return [];
    }

    /**
     * @param \cebe\openapi\spec\Schema $schema
     *
     * @return array
     */
    protected function getRequestBodyPropertiesFromSchema(Schema $schema): array
    {
        foreach ($this->getPropertiesFromSchema($schema) as $schemaObject) {
            if (!isset(($schemaObject->properties))) {
                continue;
            }

            if (empty($schemaObject->properties)) {
                continue;
            }

            if ($schemaObject instanceof Schema) {
                return $this->getRequestBodyPropertiesFromSchema($schemaObject);
            }
            if ($schemaObject instanceof Reference) {
                return $this->getRequestBodyPropertiesFromReference($schemaObject);
            }
        }

        return $this->formatRequestBodyProperties($this->getPropertiesFromSchema($schema));
    }

    /**
     * @param \cebe\openapi\spec\Reference $schema
     *
     * @return array
     */
    protected function getRequestBodyPropertiesFromReference(Reference $schema): array
    {
        foreach ($this->getPropertiesFromReference($schema) as $schemaObject) {
            if (!isset(($schemaObject->properties))) {
                continue;
            }

            if (empty($schemaObject->properties)) {
                continue;
            }

            if ($schemaObject instanceof Schema) {
                return $this->getRequestBodyPropertiesFromSchema($schemaObject);
            }

            if ($schemaObject instanceof Reference) {
                return $this->getRequestBodyPropertiesFromReference($schemaObject);
            }
        }

        return $this->formatRequestBodyProperties($this->getPropertiesFromReference($schema));
    }

    /**
     * @param iterable $properties
     *
     * @return array
     */
    protected function formatRequestBodyProperties(iterable $properties): array
    {
        $requestBodyProperties = [];

        foreach ($properties as $key => $schemaObject) {
            if (isset($schemaObject->type) && $schemaObject->type !== 'array') {
                $requestBodyProperties[$key] = $schemaObject->type;

                continue;
            }

            //If schema object's items not exist then continue
            if (!isset($schemaObject->items)) {
                continue;
            }

            if (isset($schemaObject->items->type)) {
                $requestBodyProperties[$key] = 'array[]:' . $schemaObject->items->type;

                continue;
            }

            if (isset($schemaObject->items->properties) && isset($schemaObject->items->properties['type'])) {
                $requestBodyProperties[$key] = 'array[]:' . $schemaObject->items->properties['type']->type;

                continue;
            }
        }

        return $requestBodyProperties;
    }

    /**
     * @param \cebe\openapi\spec\Operation $operation
     *
     * @return array
     */
    protected function getReponsePropertiesFromOperation(Operation $operation): array
    {
        $responses = [];

        /** @var \cebe\openapi\spec\Response|\cebe\openapi\spec\Reference $content */
        foreach ($this->getResponsesFromOperation($operation) as $content) {
            if (isset($content->content) && !empty($content->content)) {
                $responses = $this->getPropertiesFromOperationContent($content->content, $responses);
            }
        }

        return $responses;
    }

    /**
     * @param array $contents
     * @param array $responses
     *
     * @return array
     */
    protected function getPropertiesFromOperationContent(array $contents, array $responses): array
    {
        foreach ($contents as $response) {
            if (!isset($response->schema)) {
                continue;
            }
            if ($response->schema instanceof Schema) {
                $responses[$this->getClassNameFromSchema($response->schema)] = $this->getReponsePropertiesFromSchema($response->schema, []);
            }
            if ($response->schema instanceof Reference) {
                $responses[$this->getClassNameFromReference($response->schema)] = $this->getReponsePropertiesFromReference($response->schema, []);
            }
        }

        return $responses;
    }

    /**
     * @param \cebe\openapi\spec\Operation $operation
     *
     * @return array
     */
    protected function getResponsesFromOperation(Operation $operation): iterable
    {
        return $operation->responses ?? [];
    }

    /**
     * @param \cebe\openapi\spec\Schema $schema
     * @param array $rootType
     *
     * @return array
     */
    protected function getReponsePropertiesFromSchema(Schema $schema, array $rootType): array
    {
        foreach ($this->getPropertiesFromSchema($schema) as $schemaObject) {
            if ($schemaObject instanceof Schema && isset($schemaObject->properties) && !empty($schemaObject->properties)) {
                $rootType[] = false;

                return $this->getReponsePropertiesFromSchema($schemaObject, $rootType);
            }
            if ($schemaObject instanceof Reference && isset($schemaObject->properties) && !empty($schemaObject->properties)) {
                $rootType[] = false;

                return $this->getReponsePropertiesFromReference($schemaObject, $rootType);
            }

            if (!isset($schemaObject->items)) {
                continue;
            }

            if ($schemaObject->items instanceof Schema && isset($schemaObject->items->properties) && !empty($schemaObject->items->properties)) {
                $rootType[] = true;

                return $this->getReponsePropertiesFromSchema($schemaObject->items, $rootType);
            }

            if ($schemaObject->items instanceof Reference && isset($schemaObject->items->properties) && !empty($schemaObject->items->properties)) {
                $rootType[] = true;

                return $this->getReponsePropertiesFromReference($schemaObject->items, $rootType);
            }
        }
        if (current($rootType) === true) {
            return $this->formatPropertiesToArrayOfClassInstance($this->getClassNameFromSchema($schema));
        }

        return $this->formatResponseProperties($this->getPropertiesFromSchema($schema));
    }

    /**
     * @param \cebe\openapi\spec\Reference $schema
     * @param array $rootType
     *
     * @return array
     */
    protected function getReponsePropertiesFromReference(Reference $schema, array $rootType): array
    {
        foreach ($this->getPropertiesFromReference($schema) as $schemaObject) {
            if ($schemaObject instanceof Schema && isset($schemaObject->properties) && !empty($schemaObject->properties)) {
                $rootType[] = false;

                return $this->getReponsePropertiesFromSchema($schemaObject, $rootType);
            }
            if ($schemaObject instanceof Reference && isset($schemaObject->properties) && !empty($schemaObject->properties)) {
                $rootType[] = false;

                return $this->getReponsePropertiesFromReference($schemaObject, $rootType);
            }

            if (!isset($schemaObject->items)) {
                continue;
            }

            if ($schemaObject->items instanceof Schema && isset($schemaObject->items->properties) && !empty($schemaObject->items->properties)) {
                $rootType[] = true;

                return $this->getReponsePropertiesFromSchema($schemaObject->items, $rootType);
            }

            if ($schemaObject->items instanceof Reference && isset($schemaObject->items->properties) && !empty($schemaObject->items->properties)) {
                $rootType[] = true;

                return $this->getReponsePropertiesFromReference($schemaObject->items, $rootType);
            }
        }

        if (current($rootType) === true) {
            return $this->formatPropertiesToArrayOfClassInstance($this->getClassNameFromReference($schema));
        }

        return $this->formatResponseProperties($this->getPropertiesFromReference($schema));
    }

    /**
     * @param string $className
     *
     * @return array
     */
    protected function formatPropertiesToArrayOfClassInstance(string $className): array
    {
        $refClass = str_replace('Attributes', '', $className);

        return [$this->inflector->pluralize($refClass) => $refClass . '[]:' . $this->inflector->camelize($refClass)];
    }

    /**
     * @param iterable $properties
     *
     * @return array
     */
    protected function formatResponseProperties(iterable $properties): array
    {
        $response = [];
        foreach ($properties as $key => $schemaObject) {
            if (isset($schemaObject->type) && $schemaObject->type !== 'array') {
                $response[$key] = $schemaObject->type;

                continue;
            }

            if (!isset($schemaObject->items)) {
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
        }

        return $response;
    }

    /**
     * @param \cebe\openapi\spec\Schema $schema
     *
     * @return string
     */
    protected function getClassNameFromSchema(Schema $schema): string
    {
        if ($schema->getDocumentPosition()) {
            $referencePath = $schema->getDocumentPosition()->getPath();

            return end($referencePath);
        }

        return '';
    }

    /**
     * @param \cebe\openapi\spec\Reference $reference
     *
     * @return string
     */
    protected function getClassNameFromReference(Reference $reference): string
    {
        if ($reference->getDocumentPosition()) {
            $referencePath = $reference->getDocumentPosition()->getPath();

            return end($referencePath);
        }

        return '';
    }

    /**
     * @param array $parameters
     *
     * @return array
     */
    protected function preparePropertyNameForCommand(array $parameters): array
    {
        $parsedProperties = [];
        foreach ($parameters as $key => $value) {
            $parsedProperties[] = "{$key}:{$value}";
        }

        return $parsedProperties;
    }

    /**
     * @param string $organization
     * @param array $parseProperties
     *
     * @return array
     */
    protected function getTransferDefinitionSprykCommands(string $organization, array $parseProperties): array
    {
        $commandLines = [];

        foreach ($parseProperties as $operations) {
            foreach ($operations as $data) {
                $this->generateTransferCommands($organization, ($data['requestBody'] ?? []), $data['moduleName'], $commandLines);
                $this->generateTransferCommands($organization, ($data['responses'] ?? []), $data['moduleName'], $commandLines);
            }
        }

        return array_values($commandLines);
    }

    /**
     * @param string $organization
     * @param array $parseProperties
     * @param string $moduleName
     * @param array $commandLines
     *
     * @return void
     */
    protected function generateTransferCommands(string $organization, array $parseProperties, string $moduleName, array &$commandLines): void
    {
        foreach ($parseProperties as $command => $parameters) {
            $commandLines[$command] = $this->prepareTransferCommand($organization, $parameters, $command, $moduleName);
        }
    }

    /**
     * @param string $organization
     * @param array $parameters
     * @param string $command
     * @param string $moduleName
     *
     * @return array
     */
    protected function prepareTransferCommand(string $organization, array $parameters, string $command, string $moduleName): array
    {
        if (count($parameters) === 1) {
            $propertyName = array_key_first($parameters);
            $propertyTypes = explode(':', $parameters[$propertyName]);

            if (count($propertyTypes) === 1) {
                return $this->getTransferBuildCommand(
                    $organization,
                    $moduleName,
                    $command,
                    $propertyName,
                    current($propertyTypes),
                    null,
                );
            }

            return $this->getTransferBuildCommand(
                $organization,
                $moduleName,
                $command,
                $propertyName,
                current($propertyTypes),
                end($propertyTypes),
            );
        }

        return $this->getTransferBuildCommand(
            $organization,
            $moduleName,
            $command,
            implode(',', $this->preparePropertyNameForCommand($parameters)),
            null,
            null,
        );
    }

    /**
     * @param string $organization
     * @param string $moduleName
     * @param string $command
     * @param string $propertyName
     * @param string|null $propertyType
     * @param string|null $singular
     *
     * @return array
     */
    protected function getTransferBuildCommand(
        string $organization,
        string $moduleName,
        string $command,
        string $propertyName,
        ?string $propertyType,
        ?string $singular
    ): array {
        $data = [
            'vendor/bin/spryk-run',
            'AddSharedTransferProperty',
            '--mode', $this->sprykMode,
            '--organization', $organization,
            '--module', $moduleName,
            '--name', $command,
            '--propertyName', $propertyName,
        ];

        if (($propertyType !== null)) {
            $data[] = '--propertyType';
            $data[] = $propertyType;
        }

        if (($singular !== null)) {
            $data[] = '--singular';
            $data[] = $singular;
        }

        $data[] = '-n';
        $data[] = '-v';

        return $data;
    }

    /**
     * @param array<array> $commands
     *
     * @return void
     */
    protected function runCommands(array $commands): void
    {
        foreach ($commands as $command) {
            $process = new Process($command, $this->config->getProjectRootPath());
            $process->run(function ($a, $buffer) {
                echo $buffer;
                // For debugging purposes, set a breakpoint here to see issues.
            });
        }
    }
}
