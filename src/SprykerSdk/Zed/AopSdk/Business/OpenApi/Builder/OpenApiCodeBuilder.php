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
use Doctrine\Inflector\Inflector;
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

        $this->setSprykerMode($organization);

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
     * @param string $organization
     *
     * @return void
     */
    protected function setSprykerMode(string $organization): void
    {
        if ($organization === 'Spryker') {
            $this->sprykMode = 'core'; //Set sprykMode based on organization
        }
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
        $transferDefinitions = $this->getTransferDefinitions($openApi);

        if ($this->openApiResponseTransfer->getErrors()->count() === 0) {
            $transferBuildSprykCommands = $this->getTransferDefinitionSprykCommands($organization, $transferDefinitions);
            $this->runCommands($transferBuildSprykCommands);
        }
    }

    /**
     * @param \cebe\openapi\spec\OpenApi $openApi
     *
     * @return array
     */
    protected function getTransferDefinitions(OpenApi $openApi): array
    {
        $transferDefinitions = [];
        /** @var \cebe\openapi\spec\PathItem $pathItem */
        foreach ($openApi->paths->getPaths() as $path => $pathItem) {
            $transferDefinitions[$path] = $this->getTransferDefinitionFromPathItem($path, $pathItem);
        }

        return $transferDefinitions;
    }

    /**
     * @param string $path
     * @param \cebe\openapi\spec\PathItem $pathItem
     *
     * @return array <string, array>
     */
    protected function getTransferDefinitionFromPathItem(string $path, PathItem $pathItem): array
    {
        $transferDefinitions = [];

        /** @var \cebe\openapi\spec\Operation $operation */
        foreach ($pathItem->getOperations() as $method => $operation) {
            $controllerName = $this->getControllerName($path, $operation);
            $moduleName = $this->getModuleName($path, $operation);

            if ($controllerName === '') {
                continue;
            }

            if ($moduleName === '') {
                continue;
            }

            $transferDefinitions[$method]['controllerName'] = $controllerName;

            $transferDefinitions[$method]['moduleName'] = $moduleName;

            if ($operation->requestBody) {
                $transferDefinitions[$method]['requestBody'] = $this->getRequestBodyPropertiesFromOperation($operation);
            }

            $transferDefinitions[$method]['responses'] = $this->getReponsePropertiesFromOperation($operation);
        }

        return $transferDefinitions;
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
            if ($resource === '') {
                continue;
            }

            if ($key === (count($pathFragments) - 1)) {
                $resource = sprintf($resource . '%s', 'Resource');
            }

            if (strpos($resource, '{') === false) {
                return $this->inflector->classify(sprintf("{$resource}%s", 'Controller'));
            }
        }

        $messageTransfer = new MessageTransfer();
        $messageTransfer->setMessage(sprintf('Controller name not found for path: %s', $path));
        $this->openApiResponseTransfer->addError($messageTransfer);

        return '';
    }

    /**
     * @param string $path
     * @param \cebe\openapi\spec\Operation $operation
     *
     * @return string
     */
    protected function getModuleName(string $path, Operation $operation): string
    {
        if (isset($operation->operationId)) {
            $operationId = explode('.', $operation->operationId);

            return $this->inflector->classify(current($operationId));
        }

        $path = trim($path, '/');

        if ($path === '') {
            $messageTransfer = new MessageTransfer();
            $messageTransfer->setMessage(sprintf('Module name not found for path: %s', $path));
            $this->openApiResponseTransfer->addError($messageTransfer);

            return '';
        }
        $pathFragments = explode('/', trim($path, '/'));

        return sprintf(ucwords(current($pathFragments)) . '%s', 'Api');
    }

    /**
     * @param \cebe\openapi\spec\Operation $operation
     *
     * @return array <string, array>
     */
    protected function getRequestBodyPropertiesFromOperation(Operation $operation): array
    {
        $requestBodyProperties = [];
        /** @var \cebe\openapi\spec\RequestBody $mediaType */
        foreach ($this->getRequestBodyFromOperation($operation) as $mediaType) {
            if (isset($mediaType->schema)) {
                $requestBodyProperties[$this->getTransferNameFromSchemaOrReference($mediaType->schema)] = $this->getRequestBodyPropertiesFromSchemaOrReference($mediaType->schema);
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
     * @param \cebe\openapi\spec\Schema|\cebe\openapi\spec\Reference $schemaOrReference
     *
     * @return array<int|string, mixed>
     */
    protected function getRequestBodyPropertiesFromSchemaOrReference($schemaOrReference): array
    {
        foreach ($this->getPropertiesFromSchemaOrReference($schemaOrReference) as $schemaOrReferenceObject) {
            if (isset($schemaOrReferenceObject->properties) && !empty($schemaOrReferenceObject->properties)) {
                return $this->getRequestBodyPropertiesFromSchemaOrReference($schemaOrReferenceObject);
            }
        }

        return $this->prepareRequestBodyProperties($this->getPropertiesFromSchemaOrReference($schemaOrReference));
    }

    /**
     * @param \cebe\openapi\spec\Schema|\cebe\openapi\spec\Reference $schemaOrReference
     *
     * @return iterable
     */
    protected function getPropertiesFromSchemaOrReference($schemaOrReference): iterable
    {
        return $schemaOrReference->properties ?? [];
    }

    /**
     * @param iterable $properties
     *
     * @return array<int|string, mixed>
     */
    protected function prepareRequestBodyProperties(iterable $properties): array
    {
        $requestBodyProperties = [];
        foreach ($properties as $key => $schemaOrReferenceObject) {
            if (isset($schemaOrReferenceObject->type) && $schemaOrReferenceObject->type !== 'array') {
                $requestBodyProperties[$key] = $schemaOrReferenceObject->type;
            }

            if (isset($schemaOrReferenceObject->items) && $schemaOrReferenceObject->items !== null) {
                $requestBodyProperties[$key] = $this->generateArrayOfDataType($schemaOrReferenceObject->items);
            }
        }

        return $requestBodyProperties;
    }

    /**
     * @param \cebe\openapi\spec\Schema|\cebe\openapi\spec\Reference $schemaOrReference
     *
     * @return string
     */
    protected function getTransferNameFromSchemaOrReference($schemaOrReference): string
    {
        if($schemaOrReference->getDocumentPosition()){
            $referencePath = $schemaOrReference->getDocumentPosition()->getPath();
            return end($referencePath);
        }

        return '';
    }

    /**
     * @param \cebe\openapi\spec\Operation $operation
     *
     * @return array <string, string>
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
     * @param \cebe\openapi\spec\Operation $operation
     *
     * @return iterable
     */
    protected function getResponsesFromOperation(Operation $operation): iterable
    {
        return ($operation->responses ?? []);
    }

    /**
     * @param array $contents
     * @param array $responses
     *
     * @return array <string, string>
     */
    protected function getPropertiesFromOperationContent(array $contents, array $responses): array
    {
        foreach ($contents as $response) {
            if (isset($response->schema)) {
                $responses[$this->getTransferNameFromSchemaOrReference($response->schema)] = $this->getReponsePropertiesFromSchemaOrReference($response->schema, []);
            }
        }

        return $responses;
    }

    /**
     * @param \cebe\openapi\spec\Schema|\cebe\openapi\spec\Reference $schemaOrReference
     * @param array $rootType
     *
     * @return array
     */
    protected function getReponsePropertiesFromSchemaOrReference($schemaOrReference, array $rootType): array
    {
        foreach ($this->getPropertiesFromSchemaOrReference($schemaOrReference) as $schemaOrReferenceObject) {
            if (isset($schemaOrReferenceObject->properties) && !empty($schemaOrReferenceObject->properties)) {
                $rootType[] = false;

                return $this->getReponsePropertiesFromSchemaOrReference($schemaOrReferenceObject, $rootType);
            }

            if (isset($schemaOrReferenceObject->items->properties) && !empty($schemaOrReferenceObject->items->properties)) {
                $rootType[] = true;

                return $this->getReponsePropertiesFromSchemaOrReference($schemaOrReferenceObject->items, $rootType);
            }
        }
        if (current($rootType) === true) {
            return $this->generateArrayOfClassInstance($this->getTransferNameFromSchemaOrReference($schemaOrReference));
        }

        return $this->prepareResponseProperties($this->getPropertiesFromSchemaOrReference($schemaOrReference));
    }

    /**
     * @param \cebe\openapi\spec\Schema|\cebe\openapi\spec\Reference $schemaOrReference
     *
     * @return string
     */
    protected function generateArrayOfDataType($schemaOrReference): string
    {
        if (isset($schemaOrReference->type)) {
            return 'array[]:' . $schemaOrReference->type;
        }

        if (isset($schemaOrReference->properties) && isset($schemaOrReference->properties['type'])) {
            return 'array[]:' . $schemaOrReference->properties['type']->type;
        }

        return '';
    }

    /**
     * @param string $className
     *
     * @return array <string, string>
     */
    protected function generateArrayOfClassInstance(string $className): array
    {
        $className = str_replace('Attributes', '', $className);

        return [$this->inflector->pluralize($className) => $className . '[]:' . $this->inflector->camelize($className)];
    }

    /**
     * @param iterable $properties
     *
     * @return array<int|string, mixed>
     */
    protected function prepareResponseProperties(iterable $properties): array
    {
        $response = [];
        foreach ($properties as $key => $schemaOrReferenceObject) {
            if (isset($schemaOrReferenceObject->type) && $schemaOrReferenceObject->type !== 'array') {
                $response[$key] = $schemaOrReferenceObject->type;
            }

            if (isset($schemaOrReferenceObject->items)) {
                $response[$key] = $this->generateArrayOfDataType($schemaOrReferenceObject->items);
            }
        }

        return $response;
    }

    /**
     * @param string $organization
     * @param array $transferDefinitions
     *
     * @return array[
     *      0 => array,
     *      1 => array
     * ]
     */
    protected function getTransferDefinitionSprykCommands(string $organization, array $transferDefinitions): array
    {
        $commandLines = [];
        foreach ($transferDefinitions as $transferDefinition) {
            foreach ($transferDefinition as $data) {
                $this->generateTransferCommands($organization, ($data['requestBody'] ?? []), $data['moduleName'], $commandLines);
                $this->generateTransferCommands($organization, ($data['responses'] ?? []), $data['moduleName'], $commandLines);
            }
        }

        return array_values($commandLines);
    }

    /**
     * @param string $organization
     * @param array $transferDefinitions
     * @param string $moduleName
     * @param array $commandLines
     *
     * @return void
     */
    protected function generateTransferCommands(string $organization, array $transferDefinitions, string $moduleName, array &$commandLines): void
    {
        foreach ($transferDefinitions as $command => $transferDefinition) {
            $commandLines[$command] = $this->prepareTransferCommand($organization, $transferDefinition, $command, $moduleName);
        }
    }

    /**
     * @param string $organization
     * @param array $parameters
     * @param string $command
     * @param string $moduleName
     *
     * @return array[
     *      0 => array,
     *      1 => array
     * ]
     */
    protected function prepareTransferCommand(string $organization, array $parameters, string $command, string $moduleName): array
    {
        return $this->getTransferBuildCommand(
            $organization,
            $moduleName,
            $command,
            $this->getTransferPropertyName($parameters),
            $this->getTransferPropertyType($parameters),
            $this->getTransferPropertySingular($parameters),
        );
    }

    /**
     * @param array $parameters
     *
     * @return string
     */
    protected function getTransferPropertyName(array $parameters): string
    {
        if (count($parameters) === 1) {
            return array_key_first($parameters);
        }

        return implode(',', $this->preparePropertyNameForCommand($parameters));
    }

    /**
     * @param array $parameters
     *
     * @return array <int, string>
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
     * @param array $parameters
     *
     * @return string|null
     */
    protected function getTransferPropertyType(array $parameters)
    {
        if (count($parameters) === 1) {
            $propertyName = array_key_first($parameters);

            return current(explode(':', $parameters[$propertyName]));
        }

        return null;
    }

    /**
     * @param array $parameters
     *
     * @return string|null
     */
    protected function getTransferPropertySingular(array $parameters)
    {
        $propertyName = array_key_first($parameters);
        $propertyTypes = explode(':', $parameters[$propertyName]);
        if (count($parameters) !== 1 || count($propertyTypes) !== 1) {
            return end($propertyTypes);
        }

        return null;
    }

     /**
      * @param string $organization
      * @param string $moduleName
      * @param string $transferName
      * @param string $propertyName
      * @param string|null $propertyType
      * @param string|null $singular
      *
      * @return array[
      *      0 => string,
      *      1 => string
      * ]
      */
    protected function getTransferBuildCommand(
        string $organization,
        string $moduleName,
        string $transferName,
        string $propertyName,
        ?string $propertyType,
        ?string $singular
    ): array {
        $commandData = [
            'vendor/bin/spryk-run',
            'AddSharedTransferProperty',
            '--mode', $this->sprykMode,
            '--organization', $organization,
            '--module', $moduleName,
            '--name', $transferName,
            '--propertyName', $propertyName,
        ];

        if (($propertyType !== null)) {
            $commandData[] = '--propertyType';
            $commandData[] = $propertyType;
        }

        if (($singular !== null)) {
            $commandData[] = '--singular';
            $commandData[] = $singular;
        }

        $commandData[] = '-n';
        $commandData[] = '-v';

        return $commandData;
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
